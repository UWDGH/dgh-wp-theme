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

	<div class="entry-content entry-content-faculty">
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
		// total number of faculty
		( is_array( $fac ) ) ? $fac_total = count($fac) : $fac_total = 0; 
		
		// defaults
		$current_faculty_page_number = 0;
		$previous_faculty_page_number = -1;
		$next_faculty_page_number = 1;
		$offset = 0;
		$posts_per_page = 4;
		
		// total number of pages
		$total_number_of_pages = (int)ceil($fac_total / $posts_per_page);

		// current url
		$current_faculty_page_url = home_url( $_SERVER['REQUEST_URI'] );
		// previous url
		$previous_faculty_page_url = add_query_arg( 'faculty_page', $previous_faculty_page_number, get_permalink() );
		// next url
		$next_faculty_page_url = add_query_arg( 'faculty_page', $next_faculty_page_number, get_permalink() );

		// do we have a querystring?
		if ( isset( $_GET['faculty_page'] ) ) {
			if ( 'all' != strtolower( $_GET['faculty_page'] ) ) {
				// grab the value
				$current_faculty_page_number = $_GET['faculty_page'];
				// if not an int, reset to default
				if ( !is_int( (int)$_GET['faculty_page'] ) ) {
					$current_faculty_page_number = 0;
				}
				// previous page number
				$previous_faculty_page_number = $current_faculty_page_number - 1;
				// next page number
				$next_faculty_page_number = $current_faculty_page_number + 1;
				// dynamic offset
				$offset = $current_faculty_page_number * $posts_per_page;
				// next page url
				$next_faculty_page_url = add_query_arg( 'faculty_page', $next_faculty_page_number, get_permalink() );
				// previous page link
				$previous_faculty_page_url = add_query_arg( 'faculty_page', $previous_faculty_page_number, get_permalink() );
			} else {
				$posts_per_page = -1;
				$offset = 0;
			}
		}
		//todo: add count/total display
		?>
		<nav class="faculty-pagination" aria-label="Pagination">
		<h2 class="screen-reader-text"><?php _e( 'Faculty pagination', 'dgh-wp-theme' ); ?></h2>
		<?php
		// view all button
		$view_all_url = add_query_arg( 'faculty_page', 'all', get_permalink() );
		$btn_style = 'secondary';
		if ( isset( $_GET['faculty_page'] ) && 'all' == strtolower( $_GET['faculty_page'] ) ) { 
			$btn_style = 'primary'; 
		}
		echo do_shortcode( '[uw_button id="btn-faculty-view-all" style="'.$btn_style.'" size="small" target="'.esc_url($view_all_url).'"]'.__('View all','dgh-wp-theme').'[/uw_button]' );
		// page nummber buttons
		for($i = 0; $i < $total_number_of_pages; $i++) {
			$btn_style = 'primary';
			if ( $i != $current_faculty_page_number ) { 
				$btn_style = 'secondary'; 
			}
			if ( isset( $_GET['faculty_page'] ) && 'all' == strtolower( $_GET['faculty_page'] ) ) { 
				$btn_style = 'secondary'; 
			}
			$faculty_page_url = add_query_arg( 'faculty_page', $i, get_permalink() );
			$display_number = $i + 1;
			echo do_shortcode( '[uw_button id="btn-faculty-page-'.$i.'" style="'.$btn_style.'" size="small" target="'.esc_url($faculty_page_url).'"]'.$display_number.'[/uw_button]' );
		}
		?>
		</nav>
		<?php

		// construct the su_post shortcode that calls the loop template
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
		<nav class="navigation post-navigation faculty-navigation" aria-label="Pagination">
			<h2 class="screen-reader-text"><?php _e( 'Faculty page navigation', 'dgh-wp-theme' ); ?></h2>
			<div class="nav-links">
				<?php if ( $previous_faculty_page_number >= 0) : ?>
				<div class="nav-previous">
					<a href="<?php echo esc_url($previous_faculty_page_url); ?>" rel="prev">
						<div class="prev-post-text-link">
							<div class="post-navigation-sub"><span class="prev-arrow"></span><span><strong><?php _e( 'Page '.$previous_faculty_page_number + 1, 'dgh-wp-theme' ); ?></strong></span></div>
							<span class="post-navigation-title"><?php _e( 'Previous faculty page', 'dgh-wp-theme' ); ?></span>
						</div>
					</a>
				</div>
				<?php endif; ?>
				<?php if ( ( $next_faculty_page_number < $total_number_of_pages ) && ( $posts_per_page != -1 ) ) : ?>
				<div class="nav-next">
					<a href="<?php echo esc_url($next_faculty_page_url); ?>" rel="next">
						<div class="next-post-text-link">
							<div class="post-navigation-sub"><span><strong><?php _e( 'Page '.$next_faculty_page_number + 1, 'dgh-wp-theme' ); ?></strong></span><span class="next-arrow"></span></div>
							<span class="post-navigation-title"><?php _e( 'Next faculty page', 'dgh-wp-theme' ); ?></span>
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
