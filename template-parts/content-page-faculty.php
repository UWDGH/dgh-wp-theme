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
		// do_action('qm/debug',  $_SERVER['QUERY_STRING'] );
		// do_action('qm/debug',  $_SERVER['REQUEST_URI'] );
		// do_action('qm/debug',  home_url() );
		// do_action('qm/debug',  get_permalink() );
		// do_action('qm/debug',  home_url($_SERVER['REQUEST_URI']) );

		$fac = null;
		$fac_total = null;
		$fac = get_posts([
			'post_type' => 'dgh_faculty_profile',
			'post_status' => 'publish',
			'numberposts' => -1
		]);
		( is_array( $fac ) ) ? $fac_total = count($fac) : $fac_total = 0; 
		
		// do_action('qm/debug',  $fac );
		// do_action('qm/debug',  $fac_total );


		$current_faculty_page_number = 0;
		$previous_faculty_page_number = -1;
		$next_faculty_page_number = 1;
		$posts_per_page = 3;
		$offset = 0;
		
		// do_action('qm/debug',  round($fac_total / $posts_per_page) );
		// do_action('qm/debug',  round($fac_total % $posts_per_page) );
		
		$total_number_of_pages = round($fac_total / $posts_per_page);

		$current_faculty_page_url = home_url($_SERVER['REQUEST_URI']);
		$previous_faculty_page_url = get_permalink();
		$previous_faculty_page_url = add_query_arg( 'faculty_page', $previous_faculty_page_number, $previous_faculty_page_url);
		$next_faculty_page_url = get_permalink();
		$next_faculty_page_url = add_query_arg( 'faculty_page', $next_faculty_page_number, $next_faculty_page_url);

		if ( isset( $_GET['faculty_page'] ) ) {
			$current_faculty_page_number = $_GET['faculty_page'];
			// do_action('qm/debug', is_int( (int)$_GET['faculty_page'] ) );
			if ( !is_int( (int)$_GET['faculty_page'] ) ) {
				$current_faculty_page_number = 1;
			}
			
			$previous_faculty_page_number = $current_faculty_page_number - 1;
			$next_faculty_page_number = $current_faculty_page_number + 1;
			$offset = $current_faculty_page_number * $posts_per_page;

			$next_faculty_page_url = get_permalink();
			$next_faculty_page_url = add_query_arg( 'faculty_page', $next_faculty_page_number, $next_faculty_page_url);
			$previous_faculty_page_url = get_permalink();
			$previous_faculty_page_url = add_query_arg( 'faculty_page', $previous_faculty_page_number, $previous_faculty_page_url);
		}

		$fac_list = <<<FAC_LIST
		[su_posts template="su-posts-templates/faculty-card-loop.php" posts_per_page="{$posts_per_page}" offset="{$offset}" post_type="dgh_faculty_profile" orderby="meta_value" meta_key="_dgh_fac_name1" order="asc"]
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
		<nav class="navigation post-navigation dgh-faculty-navigation" aria-label="Faculty">
			<h2 class="screen-reader-text">Faculty page navigation</h2>
			<div class="nav-links">
				<?php if ( $previous_faculty_page_number >= 0) : ?>
				<div class="nav-previous">
					<a href="<?php echo $previous_faculty_page_url; ?>" rel="prev">
						<div class="prev-post-text-link">
							<div class="post-navigation-sub"><span class="prev-arrow"></span><span><strong>Previous</strong></span></div>
							<span class="post-navigation-title">Previous faculty page</span>
						</div>
					</a>
				</div>
				<?php endif; ?>
				<?php if ( $next_faculty_page_number < $total_number_of_pages) : ?>
				<div class="nav-next">
					<a href="<?php echo $next_faculty_page_url; ?>" rel="next">
						<div class="next-post-text-link">
							<div class="post-navigation-sub"><span><strong>Next</strong></span><span class="next-arrow"></span></div>
							<span class="post-navigation-title">Next faculty page</span>
						</div>
					</a>
				</div>
				<?php endif; ?>
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
