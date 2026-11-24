<?php
/**
 * Stripe Checkout + Ed25519 supporter token issuance for product sites.
 *
 * Requires on CF hosts only: DUMMY_STRIPE_SECRET, DUMMY_DONOR_PRIVATE_SEED.
 * Plugin end users never configure these — they paste a code; the plugin verifies
 * with the public key bundled in dummykit.
 *
 * @package dummy-product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Issue supporter token (Ed25519). Prefer kit helper when available; else local sodium.
 *
 * @param string $product Product slug.
 * @param string $email   Email.
 * @param int    $exp     Expiry.
 * @return string|WP_Error
 */
function dummy_product_donor_token_issue( $product, $email = '', $exp = 0 ) {
	if ( function_exists( 'dummykit_donor_token_issue' ) ) {
		return dummykit_donor_token_issue( $product, $email, $exp );
	}

	// Standalone theme path (kit not loaded on marketing site).
	if ( ! function_exists( 'sodium_crypto_sign_detached' ) || ! defined( 'DUMMY_DONOR_PRIVATE_SEED' ) ) {
		return new WP_Error( 'donor', 'Donor signing not configured on this host.' );
	}

	$product = sanitize_key( $product );
	$email   = strtolower( trim( (string) $email ) );
	$hash    = $email !== '' ? substr( hash( 'sha256', $email ), 0, 16 ) : 'anon';
	$nonce   = wp_generate_password( 8, false, false );
	$payload = $product . '|' . $hash . '|' . (int) $exp . '|' . $nonce;

	$seed_hex = (string) DUMMY_DONOR_PRIVATE_SEED;
	if ( strlen( $seed_hex ) !== 64 || ! ctype_xdigit( $seed_hex ) ) {
		return new WP_Error( 'donor', 'Invalid DUMMY_DONOR_PRIVATE_SEED.' );
	}

	$keypair = sodium_crypto_sign_seed_keypair( hex2bin( $seed_hex ) );
	$secret  = sodium_crypto_sign_secretkey( $keypair );
	$sig     = sodium_crypto_sign_detached( $payload, $secret );

	$b64 = static function ( $raw ) {
		return rtrim( strtr( base64_encode( $raw ), '+/', '-_' ), '=' );
	};

	return $b64( $payload ) . '.' . $b64( $sig );
}

/**
 * Create Stripe Checkout Session (server-side curl; no SDK required).
 *
 * @param array<string,mixed> $args amount, currency, email, success_url, cancel_url.
 * @return array<string,mixed>|WP_Error
 */
function dummy_product_stripe_checkout_create( $args ) {
	if ( ! defined( 'DUMMY_STRIPE_SECRET' ) || ! DUMMY_STRIPE_SECRET ) {
		return new WP_Error( 'stripe', 'Stripe secret not configured.' );
	}

	$config = dummy_product_config();
	$amount = isset( $args['amount'] ) ? (int) $args['amount'] : 0; // cents — donor-chosen; no default tip
	if ( $amount < 50 ) {
		return new WP_Error( 'amount', 'Choose an amount (minimum 0.50 EUR for Stripe).' );
	}
	$currency = isset( $args['currency'] ) ? sanitize_key( $args['currency'] ) : 'eur';
	$email  = isset( $args['email'] ) ? sanitize_email( $args['email'] ) : '';

	$success = isset( $args['success_url'] ) ? esc_url_raw( $args['success_url'] ) : home_url( '/donate/?success=1&session_id={CHECKOUT_SESSION_ID}' );
	$cancel  = isset( $args['cancel_url'] ) ? esc_url_raw( $args['cancel_url'] ) : home_url( '/donate/?canceled=1' );

	$body = array(
		'mode'                                   => 'payment',
		'success_url'                            => $success,
		'cancel_url'                             => $cancel,
		'line_items[0][price_data][currency]'    => $currency,
		'line_items[0][price_data][product_data][name]' => $config['name'] . ' donation',
		'line_items[0][price_data][unit_amount]' => $amount,
		'line_items[0][quantity]'                => 1,
		'metadata[product]'                      => $config['slug'],
	);
	if ( $email ) {
		$body['customer_email'] = $email;
	}

	$response = wp_remote_post(
		'https://api.stripe.com/v1/checkout/sessions',
		array(
			'headers' => array(
				'Authorization' => 'Bearer ' . DUMMY_STRIPE_SECRET,
			),
			'body'    => $body,
			'timeout' => 30,
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $data['id'] ) || empty( $data['url'] ) ) {
		return new WP_Error( 'stripe', 'Could not create Checkout Session.' );
	}

	return $data;
}

/**
 * REST: start checkout + webhook.
 */
function dummy_product_stripe_rest() {
	register_rest_route(
		'dummy-product/v1',
		'/donate/checkout',
		array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => function ( WP_REST_Request $req ) {
				$session = dummy_product_stripe_checkout_create(
					array(
						'email'    => (string) $req->get_param( 'email' ),
						'amount'   => (int) $req->get_param( 'amount' ),
						'currency' => (string) $req->get_param( 'currency' ) ?: 'eur',
					)
				);
				if ( is_wp_error( $session ) ) {
					return $session;
				}
				return array(
					'id'  => $session['id'],
					'url' => $session['url'],
				);
			},
		)
	);

	register_rest_route(
		'dummy-product/v1',
		'/stripe-webhook',
		array(
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => 'dummy_product_stripe_webhook',
		)
	);

	register_rest_route(
		'dummy-product/v1',
		'/donate/token',
		array(
			'methods'             => 'GET',
			'permission_callback' => '__return_true',
			'callback'            => function ( WP_REST_Request $req ) {
				$session_id = sanitize_text_field( (string) $req->get_param( 'session_id' ) );
				if ( ! $session_id || ! defined( 'DUMMY_STRIPE_SECRET' ) ) {
					return new WP_Error( 'invalid', 'Missing session', array( 'status' => 400 ) );
				}
				$response = wp_remote_get(
					'https://api.stripe.com/v1/checkout/sessions/' . rawurlencode( $session_id ),
					array(
						'headers' => array( 'Authorization' => 'Bearer ' . DUMMY_STRIPE_SECRET ),
						'timeout' => 20,
					)
				);
				if ( is_wp_error( $response ) ) {
					return $response;
				}
				$data = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( empty( $data['payment_status'] ) || 'paid' !== $data['payment_status'] ) {
					return new WP_Error( 'unpaid', 'Payment not complete', array( 'status' => 402 ) );
				}
				$config = dummy_product_config();
				$email  = isset( $data['customer_details']['email'] ) ? $data['customer_details']['email'] : '';
				$token  = dummy_product_donor_token_issue( $config['slug'], $email, 0 );
				return array(
					'token'   => $token,
					'product' => $config['slug'],
					'message' => 'Paste this code in the plugin Suite / Settings to hide banners permanently.',
				);
			},
		)
	);
}
add_action( 'rest_api_init', 'dummy_product_stripe_rest' );

/**
 * Minimal Stripe webhook (checkout.session.completed) — stores issued token optionally.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function dummy_product_stripe_webhook( WP_REST_Request $request ) {
	$payload = $request->get_body();
	$event   = json_decode( $payload, true );
	if ( ! is_array( $event ) || empty( $event['type'] ) ) {
		return new WP_Error( 'invalid', 'Bad payload', array( 'status' => 400 ) );
	}

	if ( 'checkout.session.completed' === $event['type'] ) {
		$session = isset( $event['data']['object'] ) ? $event['data']['object'] : array();
		$product = isset( $session['metadata']['product'] ) ? sanitize_key( $session['metadata']['product'] ) : dummy_product_config()['slug'];
		$email   = isset( $session['customer_details']['email'] ) ? sanitize_email( $session['customer_details']['email'] ) : '';
		$token   = dummy_product_donor_token_issue( $product, $email, 0 );
		update_option( 'dummy_product_last_donor_token_' . $product, $token, false );
		/**
		 * Fires when a donation checkout completes.
		 *
		 * @param string $token   Token.
		 * @param string $product Product.
		 * @param array  $session Session.
		 */
		do_action( 'dummy_product_donation_completed', $token, $product, $session );
	}

	return new WP_REST_Response( array( 'received' => true ), 200 );
}
