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

		<p><?php esc_html_e( 'Sorry, but there is an issue with the Faculty Home template.', 'dgh-wp-theme' ); ?></p>
		<p><?php echo $args['reason'] ?></p>

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
					uw_wp_theme_edit_post_link();
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div>
</article><!-- #post-<?php //the_ID();  ?> -->
