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
		<div class="uw-hero-image <?php echo esc_attr( $hasmobileimage ); ?> hero-height<?php echo esc_attr( $version ); ?>" style="background-image: url( <?php echo esc_url( $background_url ); ?> );">
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
