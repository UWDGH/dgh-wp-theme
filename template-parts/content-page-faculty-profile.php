<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uw_wp_theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">

		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php
			// $featured_image = esc_url(get_stylesheet_directory_uri().'/img/profile-placeholder.jpg');
			if ( has_post_thumbnail( get_the_ID() ) ) {
				$url_full  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
				?>
				<div id="featured-image-<?php the_ID(); ?>" class="featured-image entry-thumbnail">
					<a href="<?php echo $url_full[0]; ?>" title="This link opens the full size photo of <?php echo get_the_title(); ?>.">
					<?php
					the_post_thumbnail( array(240,240), array('id' => 'wp-post-image-'.get_the_ID(), 'class' => 'alignright wp-post-image--faculty-profile wp-img' ) );
					?>
					</a>
				</div>
				<?php
			}
		?>
		<div class="entry-content-wrapper">
		<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'uw_wp_theme' ),
					'after'  => '</div>',
				)
			);
		?>
		</div>

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
					uw_wp_theme_edit_post_link();
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div>
</article><!-- #post-<?php //the_ID();  ?> -->
