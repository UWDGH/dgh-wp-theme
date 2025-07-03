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

			if ( ! is_plugin_active( 'dgh-post-types/dgh-post-types.php' ) ) {
				get_template_part( 'template-parts/content', 'page-faculty-error', array(
					'reason' => __('Plugin "Global Health Website Custom Post Types" is not active.', 'dgh-wp-theme'),
				));
			} elseif ( ! post_type_exists( 'dgh_faculty_profile' ) ) {
				get_template_part( 'template-parts/content', 'page-faculty-error', array(
					'reason' => __('Post type "dgh_faculty_profile" does not exist.', 'dgh-wp-theme'),
				));
			} elseif( ! taxonomy_exists( 'dgh_faculty_rank' )  ) {
				get_template_part( 'template-parts/content', 'page-faculty-error', array(
					'reason' => __('Taxonomy "dgh_faculty_rank" does not exist.', 'dgh-wp-theme'),
				));
			} else { 
				get_template_part( 'template-parts/content', 'page-faculty' );
			}

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
