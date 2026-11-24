<?php
/**
 * Bootstrap (functions live in style.css header theme; WP loads functions.php).
 *
 * @package dummy-product
 */

require_once get_template_directory() . '/inc/product-config.php';
require_once get_template_directory() . '/inc/suite-pages.php';
require_once get_template_directory() . '/inc/donate-stripe.php';
require_once get_template_directory() . '/inc/enqueue.php';

/**
 * Theme setup.
 */
function dummy_product_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	register_nav_menus(
		array(
			'primary' => __( 'Primary', 'dummy-product' ),
		)
	);
}

/**
 * Mark suite requests for CSS.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function dummy_product_body_class( $classes ) {
	if ( dummy_product_is_suite() ) {
		$classes[] = 'is-suite';
	}
	return $classes;
}
add_filter( 'body_class', 'dummy_product_body_class' );
add_action( 'after_setup_theme', 'dummy_product_setup' );
