<?php
/**
 * Template Name: Faculty Home (Global Health)
 * 
 * @link: https://make.wordpress.org/core/2016/11/03/post-type-templates-in-4-7/
 */

// add js script file for the faculty page
add_action( 'wp_enqueue_scripts', array( 'DGH_WP_Theme', 'dgh_wp_theme_enqueue_page_faculty_scripts' ) );

get_header();
$sidebar = get_post_meta($post->ID, "sidebar");   ?>

<div class="container-fluid ">
<?php echo uw_breadcrumbs(); ?>

</div>
<div class="container-fluid uw-body">
	<div class="row">

		<main id="primary" class="site-main uw-body-copy col-md-<?php echo ( ( ! isset( $sidebar[0] ) || 'on' !== $sidebar[0] ) ? '8' : '12' ); ?>">
		
		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'page-faculty' );

		endwhile; // End of the loop.
		?>

		</main><!-- #primary -->

		<?php
		if ( ! isset( $sidebar[0] ) || 'on' !== $sidebar[0] ) {
			get_sidebar();
		}
		?>

	</div><!-- .row -->
</div><!-- .container -->

<?php

get_footer();
