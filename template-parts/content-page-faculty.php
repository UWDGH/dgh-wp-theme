<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uw_wp_theme
 */

?>

<span class="uw-spinner wedge"></span>
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

		// rank
		$default_rank = 'core-faculty';
		$current_rank = $default_rank;
		$rank_name = 'Core Faculty';
		// allowed ranks
		$fac_ranks = get_terms( 
			array(
					'taxonomy' => 'dgh_faculty_rank',
					'hide_empty' => true,
				) 
			);

		// set array of allowed ranks to later test GET request
		$allowed_ranks = array();
		foreach ($fac_ranks as $rank) {
			$allowed_ranks[] = $rank->slug;
		}
		// do_action('qm/debug',  $allowed_ranks);
		
		// move core-faculty to top (for button display order)
		foreach ($fac_ranks as $rank) {
			if ( $rank->slug === 'core-faculty') {
 				array_unshift($fac_ranks, $rank);
			}
		}
		$fac_ranks = array_unique($fac_ranks, SORT_REGULAR);
		// do_action( 'qm/debug', $fac_ranks );

		// defaults
		$current_faculty_page_index = 0;
		$previous_faculty_page_index = -1;
		$next_faculty_page_index = 1;
		$offset = 0;

		$default_posts_per_page = 10;
		$posts_per_page = $default_posts_per_page;

		// current url
		$current_faculty_page_url = home_url( $_SERVER['REQUEST_URI'] );
		// do_action('qm/debug', $current_faculty_page_url );
		// previous url
		$previous_faculty_page_url = add_query_arg( 'rank', $current_rank, get_permalink() );
		$previous_faculty_page_url = add_query_arg( 'page_index', $previous_faculty_page_index, $previous_faculty_page_url );
		$previous_faculty_page_url = add_query_arg( '_wpnonce', wp_create_nonce( 'dgh-fac-page-index-'.strval($previous_faculty_page_index) ), $previous_faculty_page_url  );
		// do_action('qm/debug', $previous_faculty_page_url );
		// next url
		$next_faculty_page_url = add_query_arg( 'rank', $current_rank, get_permalink() );
		$next_faculty_page_url = add_query_arg( 'page_index', $next_faculty_page_index, $next_faculty_page_url );
		$next_faculty_page_url = add_query_arg( '_wpnonce', wp_create_nonce( 'dgh-fac-page-index-'.strval($next_faculty_page_index) ), $next_faculty_page_url  );
		// do_action('qm/debug', $next_faculty_page_url );

		
		/**
		 * catch the GET postback and validate the querystring
		 */
		
		$get_request_passed = null;
		$query_args_passed = false;
		$allowed_query_args = array( '_wpnonce', 'page_index', 'rank' );
		// do_action('qm/debug', $_GET );
		// do_action('qm/debug', count($_GET) );

		// first check if the querystring has 3 args and if those are the args we expect
		if ( !empty( $_GET ) && ( count($_GET)==3 ) ) {
			$query_args_passed = true;
			foreach ($_GET as $arg => $value) {
				if ( !in_array( $arg, $allowed_query_args, true ) ) {
					$query_args_passed = false;
					break;
				}
			}
			// do_action('qm/debug', '$query_args_passed = '.json_encode($query_args_passed) );
		} else {
			$query_args_passed = false;
			// do_action('qm/debug', '$query_args_passed = '.json_encode($query_args_passed) );
		}

		// now we can start validating the args values
		if ( !empty( $_GET ) && $query_args_passed ) {
			// do_action('qm/debug', '$_GET["page_index"] = '.$_GET['page_index'] );
			// do_action('qm/debug', 'is_numeric = ' . json_encode(is_numeric(  $_GET['page_index'] )) );
			
			//do a single do-while loop to allow break in first if-statement
			do {

				// check the provided rank to allowed ranks
				if ( !in_array( $_GET['rank'], $allowed_ranks, true ) ) {
					$get_request_passed = false;
					break;
				}
				$get_request_passed = true;
				$current_rank = $_GET['rank'];
				
				if ( 'all' === strtolower( $_GET['page_index'] ) ) {

					$verify_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'dgh-fac-page-index-all' );	// return can be 1, 2 or false

					if  ( $verify_nonce  !== false )  {

						$posts_per_page = -1;
						$offset = 0;

						$get_request_passed = true;

					} else {

						// nonce verification failed
						// do_action('qm/debug', '$verify_nonce = '.json_encode($verify_nonce) );
						$get_request_passed = false;

					}

				} elseif ( is_numeric( $_GET['page_index'] ) ) {

					// grab the value
					$current_faculty_page_index = intval( $_GET['page_index'] );
					// do_action('qm/debug', '$current_faculty_page_index = '.$current_faculty_page_index );

					$verify_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'dgh-fac-page-index-'.strval($current_faculty_page_index) );

					if  ( $verify_nonce  !== false )  {

						// previous page
						$previous_faculty_page_index = $current_faculty_page_index - 1;
						// next page
						$next_faculty_page_index = $current_faculty_page_index + 1;
						// dynamic offset
						$offset = $current_faculty_page_index * $posts_per_page;
						// next page url
						$next_faculty_page_url = add_query_arg( 'rank', $current_rank, get_permalink() );
						$next_faculty_page_url = add_query_arg( 'page_index', $next_faculty_page_index, $next_faculty_page_url );
						$next_faculty_page_url = add_query_arg( '_wpnonce', wp_create_nonce( 'dgh-fac-page-index-'.strval($next_faculty_page_index) ), $next_faculty_page_url );
						// previous page link
						$previous_faculty_page_url = add_query_arg( 'rank', $current_rank, get_permalink() );
						$previous_faculty_page_url = add_query_arg( 'page_index', $previous_faculty_page_index, $previous_faculty_page_url );
						$previous_faculty_page_url = add_query_arg( '_wpnonce', wp_create_nonce( 'dgh-fac-page-index-'.strval($previous_faculty_page_index) ), $previous_faculty_page_url );

						$get_request_passed = true;
						
					} else {

						// nonce verification failed
						// do_action('qm/debug', '$verify_nonce = '.json_encode($verify_nonce) );
						$get_request_passed = null;
						// reset defaults
						$current_faculty_page_index = 0;
						$previous_faculty_page_index = -1;
						$next_faculty_page_index = 1;
						$offset = 0;

					}


				} else {

					// page_index is not 'all' or a number
					content_page_faculty_page_error_log();
					$get_request_passed = false;

				}

			} while (0);

				
		} elseif ( !empty( $_GET ) ) {

			// in theory, this condition should never be reached
			content_page_faculty_page_error_log();
			$get_request_passed = false;

		}

		// do_action('qm/debug', '$get_request_passed = '.json_encode($get_request_passed) );

		// retrieve faculty for faculty count
		$fac = null;
		$fac_total = null;
		// $fac = get_posts([
		// 	'post_type' => 'dgh_faculty_profile',
		// 	'post_status' => 'publish',
		// 	'numberposts' => -1
		// ]);
		$term = get_term_by( 'slug', $current_rank, 'dgh_faculty_rank', 'ARRAY_A' );
		//do_action('qm/debug', $term );
		$args = array(
			'post_type' => 'dgh_faculty_profile',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'tax_query' => array(
    			array(
					'taxonomy' => 'dgh_faculty_rank',
					'field' => 'term_id',
					'terms' => $term['term_id'],
					)
				)
			);
		$Fac = new WP_Query( $args );
		// do_action('qm/debug', count($Fac->get_posts()) );
		$fac = $Fac->get_posts();
		// total number of faculty
		( is_array( $fac ) ) ? $fac_total = count($fac) : $fac_total = 0;
		
		// total number of pages
		$total_number_of_pages = (int)ceil($fac_total / $default_posts_per_page);
		// do_action('qm/debug', $total_number_of_pages );
		
		// view all button label
		$view_all_label =  isset( $term['name'] ) ? 'View all '.$term['name'] : 'View All';

		// rank description
		$rank_description = isset( $term['description'] ) ? $term['description'] : '';
		
		the_content();

		?>
		<nav class="faculty-pagination" aria-labelledby="faculty-pagination">
			<h2 id="faculty-pagination" class="screen-reader-text"><?php _e( 'Faculty pagination', 'dgh-wp-theme' ); ?></h2>
			<div class="faculty-pagination-container">
				<div class="faculty-pagination-rank">
					<?php
					// loop the faculty ranks to prepare the rank buttons and the select options
					$rank_buttons = array();
					$select_options = array();
					$selected = 'selected';
					// rank buttons
					$btn_style = 'primary';
					$rank_button_index = ( $get_request_passed && $_GET['page_index'] == 'all' ) ? 'all' : 0 ;
					foreach ($fac_ranks as $rank ) {
						if ( !$get_request_passed ) {
							$btn_style = 'secondary';
							$selected = '';
						}
						if ( $get_request_passed || is_null($get_request_passed) ) {
							$btn_style = 'secondary';
							$selected = '';
						}
						if ( $rank->slug == $current_rank ) {
							$btn_style = 'primary'; 
							$selected = 'selected';
							$rank_name = $rank->name;
						}
						$faculty_rank_url = add_query_arg( 'rank', $rank->slug, get_permalink() );
						$faculty_rank_url = add_query_arg( 'page_index', $rank_button_index, $faculty_rank_url );
						$faculty_rank_url = add_query_arg( '_wpnonce', wp_create_nonce( 'dgh-fac-page-index-'.strval($rank_button_index) ), $faculty_rank_url );

						// fill the select_options array to iterate in the .faculty-pagination-rank-select element
						$select_options[] = '<option id="option-'.$rank->slug.'" value="'.esc_url($faculty_rank_url).'" '.$selected.'>'.$rank->name.'</option>';
						// fill the rank_buttons array to iterate the noscript element and the .faculty-pagination-rank-links element
						$rank_buttons[] = '[uw_button id="btn-faculty-page-'.$rank->slug.'" style="'.$btn_style.'" size="small" target="'.esc_url($faculty_rank_url).'"]<span class="screen-reader-text">'.__( 'Navigate to faculty page ', 'dgh-wp-theme' ).'</span>'.$rank->name.'[/uw_button]';						
					}
					?>
					<div class="faculty-pagination-rank-links">
						<?php
						// show these buttons on breakpoint > 1200
						foreach ($rank_buttons as $rank_button) {
							echo do_shortcode( $rank_button );
						}
						?>
					</div>
					<noscript>
						<?php
						// show these buttons if javascript is not enabled
						foreach ($rank_buttons as $rank_button) {
							echo do_shortcode( $rank_button );
						}
						?>
					</noscript>
					<form class="faculty-pagination-rank-select">
						<div class="form-group">
							<label for="select-rank" class="screen-reader-text">Select a faculty rank for the listing</label>
							<select id="select-rank" class="form-control" style="display:inline-block;">
								<?php
								// show the select options on breakpoint < 1200
								foreach ($select_options as $option) {
									echo $option;
								}
								?>
							</select>
						</div>
					</form>
				</div>
				<div class="faculty-pagination-index">
				<?php
				// view all button
				$view_all_url = add_query_arg( 'rank', $current_rank, get_permalink() );
				$view_all_url = add_query_arg( 'page_index', 'all', $view_all_url );
				$view_all_url = add_query_arg( '_wpnonce', wp_create_nonce( 'dgh-fac-page-index-all' ), $view_all_url );
				$btn_style = 'secondary';
				if ( isset( $_GET['page_index'] ) && 'all' === strtolower( $_GET['page_index'] ) && $get_request_passed ) { 
					$btn_style = 'primary'; 
				}
				echo do_shortcode( '[uw_button id="btn-faculty-view-all" style="'.$btn_style.'" size="small" target="'.esc_url($view_all_url).'"]'.__($view_all_label,'dgh-wp-theme').'[/uw_button]' );
				// page nummber buttons
				content_page_faculty_page_buttons( $total_number_of_pages, $current_rank, $current_faculty_page_index, $get_request_passed );
				?>
					<div role="status" aria-atomic="true" aria-labelledby="faculty-list-status" class="faculty-list-status">
						<h2 id="faculty-list-status" class="screen-reader-text"><?php _e( 'Faculty list status', 'dgh-wp-theme' ); ?></h2>
						<span>Faculty list, page <?php echo ( -1 == $posts_per_page ) ? ' 1 ' : ($current_faculty_page_index + 1) . ' of ' . $total_number_of_pages ; ?>. Displaying 
							<?php if ( -1 == $posts_per_page ) : ?>
								all <?php echo $fac_total; ?> faculty.
							<?php else: ?>
								<?php if ( ( $total_number_of_pages == ($current_faculty_page_index + 1) ) && ( 0 !== $fac_total % $posts_per_page ) ) : ?>
									<?php echo ( $fac_total % $posts_per_page ) . ' of ' . $fac_total ;?>
								<?php else: ?>
									<?php echo $posts_per_page . ' of ' . $fac_total ;?>
								<?php endif; ?>
								faculty.
							<?php endif; ?>
						</span>
					</div>
				</div>
			</div>
			<?php //if ( $current_rank === 'core-faculty' ) : ?>
			<?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
			<?php //endif; ?>
		</nav>
		<h2 id="faculty-listing" style="margin-top: 1em;"><?php echo esc_html( $rank_name ); ?></h2>
		<?php if ( $rank_description ) : ?>
			<p><small><?php _e( $rank_description, 'dgh-wp-theme' ); ?></small></p>
		<?php endif; ?>
		<section aria-labelledby="faculty-listing">
			<?php

			// construct the su_post shortcode that calls the loop template
			$fac_list = <<<FAC_LIST
			[su_posts template="su-posts-templates/faculty-card-loop.php" posts_per_page="{$posts_per_page}" offset="{$offset}" post_type="dgh_faculty_profile" orderby="meta_value" meta_key="_dgh_fac_name1" order="asc" taxonomy="dgh_faculty_rank" tax_term="{$current_rank}"]
			FAC_LIST;
			if ( $current_rank !== 'core-faculty' ) {
				$fac_list = <<<FAC_LIST
				[su_posts template="su-posts-templates/faculty-teaser-loop.php" posts_per_page="{$posts_per_page}" offset="{$offset}" post_type="dgh_faculty_profile" orderby="meta_value" meta_key="_dgh_fac_name1" order="asc" taxonomy="dgh_faculty_rank" tax_term="{$current_rank}"]
				FAC_LIST;
			}

			echo do_shortcode( $fac_list );

			// the_content();

			// wp_link_pages(
			// 	array(
			// 		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'uw_wp_theme' ),
			// 		'after'  => '</div>',
			// 	)
			// );
			?>
		</section>
		<nav class="navigation post-navigation faculty-navigation" aria-labelledby="faculty-navigation">
			<h2 id="faculty-navigation" class="screen-reader-text"><?php _e( 'Faculty page navigation', 'dgh-wp-theme' ); ?></h2>
			<div class="nav-links">
				<?php if ( $previous_faculty_page_index >= 0) : ?>
				<div class="nav-previous">
					<a href="<?php echo esc_url($previous_faculty_page_url); ?>" rel="prev">
						<div class="prev-post-text-link">
							<div class="post-navigation-sub"><span class="prev-arrow"></span><span><strong><?php _e( 'Page '.$previous_faculty_page_index + 1, 'dgh-wp-theme' ); ?></strong></span></div>
							<span class="post-navigation-title"><?php _e( 'Previous faculty page', 'dgh-wp-theme' ); ?></span>
						</div>
					</a>
				</div>
				<?php endif; ?>
				<?php if ( ( $next_faculty_page_index < $total_number_of_pages ) && ( $posts_per_page != -1 ) ) : ?>
				<div class="nav-next">
					<a href="<?php echo esc_url($next_faculty_page_url); ?>" rel="next">
						<div class="next-post-text-link">
							<div class="post-navigation-sub"><span><strong><?php _e( 'Page '.$next_faculty_page_index + 1, 'dgh-wp-theme' ); ?></strong></span><span class="next-arrow"></span></div>
							<span class="post-navigation-title"><?php _e( 'Next faculty page', 'dgh-wp-theme' ); ?></span>
						</div>
					</a>
				</div>
				<?php endif; ?>
			</div>
		</nav>
		<nav class="faculty-pagination faculty-pagination--centered" aria-label="Faculty pagination">
			<h2 class="screen-reader-text"><?php _e( 'Faculty pagination', 'dgh-wp-theme' ); ?></h2>
			<?php
			// page nummber buttons
			content_page_faculty_page_buttons( $total_number_of_pages, $current_rank, $current_faculty_page_index, $get_request_passed );
			?>
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

<?php

/**
 * helper function
 * print the page number buttons
 */
function content_page_faculty_page_buttons( $total_number_of_pages, $current_rank, $current_faculty_page_index, $get_request_passed) {
	for($i = 0; $i < $total_number_of_pages; $i++) {
		$btn_style = 'primary';
		if ( $i != $current_faculty_page_index ) { 
			$btn_style = 'secondary'; 
		}
		if ( isset( $_GET['page_index'] ) && 'all' == strtolower( $_GET['page_index'] ) ) { 
			$btn_style = 'secondary'; 
		}
		if ( !$get_request_passed && !is_null($get_request_passed) ) {
			$btn_style = 'secondary';
		}
		$faculty_page_url = add_query_arg( 'rank', $current_rank, get_permalink() );
		$faculty_page_url = add_query_arg( 'page_index', $i, $faculty_page_url );
		$faculty_page_url = add_query_arg( '_wpnonce', wp_create_nonce( 'dgh-fac-page-index-'.strval($i) ), $faculty_page_url );
		$display_number = $i + 1;
		echo do_shortcode( '[uw_button id="btn-faculty-page-'.$i.'-'.bin2hex(random_bytes(1)).'" style="'.$btn_style.'" size="small" target="'.esc_url($faculty_page_url).'"]<span class="screen-reader-text">'.__( 'Navigate to faculty page ', 'dgh-wp-theme' ).'</span>'.$display_number.'[/uw_button]' );
	}
}

/**
 * log helper
 */
function content_page_faculty_page_error_log() {
	global $pagename;
	$log_string = $pagename . " Query String injection" . "\t";
	foreach ($_REQUEST as $key => $value) {
		$log_string .= $key.'=>'.$value . "\t";
	}
	$log_string .= 'REMOTE_ADDR'.'=>'.$_SERVER['REMOTE_ADDR'] . "\t";
	// $log_string .= 'SERVER_ADDR'.'=>'.$_SERVER['SERVER_ADDR'] . "\t";
	// $log_string .= 'HTTP_COOKIE'.'=>'.$_SERVER['HTTP_COOKIE'] . "\t";
	// foreach ($_SERVER as $key => $value) {
	// 	$log_string .= $key.'=>'.$value . "\t";
	// }
	// $err_string = print_r( $log_string, true );
	do_action('qm/debug', $log_string );
	error_log($log_string);
}