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
	<div class="site-header__inner">
	<a class="brand notranslate" lang="en" translate="no" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php if ( ! empty( $config['suite'] ) ) : ?>
			<img class="brand__logo" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/dummytools.jpg' ); ?>" width="40" height="40" alt="" />
		<?php endif; ?>
		<span class="brand__text">
			<span class="brand__dummy">dummy</span><span class="brand__product"><?php echo esc_html( $config['label'] ); ?></span>
		</span>
	</a>
	<button type="button" class="nav-toggle" id="dummy-nav-toggle" aria-expanded="false" aria-controls="site-nav">
		<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'dummy-product' ); ?></span>
		<span aria-hidden="true"></span>
	</button>
	<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'dummy-product' ); ?>">
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
		<?php elseif ( ! empty( $config['suite'] ) ) : ?>
			<a class="site-nav__home" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Home', 'dummy-product' ); ?>">
				<svg class="site-nav__home-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
			</a>
			<?php foreach ( dummy_product_suite_cards() as $card ) : ?>
				<a href="<?php echo esc_url( get_permalink( $card ) ); ?>"><?php echo esc_html( get_the_title( $card ) ); ?></a>
			<?php endforeach; ?>
			<a href="<?php echo esc_url( home_url( '/donate/' ) ); ?>">Donate</a>
			<a href="<?php echo esc_url( home_url( '/ticket/' ) ); ?>">Ticket</a>
		<?php else : ?>
			<a class="site-nav__home" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Home', 'dummy-product' ); ?>">
				<svg class="site-nav__home-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
			</a>
			<a href="<?php echo esc_url( home_url( '/docs/' ) ); ?>">Docs</a>
			<a href="<?php echo esc_url( home_url( '/donate/' ) ); ?>">Donate</a>
			<a href="<?php echo esc_url( home_url( '/ticket/' ) ); ?>">Ticket</a>
		<?php endif; ?>
	</nav>
	<button type="button" class="theme-toggle" id="dummy-theme-toggle" aria-pressed="false" aria-label="<?php esc_attr_e( 'Switch color theme', 'dummy-product' ); ?>">
		<svg class="theme-toggle__moon" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><path fill="currentColor" d="M21 14.5A8.5 8.5 0 0 1 9.5 3 7 7 0 1 0 21 14.5z"/></svg>
		<svg class="theme-toggle__sun" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true"><circle cx="12" cy="12" r="4" fill="currentColor"/><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
	</button>
	</div>
</header>
<main>
