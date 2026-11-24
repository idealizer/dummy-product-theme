<?php
/**
 * Front page. Suite copy comes from the static page. Other hosts keep the product hero.
 *
 * @package dummy-product
 */

$config = dummy_product_config();
get_header();

if ( empty( $config['suite'] ) ) :
	?>
	<section class="hero">
		<div class="brand notranslate" lang="en" translate="no" style="font-size:2rem;margin-bottom:1rem">
			<span class="brand__dummy">dummy</span><span class="brand__product"><?php echo esc_html( $config['label'] ); ?></span>
		</div>
		<h1><?php echo esc_html( $config['tagline'] ); ?></h1>
		<p>Free forever. No upsell. Built by Content Factory OÜ — Tornimäe tn 5, 10145 Tallinn, Estonia.</p>
		<a class="cta" href="<?php echo esc_url( home_url( '/docs/' ) ); ?>">Get started</a>
		<a class="cta cta--ghost" href="<?php echo esc_url( home_url( '/donate/' ) ); ?>">Donate</a>
	</section>
	<?php
else :
	?>
	<section class="hero hero--suite">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_title( '<h1 class="notranslate" lang="en" translate="no">', '</h1>' );
				echo '<div class="entry-content">';
				the_content();
				echo '</div>';
			}
		}
		?>
	</section>
	<section class="suite-wrap" aria-label="<?php esc_attr_e( 'Tools', 'dummy-product' ); ?>">
		<div class="suite-grid">
			<?php
			foreach ( dummy_product_suite_cards() as $card ) {
				$badge = get_post_meta( $card->ID, '_dummy_suite_badge', true );
				$state = get_post_meta( $card->ID, '_dummy_suite_state', true );
				$badge = is_string( $badge ) ? trim( $badge ) : '';
				$pill  = $state === 'live' ? 'pill pill--live' : 'pill pill--soon';
				$blurb = $card->post_excerpt !== '' ? $card->post_excerpt : wp_trim_words( wp_strip_all_tags( $card->post_content ), 24 );
				?>
				<a class="suite-card" href="<?php echo esc_url( get_permalink( $card ) ); ?>">
					<span class="suite-card__title notranslate" lang="en" translate="no"><?php echo esc_html( get_the_title( $card ) ); ?></span>
					<?php if ( $badge !== '' ) : ?>
						<span class="<?php echo esc_attr( $pill ); ?>"><?php echo esc_html( $badge ); ?></span>
					<?php endif; ?>
					<?php if ( $blurb !== '' ) : ?>
						<span class="suite-card__blurb"><?php echo esc_html( $blurb ); ?></span>
					<?php endif; ?>
				</a>
				<?php
			}
			?>
		</div>
	</section>
	<?php
endif;

get_footer();
