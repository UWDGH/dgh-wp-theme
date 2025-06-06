<?php defined( 'ABSPATH' ) || exit; ?>

<?php
/**
 * READ BEFORE EDITING!
 *
 * Do not edit templates in the plugin folder, since all your changes will be
 * lost after the plugin update. Read the following article to learn how to
 * change this template or create a custom one:
 *
 * https://getshortcodes.com/docs/posts/#built-in-templates
 */
?>

<div class="su-posts su-posts-dgh-faculty-card <?php echo esc_attr( $atts['class'] ); ?>">
	<p id="faculty-cards-description" class="screen-reader-text"><?php _e( 'A listing of faculty, presented visually as cards. Showing a profile image on one side of the card, and their name, appointment title, email address, and a link to their faculty page on the other side of the card.', 'dgh-wp-theme' ); ?></p>

	<?php if ( $posts->have_posts() ) : ?>
		
		<?php

		// start shortcode string for output
		$shortcode = '[row height="equal" id="faculty-cards"]';
		?>
		<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>

			<?php
			global $post;
			// setup_postdata($post);

			$class_single = esc_attr( $atts['class_single'] );
			$card_classes = 'su-post-faculty-card';
			
			$the_ID = get_the_ID();
			
			/**
			 * set sync status
			 */
			$fac_is_synced = true;
			$fac_url_id = get_post_meta( $the_ID, '_dgh_fac_url_id', true );
			$fac_ignore_sync = get_post_meta( $the_ID, '_dgh_fac_ignore_sync', true );
			if ( empty( $fac_url_id ) || !empty( $fac_ignore_sync ) ) {
				$fac_is_synced = false;
			}

			$fac_title = get_the_title( $the_ID );
			// do_action('qm/debug', $fac_title );

			// appointments
			$fac_appt_block = '<div class="faculty-appts" style="font-family: Encode Sans Compressed, sans-serif; font-weight: 500;">';
			$fac_appt = '';
			$fac_appt_ttl_dept = get_post_meta( $post->ID, '_dgh_fac_appt_ttl_dept', true );
			$fac_appts = get_post_meta( $post->ID, '_dgh_fac_appts' );
			$fac_job_title = get_post_meta( $post->ID, '_dgh_fac_job_title' );
			if ( !empty($fac_appts[0]) && $fac_is_synced ) {
				$fac_appt = '<div>'.$fac_appts[0][0].'</div>';	// only get the first appt
			} elseif ( !empty($fac_job_title[0]) ) {
				$fac_appt = '<div>'.$fac_job_title[0][0].'</div>';	// only get the first job_title
			} elseif ( !empty($fac_appt_ttl_dept) ) {
				// fallback from _dgh_fac_appt_ttl_dept
				$fac_appt = '<div>'.$fac_appt_ttl_dept.'</div>';
			}
			$fac_appt_block .= $fac_appt;
			$fac_appt_block .= '</div>';

			$image_url = get_stylesheet_directory_uri() . '/assets/img/W_placeholder.jpg';
			$photo_url = get_post_meta( $post->ID, '_dgh_fac_photo_url', true );
			$alt_text = '';
			$modal_thumbnail = '';
			if ( has_post_thumbnail( $the_ID ) ) {
				$image_url = get_the_post_thumbnail_url( $the_ID );
				$att_id = attachment_url_to_postid( $image_url );
				$alt_text = wp_get_attachment_caption( $att_id );
				$modal_thumbnail = get_the_post_thumbnail( $post, 'thumbnail', array( 'class' => 'alignright' ) );
			} elseif ( $photo_url ) {
				// fallback: deeplink the SPH photo URL
				if ( \DGH_Post_Types\DGH_Template::is_dgh_fac_photo_url_ok( $photo_url ) ) {
					$image_url = $photo_url;
					$modal_thumbnail = <<<THUMBNAIL
					<img loading="lazy" width="150" height="150" src="{$image_url}" class="alignright wp-post-image" alt="Profile photo of {$fac_title}" decoding="async">
					THUMBNAIL;
				}
			}

			//permalink
			$fac_permalink = get_the_permalink( $the_ID );
			//bio
			$fac_bio = apply_filters( 'the_content', get_the_content( $post ) );

			// create preview modal if there's bio content
			$preview_modal = '';
			if ( $fac_bio ) {
				// get an excerpt of the content for the preview modal.
				// note! the post_excerpt itself should always be empty since it's not supported in the dgh_faculty_profile post type
				// and WP should automatically fall back on generating an excerpt from the post_content
				add_filter( 'excerpt_length', function($number) {
					global $post;
					// get the excerpt length based on number of words of the first paragraph
					$fac_bio = apply_filters( 'the_content', get_the_content( $post->post_content ) );
					libxml_use_internal_errors(true);
					$bio_dom = new \DOMDocument();
					$bio_dom->loadHTML($fac_bio);
					foreach (libxml_get_errors() as $error) {
						// handle DOMDocument errors here
						error_log( \DGH_Post_Types\DGH_Template::DOMDocumentError( $error ) );
					}
					$excerpt_len = str_word_count( rtrim( strtok( $bio_dom->textContent, "\n" ) ), 0, '[0...9()]' );
					$the_post_template = get_post_meta( $post->ID, '_wp_page_template', true );
					if ( 'templates/template-faculty-profile.php' == $the_post_template ) {
						$number = $excerpt_len;
					}
					libxml_clear_errors();
					return $number;
				}, 999 );
				add_filter( 'excerpt_more', function($more_string) {
					global $post;
					$the_post_template = get_post_meta( $post->ID, '_wp_page_template', true );
					if ( 'templates/template-faculty-profile.php' == $the_post_template ) {
						return ' [&hellip;]';
					}
					return $more_string;
				});
				$fac_bio_excerpt = apply_filters( 'the_excerpt', get_the_excerpt( $post ) );

				//research interest
				$fac_research_interests = get_post_meta( $post->ID, '_dgh_fac_research_interests', true );

				// start create modal content
				// todo: decide if to use and what content to add
				$modal_content = $fac_bio_excerpt;
				if ( !empty( $fac_research_interests ) ){
					// $modal_content .= '<hr><h3>Areas of expertise</h3>'.$fac_research_interests;
				}
				//TODO maybe: include 'health topics/research areas' vocabulary terms to modal
				
				// create modal shortcode
				$preview_modal_btn = __('preview','dgh-wp-theme');
				$preview_modal = <<<UW_MODAL
				[uw_modal id="uw-modal-{$the_ID}" title="{$fac_title}" width="default" color="gold" button="{$preview_modal_btn}" position="center" size="small"]
				{$modal_thumbnail}{$modal_content}
				[/uw_modal] 
				UW_MODAL;
			}

			//email
			$fac_email_hidden = false;
			$fac_email_hidden =  get_post_meta( $post->ID, '_dgh_fac_email_hidden', true );
			$fac_email = get_post_meta( $post->ID, '_dgh_fac_email', true );
			$p_fac_email = '';
			if ( !empty( $fac_email ) ) {
				$p_fac_email .= '<div class="fac-email" data-alt="Email">';
				$p_fac_email .= ($fac_email_hidden) ? '<i>' . __('undisclosed','dgh-wp-theme') . '</i>' : '<a href="'.esc_url( 'mailto:' . $fac_email ).'">'.esc_html( $fac_email ).'</a>';
				$p_fac_email .= '</div>';
			}

			// construct uw_card item
			$fac_fname = get_post_meta( $post->ID, '_dgh_fac_fname', true );
			$uw_card_btn = __('Go to profile page','dgh-wp-theme');
			// if ( $fac_fname ) {
			// 	// extract actual first name from $fac_fname
			// 	$fname = explode(" ", $fac_fname);
			// 	$p_fname = $fname[0];
			// 	if ( (count($fname) > 1) && (strlen( $fname[0] ) == 1 ) ) {
			// 		$p_fname = $fname[0] . ' ' . $fname[1];
			// 	} elseif ( (count($fname) > 1) && (strlen( $fname[0] ) == 2 ) && (strpos($fname[0], ".") == 1) ) {
			// 		$p_fname = $fname[0] . ' ' . $fname[1];
			// 	}
			// 	$uw_card_btn = __('Go to '.$p_fname.'\'s profile page','dgh-wp-theme');
			// }
			$uw_card = <<<FACULTY_CARD
			[col id="su-post-{$the_ID}" class="col-12 col-lg-6 py-3 px-3 su-post {$card_classes} {$class_single}"]
			[uw_card id="su-post-faculty-card-{$the_ID}" style="half-block-large" align="right" color="white" titletag="h3" image="{$image_url}" alt="{$alt_text}" title="{$fac_title}" button="{$uw_card_btn}" link="{$fac_permalink}"]
			{$fac_appt_block}
			{$preview_modal}
			{$p_fac_email}
			[/uw_card]
			[/col]
			FACULTY_CARD;
			$shortcode .= $uw_card;
			?>

		<?php endwhile; ?>

		<?php
		// echo output
		$shortcode .= '[/row]';
		echo do_shortcode( $shortcode, false );
		?>

	<?php else : ?>
		<p><samp><?php _e( 'Posts not found', 'dgh-wp-theme' ); ?></samp></p>
	<?php endif; ?>

</div>
