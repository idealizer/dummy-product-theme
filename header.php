<?php
/**
 * Header partial for page templates.
 *
 * @package dummy-product
 */

$config = dummy_product_config();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script>
	(function () {
		try {
			if ( localStorage.getItem( 'dummy-product-theme' ) === 'dark' ) {
				document.documentElement.classList.add( 'dark' );
			}
		} catch ( e ) {}
	}());
	</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<a class="brand notranslate" lang="en" translate="no" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<span class="brand__dummy">dummy</span><span class="brand__product"><?php echo esc_html( $config['label'] ); ?></span>
	</a>
	<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'dummy-product' ); ?>">
		<?php if ( ! empty( $config['suite'] ) && has_nav_menu( 'primary' ) ) : ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'site-nav__menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		<?php elseif ( empty( $config['suite'] ) ) : ?>
			<a href="<?php echo esc_url( home_url( '/docs/' ) ); ?>">Docs</a>
			·
			<a href="<?php echo esc_url( home_url( '/donate/' ) ); ?>">Donate</a>
			·
			<a href="<?php echo esc_url( home_url( '/ticket/' ) ); ?>">Ticket</a>
		<?php endif; ?>
	</nav>
	<button type="button" class="theme-toggle" id="dummy-theme-toggle" aria-pressed="false" aria-label="<?php esc_attr_e( 'Switch color theme', 'dummy-product' ); ?>">
		<svg class="theme-toggle__moon" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M21 14.5A8.5 8.5 0 0 1 9.5 3 7 7 0 1 0 21 14.5z"/></svg>
		<svg class="theme-toggle__sun" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><circle cx="12" cy="12" r="4" fill="currentColor"/><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
	</button>
</header>
<main>
