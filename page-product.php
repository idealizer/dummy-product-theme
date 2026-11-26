<?php
/**
 * Template Name: Product
 * Landing for one suite tool. Title and body are the page.
 *
 * @package dummy-product
 */

get_header();
?>
<article class="hero product-landing">
	<?php
	while ( have_posts() ) {
		the_post();
		$badge = get_post_meta( get_the_ID(), '_dummy_suite_badge', true );
		$state = get_post_meta( get_the_ID(), '_dummy_suite_state', true );
		$badge = is_string( $badge ) ? trim( $badge ) : '';
		$pill  = $state === 'live' ? 'pill pill--live' : 'pill pill--soon';
		$icon = dummy_product_suite_icon_url( get_post() );
		if ( $icon !== '' ) {
			echo '<img class="product-landing__icon" src="' . esc_url( $icon ) . '" alt="" width="72" height="72" />';
		}
		echo '<h1 class="brand-title notranslate" lang="en" translate="no">' . dummy_product_brand_markup( get_the_title() ) . '</h1>';
		if ( $badge !== '' ) {
			echo '<p><span class="' . esc_attr( $pill ) . '">' . esc_html( $badge ) . '</span></p>';
		}
		echo '<div class="entry-content">';
		the_content();
		echo '</div>';
	}
	?>
</article>
<?php
get_footer();
