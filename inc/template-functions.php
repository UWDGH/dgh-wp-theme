<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package uw_wp_theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */


if ( !function_exists( 'uw_header_template' ) ) :

	function uw_header_template( $type ) {

		global $post;

		//$version = $type == 'big' ? '' : '2';
		if ( 'big' == $type ) {
			$version = '';
		} else if ( 'jumbotron' == $type ) {
			$version = 'jumbo';
		} else {
			$version = '2';
		}

		$background_url = get_post_thumbnail_id( $post->ID ) ? wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) : get_template_directory_uri() . "/assets/headers/suzzallo.jpg";

		$mobileimage_url = get_post_meta( $post->ID, "mobileimage" );
		$hasmobileimage = '';

		if ( !empty( $mobileimage_url ) && $mobileimage_url[0] !== "" ) {
			$mobileimage = $mobileimage_url[0];
			$hasmobileimage = 'hero-mobile-image';
		}

		$banner = get_post_meta( $post->ID, 'banner' );
		$buttontext = get_post_meta( $post->ID, 'buttontext' );
		$buttonlink = get_post_meta( $post->ID, 'buttonlink' );
		$pagetitle = get_post_meta( $post->ID, 'pagetitle' );
		?>
		<div class="uw-hero-image <?php echo esc_attr( $hasmobileimage ); ?> hero-height<?php echo esc_attr( $version ); ?>" style="background-image: url( <?php echo esc_url( $background_url ); ?> );"<?php echo ('jumbotron' == $type) ? 'role="region" aria-label="page title and banner"' : 'role="presentation"' ?>>
			<?php if ( isset( $mobileimage ) ) { ?>
				<div class="mobile-image" style="background-image: url( <?php echo $mobileimage ?> );"></div>
			<?php } ?>

			<div class="container-fluid">
				<?php if( 'jumbotron' == $type ) { ?> <!-- this is the jumbotron style hero -->
					<?php $subhead = get_post_meta( $post->ID, 'subhead' ); ?>

					<?php if( !empty( $banner ) && $banner[0] ) { ?>
						<div id="banner"><span><span><?php echo $banner[0] ? $banner[0] : ''; ?></span></span></div>
					<?php } ?>
					<div class="row col-xs-12 jumbo">
						<div class="transparent-overlay">
							<div class="inner-overlay">
								<h1 class=" uw-site-title <?php echo $version ?>"><?php the_title(); ?></h1>
								<?php if( !empty( $subhead ) && $subhead[0] ) { ?>
									<p class="jumbo-subhead">
										<span class="udub-slant-divider gold" style=""><span></span></span>
										<?php echo $subhead[0] ? $subhead[0] : '';  ?>
									</p>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php if( !empty( $buttontext ) && $buttontext[0] ) { ?>
						<a class="btn btn-lg arrow white" href="<?php echo $buttonlink && $buttontext[0] ? $buttonlink[0] : ''; ?>"><span><?php echo $buttontext[0] ? $buttontext[0] : ''; ?></span><span class="arrow-box"><span class="arrow"></span></span></a>

					<?php } ?>
				<?php } else { ?> <!-- this is the other hero types -->
					<?php if( !empty( $banner ) && $banner[0] ) { ?>
						<div id="hashtag"><span><span><?php echo $banner[0] ? $banner[0] : ''; ?></span></span></div>
					<?php } ?>

					<?php if ( ! empty( $pagetitle ) && $pagetitle[0] ) { ?>
						<!-- do not show site title -->
					<?php } else { ?>
						<h1 class="uw-site-title<?php echo $version ?>"><?php the_title(); ?></h1>
						<?php if( !empty( $subhead ) && $subhead[0] ) {
							echo $subhead;
						}
						?>
						<span class="udub-slant"><span></span></span>
					<?php } ?>


					<?php if( !empty( $buttontext ) && $buttontext[0] ) { ?>
						<a class="btn btn-lg arrow white" href="<?php echo $buttonlink && $buttontext[0] ? $buttonlink[0] : ''; ?>"><span><?php echo $buttontext[0] ? $buttontext[0] : ''; ?></span><span class="arrow-box"><span class="arrow"></span></span></a>

					<?php } ?>

					<?php } ?>
			</div>
		</div>
		<?php if ( ! empty( $pagetitle ) && $pagetitle[0] ) { ?>
			<div role="region" aria-label="page title" class="container-fluid mt-3">
				<h1 class="uw-site-title<?php echo esc_attr( $version ); ?> below-hero"><?php the_title(); ?></h1>
			</div>
				<?php } else { ?>
					<!-- do nothing -->
				<?php } ?>
		<?php

	}

endif;

/**
 * override uw_meta_tags
 * @link https://ogp.me/
 */
if ( !function_exists( 'uw_meta_tags' ) ) :
	function uw_meta_tags() {
		
		if ( is_404() )
			return;
		
		global $post;
		setup_postdata( $post->ID );
		// do_action('qm/debug', $post );
		
		$og = array();
		//og:locale
		$og['og:locale'] = 'en_US';
		//og:site_name
		$og['og:site_name']  = get_bloginfo( 'name' );
		//og:type (required)
		$og['og:type'] = 'website';
		if ( $post->post_type == 'post' ) {
			$og['og:type'] = 'article';
			$og['article:published_time'] = $post->post_date;
			$og['article:modified_time'] = $post->post_modified;
		}
		if ( $post->post_type == 'dgh_faculty_profile' ) {
			$og['og:type'] = 'profile';
			$og['profile:first_name'] = get_post_meta( $post->ID, '_dgh_fac_fname', true );
			$og['profile:last_name '] = get_post_meta( $post->ID, '_dgh_fac_lname', true );
		}
		//og:title (required)
		$og['og:title'] = html_entity_decode( get_the_title( $post->ID ) );
		// og:image (required)
		$og['og:image'] = "https://s3-us-west-2.amazonaws.com/uw-s3-cdn/wp-content/uploads/sites/10/2019/06/21094817/Univ-of-Washington_Memorial-Way.jpg";
		$og['og:image:width"'] = 1200;
		$og['og:image:height'] = 630;
		$has_post_thumbnail = isset( $post->ID ) ? has_post_thumbnail( $post->ID ) : false;
		if ( $has_post_thumbnail ) {
			$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
			$og['og:image'] = esc_attr( $thumbnail_src[0] );
		} elseif ( !$has_post_thumbnail && $post->post_type == 'dgh_faculty_profile' ) {
			$photo_url = get_post_meta( $post->ID, '_dgh_fac_photo_url', true );
			if ( \DGH_Post_Types\DGH_Template::is_dgh_fac_photo_url_ok( $photo_url ) ) {
				$og['og:image'] = esc_attr( $photo_url );
			} else {
				$og['og:image'] = get_stylesheet_directory_uri() . '/assets/img/W_placeholder.jpg';
			}
		}
		// og:description
		$og_description = get_the_excerpt( $post->ID ); // if there's is no excerpt, this function will fallback on the post content
		if ( $og_description ) {
			$og_description = trim( str_replace( '&nbsp;', ' ', $og_description ) ); //convert non-breakable spaces to normal spaces to really trim it
			$og_description = trim( wp_strip_all_tags( strip_shortcodes( stripslashes( $og_description ), true ) ) );
			$og_description = uw_social_truncate( $og_description, 200 );
			$og['og:description'] = html_entity_decode( $og_description );
		}
		if ( empty($og_description) ) {	$og['og:description'] = get_bloginfo( 'description' ); } //fallback		
		if ( empty($og_description) ) { $og['og:description'] = __('Achieve sustainable, quality health globally.', 'dgh-wp-theme'); } //fallback		
		//og:url (required)
		$og['og:url'] = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		// do_action('qm/debug', $og );
		foreach ($og as $key => $value) {
			echo '<meta property="'.$key.'" content="'.$value.'" />' . PHP_EOL;
		}

	}
	add_action( 'wp_head', 'uw_meta_tags', 5 );
endif;
 
/**
 * override uw_breadcrumbs
 * add trail for custom post type dgh_faculty_profile
 */
if ( ! function_exists( 'uw_breadcrumbs' ) ) :

	function uw_breadcrumbs() {

		if ( get_option( 'breadcrumb-hide' ) ) :
			return;
		endif;

		global $post;

		if ( isset( $post ) && get_post_meta( $post->ID, 'breadcrumbs', true ) ) {
			return;
		}

		$ancestors = array_reverse( get_post_ancestors( $post ) );
		$html      = '<li><a href="' . home_url( '/' ) . '" title="' . get_bloginfo( 'title' ) . '">' . get_bloginfo( 'title' ) . '</a>';

		if ( is_404() ) {
			$html .= '<li class="current"><span>Woof!</span>';
		} elseif ( is_search() ) {
			$html .= '<li class="current"><span>Search results for ' . get_search_query() . '</span>';
		} elseif ( is_author() ) {
			$author = get_queried_object();
			$html  .= '<li class="current"><span> Author: ' . $author->display_name . '</span>';
		} elseif ( get_queried_object_id() === (int) get_option( 'page_for_posts' ) ) {
			$html .= '<li class="current"><span> ' . get_the_title( get_queried_object_id() ) . ' </span>';
		}

		// If the current view is a post type other than page or attachment then the breadcrumbs will be taxonomies.
		if ( is_category() || is_single() || is_post_type_archive() || is_tag() ) {

			if ( is_post_type_archive() ) {
				$posttype = get_post_type_object( get_post_type() );
				//$html .=  '<li class="current"><a href="'  . get_post_type_archive_link( $posttype->query_var ) .'" title="'. $posttype->labels->menu_name .'">'. $posttype->labels->menu_name  . '</a>';
				$html .= '<li class="current"><span>' . $posttype->labels->menu_name  . '</span>';
			}

			if ( is_category() ) {
				if ( 'post' === get_post_type() && get_option( 'page_for_posts', true ) ) {
					$html .= '<li><a href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';
				}

				$category = get_category( get_query_var( 'cat' ) );
				//$html .=  '<li class="current"><a href="'  . get_category_link( $category->term_id ) .'" title="'. get_cat_name( $category->term_id ).'">'. get_cat_name($category->term_id ) . '</a>';
				$html .= '<li class="current"><span>' . get_cat_name( $category->term_id ) . '</span>';
			}

			if ( is_tag() ) {
				if ( 'post' === get_post_type() && get_option( 'page_for_posts', true ) ) {
					$html .= '<li><a href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';
				}

				$tag   = get_tag( get_queried_object_id() );
				$html .= '<li class="current"><span>' . $tag->slug . '</span>';
			}

			if ( is_single() ) {
				if ( 'post' === get_post_type() && get_option( 'page_for_posts', true ) ) {
					$html .= '<li><a href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';
				} elseif ( has_category() ) {
					$thecat   = get_the_category( $post->ID );
					$category = array_shift( $thecat );
					$html    .= '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . get_cat_name( $category->term_id ) . ' ">' . get_cat_name( $category->term_id ) . '</a>';
				}
				// check if is Custom Post Type.
				if ( ! is_singular( array( 'page', 'attachment', 'post' ) ) ) {

					switch ( get_post_type() ) {
						case 'dgh_faculty_profile':
							$fac_page = DGH_WP_Theme::dgh_wp_theme_faculty_home_breadcrumb();
							if ( $fac_page ) {
								$html    .= '<li><a href="' . home_url( '/'.$fac_page['post_name'] ) . '" title="'.$fac_page['post_title'].'">'.$fac_page['post_title'].'</a>';
								break;
							} else {
								// fall through and use default...
							}
						default:
							$posttype = get_post_type_object( get_post_type() );
							$html    .= '<li><a href="' . home_url( '/' ) . '" title="' . get_bloginfo( 'title' ) . '">' . get_bloginfo( 'title' ) . '</a>';
							break;
					}

				}

				$html .= '<li class="current"><span>' . get_the_title( $post->ID ) . '</span>';
			}
		} elseif ( is_page() ) {
			// If the current view is a page then the breadcrumbs will be parent pages.

			if ( ! is_home() || ! is_front_page() ) {
				$ancestors[] = $post->ID;
			}

			if ( ! is_front_page() ) {
				foreach ( array_filter( $ancestors ) as $index => $ancestor ) {

					$class      = $index + 1 === count( $ancestors ) ? ' class="current" ' : '';
					$page       = get_post( $ancestor );
					$url        = get_permalink( $page->ID );
					$title_attr = esc_attr( $page->post_title );

					if ( ! empty( $class ) ) {
						$html .= "<li $class><span>{$page->post_title}</span></li>";
					} else {
						$html .= "<li><a href=\"$url\" title=\"{$title_attr}\">{$page->post_title}</a></li>";
					}
				}
			}
		}

		return "<nav class='uw-breadcrumbs' aria-label='breadcrumbs'><ul>$html</ul></nav>";
	}
endif;
