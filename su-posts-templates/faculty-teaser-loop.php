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

<div class="su-posts su-posts-teaser-loop su-posts-teaser-loop--dgh-faculty <?php echo esc_attr( $atts['class'] ); ?>">

	<?php if ( $posts->have_posts() ) : ?>
		<div class="grid row equal">
		<?php while ( $posts->have_posts() ) : ?>
			<?php global $post; ?>
			<?php $posts->the_post(); ?>

			<?php if ( ! su_current_user_can_read_post( get_the_ID() ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>

			<?php
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

			// faculty rank
			$terms = get_the_terms( $the_ID, 'dgh_faculty_rank' );
			$is_emeritus = false;
			foreach ($terms as $fac_rank) {
				if ( $fac_rank->slug === 'emeritus-faculty' ) {
					$is_emeritus = true;
					break;
				}
			}

			$fac_title = get_the_title( $the_ID );
			$fac_photo_hidden = false;
			$fac_photo_hidden = get_post_meta( $post->ID, '_dgh_fac_photo_hidden', true );
			$image_url = get_stylesheet_directory_uri() . '/assets/img/W_placeholder.jpg';
			$photo_url = get_post_meta( $post->ID, '_dgh_fac_photo_url', true );
			$alt_text = '';
			$thumbnail = <<<THUMBNAIL
			<img loading="lazy" width="150" height="150" src="{$image_url}" class="alignleft wp-post-image" alt="Placeholder image for {$fac_title}" decoding="async">
			THUMBNAIL;
			if ( ! $fac_photo_hidden ) {
				if ( has_post_thumbnail( $the_ID ) ) {
					$image_url = get_the_post_thumbnail_url( $the_ID );
					$att_id = attachment_url_to_postid( $image_url );
					$alt_text = wp_get_attachment_caption( $att_id );
					$thumbnail = get_the_post_thumbnail( $post, 'thumbnail', array( 'class' => 'alignleft' ) );
				} elseif ( $photo_url ) {
					// fallback: deeplink the SPH photo URL
					if ( \DGH_Post_Types\DGH_Template::is_dgh_fac_photo_url_ok( $photo_url ) ) {
						$thumbnail = <<<THUMBNAIL
						<img loading="lazy" width="150" height="150" src="{$photo_url}" class="alignleft wp-post-image" alt="Profile photo of {$fac_title}" decoding="async" style="max-height: 150px;object-fit: cover;">
						THUMBNAIL;
					}
				}
			}
			// appointments
			$fac_appt_block = '<div class="faculty-appts">';
			$fac_appt = '';
			$fac_appt_ttl_dept = get_post_meta( $post->ID, '_dgh_fac_appt_ttl_dept', true );
			$fac_appts = get_post_meta( $post->ID, '_dgh_fac_appts' );
			$fac_job_title = get_post_meta( $post->ID, '_dgh_fac_job_title' );
			if ( !empty($fac_appts[0]) && $fac_is_synced ) {
				$fac_appt = '<p>'.$fac_appts[0][0].'</p>';	// only get the first appt
			} elseif ( !empty($fac_job_title[0]) ) {
				$fac_appt = '<p>'.$fac_job_title[0][0].'</p>';	// only get the first job_title
			} elseif ( !empty($fac_appt_ttl_dept) ) {
				// fallback from _dgh_fac_appt_ttl_dept
				$fac_appt = '<p>'.$fac_appt_ttl_dept.'</p>';
			}
			$fac_appt_block .= $fac_appt;
			$fac_appt_block .= '</div>';
			//email
			$fac_email_hidden = false;
			$fac_email_hidden =  get_post_meta( $post->ID, '_dgh_fac_email_hidden', true );
			$fac_email = get_post_meta( $post->ID, '_dgh_fac_email', true );
			$p_fac_email = '';
			if ( !empty( $fac_email ) ) {
				$hide_dashicon = ($fac_email_hidden) ? ' hide' : '';
				$p_fac_email .= '<p class="fac-email" data-alt="Email"><span class="dashicons dashicons--fac dashicons-email'.$hide_dashicon.'"></span>';
				$p_fac_email .= ($fac_email_hidden) ? '<i class="screen-reader-text">' . __('email undisclosed','dgh-wp-theme') . '</i>' : '<a href="'.esc_url( 'mailto:' . $fac_email ).'">'.esc_html( $fac_email ).'</a>';
				$p_fac_email .= '</p>';
			}
			?>
			<div id="su-post-<?php the_ID(); ?>" class="su-post <?php echo esc_attr( $atts['class_single'] ); ?> col-12 col-xl-6 py-3 px-3">
				<?php //if ( has_post_thumbnail() ) : ?>
					<?php echo $thumbnail; ?>
				<?php //endif; ?>
				<h3 class=""><!--a href="<?php //the_permalink(); ?>"--><?php the_title(); ?><!--/a--></h3>
				<?php echo $fac_appt_block; ?>
				<?php echo ( $is_emeritus ) ? null : $p_fac_email ;	// don't show email for emeritus faculty ?>
			</div>

		<?php endwhile; ?>
		</div>
	<?php else : ?>

		<p class="su-posts-not-found"><?php esc_html_e( 'Posts not found', 'shortcodes-ultimate' ); ?></p>

	<?php endif; ?>

</div>
