<?php
/**
 * Suite landings stored as pages so editors and dummyLANG can change them.
 *
 * Seeds only missing slugs. Existing page content is left alone.
 *
 * @package dummy-product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product pages for the suite grid (source language only).
 *
 * @return WP_Post[]
 */
function dummy_product_suite_cards() {
	$query = new WP_Query(
		array(
			'post_type'              => 'page',
			'post_status'            => 'publish',
			'posts_per_page'         => 50,
			'orderby'                => 'menu_order',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'meta_key'               => '_wp_page_template',
			'meta_value'             => 'page-product.php',
		)
	);

	$cards = array();
	foreach ( $query->posts as $post ) {
		if ( ! $post instanceof WP_Post ) {
			continue;
		}
		$lang = get_post_meta( $post->ID, '_dummylang_lang', true );
		if ( is_string( $lang ) && $lang !== '' && $lang !== 'en_US' ) {
			continue;
		}
		$source = get_post_meta( $post->ID, '_dummylang_source', true );
		if ( $source ) {
			continue;
		}
		$cards[] = $post;
	}
	return $cards;
}

/**
 * Page definitions inserted when the slug does not exist yet.
 *
 * @return array<int,array<string,mixed>>
 */
function dummy_product_suite_catalog() {
	$soon = 'soon';
	$live = 'live';
	return array(
		array(
			'slug'    => 'home',
			'title'   => 'dummyTOOLS',
			'excerpt' => 'Free WordPress tools, one home.',
			'order'   => 0,
			'front'   => true,
			'content' => '<p>One home for the dummy suite. Free forever. No upsell.</p><p>Built by Content Factory OÜ. Open a tool to see what it does.</p>',
		),
		array(
			'slug'    => 'dummycontent',
			'title'   => 'dummyCONTENT',
			'excerpt' => 'Images, text, voice, music, video — one playful hub.',
			'order'   => 10,
			'state'   => $soon,
			'badge'   => 'Coming soon',
			'content' => '<p>dummyCONTENT is on the way: images, text, voice, music, and video in one playful hub.</p><p>Free, like the rest of the suite.</p>',
		),
		array(
			'slug'    => 'dummycrm',
			'title'   => 'dummyCRM',
			'excerpt' => 'Customers, without the HubSpot hangover.',
			'order'   => 20,
			'state'   => $soon,
			'badge'   => 'Coming soon',
			'content' => '<p>dummyCRM is on the way: customers, conversations, and follow-ups without the HubSpot hangover.</p><p>Free, like the rest of the suite.</p>',
		),
		array(
			'slug'    => 'dummyfiles',
			'title'   => 'dummyFILES',
			'excerpt' => 'A two-panel file commander.',
			'order'   => 30,
			'state'   => $live,
			'badge'   => 'Available',
			'content' => '<p>dummyFILES is a two-panel file commander. Both sides of the job stay on screen, so copying, moving, and comparing files feels like a desktop commander.</p><p>The desktop build is still shipped as FilePad. This page is its home in the suite.</p><p>Free forever. No upsell.</p>',
		),
		array(
			'slug'    => 'dummyfinance',
			'title'   => 'dummyFINANCE',
			'excerpt' => 'Books that do not feel like homework.',
			'order'   => 40,
			'state'   => $soon,
			'badge'   => 'Coming soon',
			'content' => '<p>dummyFINANCE is on the way: books that do not feel like homework.</p><p>Free, like the rest of the suite.</p>',
		),
		array(
			'slug'    => 'dummyjob',
			'title'   => 'dummyJOB',
			'excerpt' => 'Hire and run your team without the HR headache.',
			'order'   => 50,
			'state'   => $soon,
			'badge'   => 'Coming soon',
			'content' => '<p>dummyJOB is on the way: hiring and the day-to-day of a team, without the HR headache.</p><p>Free, like the rest of the suite.</p>',
		),
		array(
			'slug'    => 'dummylang',
			'title'   => 'dummyLANG',
			'excerpt' => 'Translate plugins and themes without the enterprise tax.',
			'order'   => 60,
			'state'   => $soon,
			'badge'   => 'Coming soon',
			'content' => '<p>dummyLANG is on the way: translate plugins and themes without the enterprise tax.</p><p>Free, like the rest of the suite.</p>',
		),
		array(
			'slug'    => 'dummyseo',
			'title'   => 'dummySEO',
			'excerpt' => 'Rank without the rank-math anxiety.',
			'order'   => 70,
			'state'   => $soon,
			'badge'   => 'Coming soon',
			'content' => '<p>dummySEO is on the way: rankings without the rank-math anxiety.</p><p>Free, like the rest of the suite.</p>',
		),
		array(
			'slug'    => 'dummysql',
			'title'   => 'dummySQL',
			'excerpt' => 'Your database, without the dread.',
			'order'   => 80,
			'state'   => $live,
			'badge'   => 'Available',
			'content' => '<p>Look at your WordPress database without the dread. dummySQL is a free database explorer for people who would rather see the tables than memorize another query.</p><ul><li>Browse tables and rows on the site you already run</li><li>Read-only by default, so a look around stays a look around</li><li>Lives under Tools, or under dummyTOOLS when several dummy plugins are active</li></ul><p>Free forever. No upsell. If it helps, donate whatever feels fair.</p>',
		),
		array(
			'slug'    => 'dummysupport',
			'title'   => 'dummySUPPORT',
			'excerpt' => 'AI-native tickets. We use it ourselves.',
			'order'   => 90,
			'state'   => $soon,
			'badge'   => 'Coming soon',
			'content' => '<p>dummySUPPORT is on the way: tickets the way we already run them, with AI in the loop and no helpdesk middleman.</p><p>Free, like the rest of the suite.</p>',
		),
		array(
			'slug'    => 'dummyteam',
			'title'   => 'dummyTEAM',
			'excerpt' => 'Digital coworkers that actually show up.',
			'order'   => 100,
			'state'   => $soon,
			'badge'   => 'Coming soon',
			'content' => '<p>dummyTEAM is on the way: digital coworkers that actually show up.</p><p>Free, like the rest of the suite.</p>',
		),
		array(
			'slug'    => 'terms',
			'title'   => 'Terms',
			'excerpt' => '',
			'order'   => 200,
			'content' => '<p>The dummy suite is published by Content Factory OÜ, Tornimäe tn 5, 10145 Tallinn, Estonia. The tools are free to use. Donations are optional.</p>',
		),
		array(
			'slug'    => 'privacy',
			'title'   => 'Privacy',
			'excerpt' => '',
			'order'   => 210,
			'content' => '<p>Content Factory OÜ, Tornimäe tn 5, 10145 Tallinn, Estonia, runs this site. Account and donation details are used to operate the site and to send a supporter code when you donate.</p>',
		),
	);
}

/**
 * Insert suite pages that are not already stored.
 */
function dummy_product_suite_seed() {
	if ( ! dummy_product_is_suite() ) {
		return;
	}
	if ( get_option( 'dummy_product_suite_seeded' ) ) {
		dummy_product_suite_protect_brands();
		return;
	}

	$front_id = 0;
	foreach ( dummy_product_suite_catalog() as $page ) {
		$slug = isset( $page['slug'] ) ? sanitize_title( $page['slug'] ) : '';
		if ( $slug === '' ) {
			continue;
		}
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $existing instanceof WP_Post ) {
			if ( ! empty( $page['front'] ) ) {
				$front_id = (int) $existing->ID;
			}
			continue;
		}

		$is_product = isset( $page['state'] );
		$post_id    = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => isset( $page['title'] ) ? $page['title'] : $slug,
				'post_name'    => $slug,
				'post_excerpt' => isset( $page['excerpt'] ) ? $page['excerpt'] : '',
				'post_content' => isset( $page['content'] ) ? $page['content'] : '',
				'menu_order'   => isset( $page['order'] ) ? (int) $page['order'] : 0,
			),
			true
		);
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}
		update_post_meta( $post_id, '_dummylang_lang', 'en_US' );
		if ( $is_product ) {
			update_post_meta( $post_id, '_wp_page_template', 'page-product.php' );
			update_post_meta( $post_id, '_dummy_suite_state', $page['state'] === 'live' ? 'live' : 'soon' );
			update_post_meta( $post_id, '_dummy_suite_badge', isset( $page['badge'] ) ? $page['badge'] : '' );
		}
		if ( ! empty( $page['front'] ) ) {
			$front_id = (int) $post_id;
		}
	}

	if ( $front_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_id );
	}
	if ( get_option( 'permalink_structure' ) !== '/%postname%/' ) {
		update_option( 'permalink_structure', '/%postname%/' );
		flush_rewrite_rules( false );
	}

	update_option( 'dummy_product_suite_seeded', 1, false );
	dummy_product_suite_protect_brands();
}
add_action( 'init', 'dummy_product_suite_seed', 1 );
add_action( 'after_switch_theme', 'dummy_product_suite_seed' );

/**
 * Keep suite brand names out of dummyLANG translations. Runs once.
 */
function dummy_product_suite_protect_brands() {
	if ( ! dummy_product_is_suite() || get_option( 'dummy_product_suite_brands' ) ) {
		return;
	}
	if ( ! function_exists( 'dummylang_settings_get' ) || ! function_exists( 'dummylang_settings_update' ) ) {
		return;
	}
	$brands  = array(
		'dummyTOOLS',
		'dummySQL',
		'dummyFILES',
		'dummyCONTENT',
		'dummyCRM',
		'dummyFINANCE',
		'dummyJOB',
		'dummyLANG',
		'dummySEO',
		'dummySUPPORT',
		'dummyTEAM',
		'FilePad',
		'Content Factory',
		'WordPress',
	);
	$current = dummylang_settings_get();
	$list    = isset( $current['do_not_translate'] ) && is_array( $current['do_not_translate'] )
		? $current['do_not_translate']
		: array();
	$next    = array();
	foreach ( array_merge( $list, $brands ) as $word ) {
		$word = trim( (string) $word );
		if ( $word !== '' && ! in_array( $word, $next, true ) ) {
			$next[] = $word;
		}
	}
	dummylang_settings_update( array( 'do_not_translate' => $next ) );
	update_option( 'dummy_product_suite_brands', 1, false );
}

/**
 * Editable card badge for product pages.
 */
function dummy_product_suite_badge_box() {
	add_meta_box(
		'dummy_product_suite_badge',
		__( 'Suite card', 'dummy-product' ),
		'dummy_product_suite_badge_box_render',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'dummy_product_suite_badge_box' );

/**
 * @param WP_Post $post Page.
 */
function dummy_product_suite_badge_box_render( $post ) {
	wp_nonce_field( 'dummy_product_suite_badge', 'dummy_product_suite_badge_nonce' );
	$badge = get_post_meta( $post->ID, '_dummy_suite_badge', true );
	$state = get_post_meta( $post->ID, '_dummy_suite_state', true );
	$badge = is_string( $badge ) ? $badge : '';
	$state = $state === 'live' ? 'live' : 'soon';
	?>
	<p>
		<label for="dummy_suite_badge"><?php esc_html_e( 'Badge', 'dummy-product' ); ?></label>
		<input type="text" id="dummy_suite_badge" name="dummy_suite_badge" value="<?php echo esc_attr( $badge ); ?>" style="width:100%" />
	</p>
	<p>
		<label for="dummy_suite_state"><?php esc_html_e( 'Badge color', 'dummy-product' ); ?></label>
		<select id="dummy_suite_state" name="dummy_suite_state" style="width:100%">
			<option value="live" <?php selected( $state, 'live' ); ?>><?php esc_html_e( 'Green', 'dummy-product' ); ?></option>
			<option value="soon" <?php selected( $state, 'soon' ); ?>><?php esc_html_e( 'Orange', 'dummy-product' ); ?></option>
		</select>
	</p>
	<?php
}

/**
 * @param int $post_id Page ID.
 */
function dummy_product_suite_badge_save( $post_id ) {
	if ( ! isset( $_POST['dummy_product_suite_badge_nonce'] ) ) {
		return;
	}
	$nonce = sanitize_text_field( wp_unslash( $_POST['dummy_product_suite_badge_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'dummy_product_suite_badge' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}
	$badge = isset( $_POST['dummy_suite_badge'] ) ? sanitize_text_field( wp_unslash( $_POST['dummy_suite_badge'] ) ) : '';
	$state = ( isset( $_POST['dummy_suite_state'] ) && sanitize_key( wp_unslash( $_POST['dummy_suite_state'] ) ) === 'live' ) ? 'live' : 'soon';
	update_post_meta( $post_id, '_dummy_suite_badge', $badge );
	update_post_meta( $post_id, '_dummy_suite_state', $state );
}
add_action( 'save_post_page', 'dummy_product_suite_badge_save' );
