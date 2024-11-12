<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package uw_wp_theme
 */

get_header();

// get the image header.
get_template_part( 'template-parts/header', 'image' );

?>
<div class="container-fluid ">
<?php echo uw_breadcrumbs(); ?>

</div>
<div class="container-fluid uw-body">
	<div class="row">

		<main id="primary" class="site-main uw-body-copy col-md-12">
		<article id="404" class="hentry">
				<div class="woof" style="background: url( <?php echo get_template_directory_uri() . '/assets/images/404.jpg' ?>) center center no-repeat; height: 400px;"></div>

				<div class="row">

					<div class="col-md-10 align-self-center offset-md-1">
						<h1>Not what you were expecting?</h1>
						<p>Dubs tells us this page might not be what you had in mind when you set out on your journey through the <?php echo get_bloginfo('name'); ?> website. 
						We apologize for the inconvenience and are confident you can find what you are looking starting from the <a href="<?php echo home_url('/'); ?>" title="<?php echo get_bloginfo('name'); ?> Home" class="uw-wordmark" tabindex="-1" aria-hidden="true">home page</a>, or using the search form below.</p>
						
						<?php get_search_form(); ?>
						
					</div>

					
				</div>
			</article> 
		</main><!-- #primary -->

	</div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();
