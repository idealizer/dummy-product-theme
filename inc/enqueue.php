<?php
/**
 * Theme assets.
 *
 * @package dummy-product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue.
 */
function dummy_product_enqueue() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style(
		'dummy-product',
		get_stylesheet_uri(),
		array(),
		$version
	);
	wp_enqueue_style(
		'dummy-product-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Source+Sans+3:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_script(
		'dummy-product-theme',
		get_template_directory_uri() . '/assets/theme-toggle.js',
		array(),
		$version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'dummy_product_enqueue' );
