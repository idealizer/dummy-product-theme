<?php
/**
 * Per-domain product config — Content Factory product sites only.
 *
 * End users of dummy* plugins never set these. Plugins need zero wp-config.
 *
 * On OUR hosts (dummysql.com, …) in wp-config.php:
 *   define( 'DUMMY_PRODUCT_SLUG', 'dummysql' );
 *   define( 'DUMMY_PRODUCT_LABEL', 'SQL' );
 *   define( 'DUMMY_SUPPORT_INTAKE', 'https://www.dummysupport.app' );
 *   define( 'DUMMY_STRIPE_SECRET', 'sk_...' );
 *   define( 'DUMMY_DONOR_PRIVATE_SEED', '…32-byte hex…' );
 *
 * @package dummy-product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hostname for this install, without port or a leading www.
 *
 * @return string
 */
function dummy_product_request_host() {
	$host = '';
	if ( function_exists( 'home_url' ) ) {
		$parsed = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
		if ( is_string( $parsed ) && $parsed !== '' ) {
			$host = $parsed;
		}
	}
	if ( $host === '' && isset( $_SERVER['HTTP_HOST'] ) ) {
		$host = (string) wp_unslash( $_SERVER['HTTP_HOST'] );
	}
	$host = strtolower( $host );
	$host = (string) preg_replace( '/:\d+$/', '', $host );
	$host = (string) preg_replace( '/^www\./', '', $host );
	return $host;
}

/**
 * Suite marketing site (one domain, a page per tool).
 *
 * @return bool
 */
function dummy_product_is_suite() {
	if ( dummy_product_request_host() === 'dummytools.app' ) {
		return true;
	}
	return defined( 'DUMMY_PRODUCT_SLUG' ) && sanitize_key( DUMMY_PRODUCT_SLUG ) === 'dummytools';
}

/**
 * @return array{slug:string,label:string,name:string,support_base:string,tagline:string,suite:bool}
 */
function dummy_product_config() {
	$support_base = defined( 'DUMMY_SUPPORT_INTAKE' )
		? untrailingslashit( DUMMY_SUPPORT_INTAKE )
		: 'https://www.dummysupport.app';

	if ( dummy_product_is_suite() ) {
		return array(
			'slug'         => 'dummytools',
			'label'        => 'TOOLS',
			'name'         => 'dummyTOOLS',
			'support_base' => $support_base,
			'tagline'      => '',
			'suite'        => true,
		);
	}

	$slug  = defined( 'DUMMY_PRODUCT_SLUG' ) ? sanitize_key( DUMMY_PRODUCT_SLUG ) : 'dummysql';
	$label = defined( 'DUMMY_PRODUCT_LABEL' ) ? preg_replace( '/[^A-Za-z0-9]/', '', DUMMY_PRODUCT_LABEL ) : 'SQL';

	$taglines = array(
		'dummysql'     => 'Your database, without the dread.',
		'dummylang'    => 'Translate plugins & themes without the enterprise tax.',
		'dummysupport' => 'AI-native tickets. Eat our own dogfood.',
		'dummycontent' => 'Images, text, voice, music, video — one playful hub.',
		'dummyjob'     => 'Hire and run your team without the HR headache.',
		'dummyteam'    => 'Digital coworkers that actually show up.',
		'dummyseo'     => 'Rank without the rank-math anxiety.',
		'dummyfinance' => 'Books that don’t feel like homework.',
		'dummycrm'     => 'Customers, without the HubSpot hangover.',
		'dummystyle'   => 'Layout freedom without the Elementor bloat.',
	);

	return array(
		'slug'         => $slug,
		'label'        => $label,
		'name'         => 'dummy' . $label,
		'support_base' => $support_base,
		'tagline'      => isset( $taglines[ $slug ] ) ? $taglines[ $slug ] : 'Free. Fun. Seriously useful.',
		'suite'        => false,
	);
}
