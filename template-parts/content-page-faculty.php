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
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
	<?php
	if ( ( is_single() || is_home() ) && get_option( 'show_byline_on_posts' ) ) :

		if ( get_option( 'show_author_on_posts' ) && 'post' === get_post_type() ) {
			?>
			<div class="author-info">
			<?php if ( function_exists( 'coauthors' ) ) { coauthors(); } else { the_author(); } ?>
			<p class="author-desc"> <small><?php the_author_meta(); ?></small></p>
			</div>

			<?php
		}
	endif;
	?>

	<div class="entry-content">
		<?php
		do_action('qm/debug',  $_SERVER['REQUEST_URI'] );
		do_action('qm/debug',  get_permalink() );
		do_action('qm/debug',  home_url($_SERVER['REQUEST_URI']) );
		do_action('qm/debug',  $_GET['faculty_page'] );

		$current_faculty_page_number = 1;
		$previous_faculty_page_number = 0;
		$next_faculty_page_number = 2;

		$current_faculty_page_url = '';
		$previous_faculty_page_url = '';
		$next_faculty_page_url = '';

		if ( isset( $_GET['faculty_page'] ) ) {

		}

		$fac_list = <<<FAC_LIST
		[su_posts template="su-posts-templates/faculty-card-loop.php" posts_per_page="4" offset="2" post_type="dgh_faculty_profile" orderby="meta_value" meta_key="_dgh_fac_name1" order="asc"]
		FAC_LIST;

		echo do_shortcode( $fac_list );

		// the_content();

		// wp_link_pages(
		// 	array(
		// 		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'uw_wp_theme' ),
		// 		'after'  => '</div>',
		// 	)
		// );
		?>
		<nav class="navigation post-navigation" aria-label="Posts">
			<h2 class="screen-reader-text">Faculty page navigation</h2>
			<div class="nav-links">
				<?php if ( $previous_faculty_page_number > 0) : ?>
				<div class="nav-previous">
					<a href="#" rel="prev">
						<div class="prev-post-text-link">
							<div class="post-navigation-sub"><span class="prev-arrow"></span><span><strong>Previous</strong></span></div>
							<span class="post-navigation-title">Previous faculty page</span>
						</div>
					</a>
				</div>
				<?php endif; ?>
				<div class="nav-next">
					<a href="#" rel="next">
						<div class="next-post-text-link">
							<div class="post-navigation-sub"><span><strong>Next</strong></span><span class="next-arrow"></span></div>
							<span class="post-navigation-title">Next faculty page</span>
						</div>
					</a>
				</div>
			</div>
		</nav>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
				uw_wp_theme_edit_post_link();
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
