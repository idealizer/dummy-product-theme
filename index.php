<?php
/**
 * Fallback loop. The front page lives in front-page.php.
 *
 * @package dummy-product
 */

get_header();
?>
<div class="panel">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_title( '<h1>', '</h1>' );
			the_content();
		}
	}
	?>
</div>
<?php
get_footer();
