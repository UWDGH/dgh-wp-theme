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

	<?php if ( $posts->have_posts() ) : ?>
		
		<?php

		// start shortcode string for output
		$shortcode = '[row height="equal" id="faculty-cards"]';
		?>
		<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>

			<?php
			global $post;
			setup_postdata($post);

			$class_single = esc_attr( $atts['class_single'] );
			$card_classes = 'su-post-faculty-card';
			
			$the_ID = get_the_ID();

			$fac_title = get_the_title( $the_ID );

			$fac_research_interests = get_post_meta( $post->ID, '_dgh_fac_research_interests', true );
			// do_action('qm/debug', empty($fac_research_interests) );
			$fac_expertise = '';
			if ( !empty($fac_research_interests) ){
				// $fac_research_interests = wp_trim_words( $fac_research_interests, 15, '&hellip;' );
				$fac_expertise = <<<FAC_EXPERTISE
				<p class="fac-expertise">
				<strong>Expertise: </strong>{$fac_research_interests}
				</p>
				FAC_EXPERTISE;
			}

			$uw_card_image = get_stylesheet_directory_uri() . '/assets/img/profile-placeholder.png';
			$modal_thumbnail = '';
			if ( has_post_thumbnail( $the_ID ) ) {
				$uw_card_image = get_the_post_thumbnail_url( $the_ID );
				$modal_thumbnail = get_the_post_thumbnail( $post, 'thumbnail', array( 'class' => 'alignleft' ) );
			}

			$fac_permalink = get_the_permalink( $the_ID );

			$fac_bio = apply_filters( 'the_content', get_the_content( $post ) );
			if ( !empty( $fac_research_interests ) ){
				$fac_bio .= '<h3>Areas of expertise</h3>'.$fac_research_interests;
			}
			$bio_modal = <<<FAC_BIO
			[uw_modal id="uw-modal-{$the_ID}" title="{$fac_title}" width="default" color="gold" button="bio" position="center" size="small"]
			{$modal_thumbnail}{$fac_bio}
			[/uw_modal] 
			FAC_BIO;


			// construct uw_card item
			$uw_card = <<<FACULTY_CARD
			[col id="su-post-{$the_ID}" class="col-12 col-lg-6 py-3 px-3 su-post {$card_classes} {$class_single}"]
			[uw_card 
			id="su-post-faculty-card-{$the_ID}" 
			style="half-block-large" 
			align="right" 
			color="white" 
			titletag="h3" 
			image="{$uw_card_image}" 
			alt="Profile photo of {$fac_title}" title="{$fac_title}" 
			button="Go to profile page" 
			link="{$fac_permalink}"
			stretched_link="true"]
			<strong>Professional Title, Global Health</strong><br>
			<strong>Second Professional Title, Other Department or School</strong>
			{$bio_modal}
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
