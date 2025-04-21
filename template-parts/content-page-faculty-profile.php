<?php
/**
 * Template part for displaying page content in template-faculty-profile.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uw_wp_theme
 */

?>

<?php
/**
 * variable declarations
 */
$fac_is_synced = true;		// sync status
// post and meta variables
$id = '';					// Post id
$title = '';				// Post title
$fac_url_id = '';			// SPH URL ID
$fac_ignore_sync = '';		// ignore sync
$fac_bio = '';				// Post content (i.e. Faculty Bio)
$fac_image_url = '';		// feaured image
$fac_photo_url = '';		// the SPH photo URL. Only us as fallback
$fac_image_alt_text = '';	// alt text (appears unused in UW's 'large' card)
$fac_appts = array();		// UW appointments
$fac_job_title = array();	// Additional job title(s)
$fac_fname = '';			// first name
$fac_lname = '';			// last name
$fac_appt_ttl_dept = '';	// that one strange appointment title field. we can use it as a fallback
$fac_phone_number = '';		// phone number
$fac_phone_number_hidden = false;
$fac_office = '';			// office location
$fac_office_hidden = false;
$fac_degrees = array();		// Degrees
$fac_publications = '';		// Publications
$fac_image_url = get_stylesheet_directory_uri() . '/assets/img/profile-placeholder.png';

// output(i.e. print) vars are prefixed with 'p'
$p_fac_bio = '';
$p_fac_appts = '';
$p_fac_research_interests = '';
$p_fac_degrees = '';
$p_fac_contact = '';
$p_fac_email = '';
$p_fac_phone_number = '';
$p_fac_office = '';
$p_fac_publications = '';

// content headings vars are prefixed with 'h'
$h_bio = '<h2>'.__('Bio', 'dgh-wp-theme').'</h2>';
$h_degrees = '<h2>'.__('Academic Degrees', 'dgh-wp-theme').'</h2>';
$h_contact = '<h2>'.__('Contact Information', 'dgh-wp-theme').'</h2>';
$h_research_interests = '<h2>'.__('Areas of Expertise', 'dgh-wp-theme').'</h2>';
$h_publications = '<h2>'.__('Publications', 'dgh-wp-theme').'</h2>';

/**
 * retrieve post data
 */
$id = $post->ID;
$title = get_the_title();
$fac_bio = apply_filters( 'the_content', get_the_content() );
$fac_research_interests = get_post_meta( $id, '_dgh_fac_research_interests', true );
$fac_fname = get_post_meta( $id, '_dgh_fac_fname', true );
$fac_lname = get_post_meta( $id, '_dgh_fac_lname', true );
$fac_email =  get_post_meta( $id, '_dgh_fac_email', true );
$fac_phone_number =  get_post_meta( $id, '_dgh_fac_phone_number', true );
$fac_phone_number_hidden =  get_post_meta( $id, '_dgh_fac_phone_number_hidden', true );
$fac_office =  get_post_meta( $id, '_dgh_fac_office', true );
$fac_office_hidden =  get_post_meta( $id, '_dgh_fac_office_hidden', true );
$fac_appt_ttl_dept = get_post_meta( $id, '_dgh_fac_appt_ttl_dept', true );
// degrees
$fac_degrees =  get_post_meta( $id, '_dgh_fac_degrees' );
if ( !empty( $fac_degrees ) ) { $fac_degrees = $fac_degrees[0]; }
// appts
$fac_appts =  get_post_meta( $id, '_dgh_fac_appts' );
if ( !empty( $fac_appts ) ) { $fac_appts = $fac_appts[0]; }
// job titles
$fac_job_title =  get_post_meta( $id, '_dgh_fac_job_title' );
if ( !empty( $fac_job_title ) ) { $fac_job_title = $fac_job_title[0]; }
// featured image
// SPH photo url
$fac_photo_url = get_post_meta( $post->ID, '_dgh_fac_photo_url', true );
if ( has_post_thumbnail( $id ) ) {
	$fac_image_url = get_the_post_thumbnail_url( $id );
	$att_id = attachment_url_to_postid( $fac_image_url );
	$fac_image_alt_text = wp_get_attachment_caption( $att_id );
} elseif ( $fac_photo_url ) {
	// fallback: deeplink the SPH photo URL
	if ( DGH_Template::is_dgh_fac_photo_url_ok( $fac_photo_url ) ) {
		$fac_image_url = $fac_photo_url;
	}
}
// publications
$fac_publications = get_post_meta( $id, '_dgh_fac_publications', true );

/**
 * set sync status
 */
$fac_url_id = get_post_meta( $id, '_dgh_fac_url_id', true );
$fac_ignore_sync = get_post_meta( $id, '_dgh_fac_ignore_sync', true );
if ( empty( $fac_url_id ) || !empty( $fac_ignore_sync ) ) {
	$fac_is_synced = false;
}

/**
 * construct output vars
 */
// construct appointments
if ( !empty( $fac_appts) || !empty( $fac_job_title)  ) {
	$p_fac_appts .= '<div class="fac-appts">';
	if ( !empty( $fac_appts) && $fac_is_synced ) {
		foreach ($fac_appts as $key => $value) {
			$p_fac_appts .= '<div role="note" aria-label="'.__('University of Washington Appointment','dgh-wp-theme').'">' . $value . '</div>';
		}
	}
	if ( !empty( $fac_job_title) ) {
		foreach ($fac_job_title as $key => $value) {
			$p_fac_appts .= '<div role="note" aria-label="'.__('Job Title','dgh-wp-theme').'">' . $value . '</div>';
		}
	} elseif ( empty( $fac_job_title) && !empty($fac_appt_ttl_dept) && !$fac_is_synced ) {
		$p_fac_appts .= '<div role="note" aria-label="'.__('Department Appointment Title','dgh-wp-theme').'">' . $fac_appt_ttl_dept . '</div>';
	}
	$p_fac_appts .= '</div>';
} 

// construct email
if ( $fac_email ) {
	$p_fac_email = '<div class="fac-email" data-alt="Email">';
	$p_fac_email .= '<span class="dashicons dashicons--fac dashicons-email"><span class="screen-reader-text">'.__('Email','dgh-wp-theme').'</span></span>';
	$p_fac_email .= '<a href="'.esc_url( 'mailto:' . $fac_email ).'">'.esc_html( $fac_email ).'</a>';
	$p_fac_email .= '</div>';
}

// construct phone number
if ( $fac_phone_number ) {
	$p_fac_phone_number = '<div class="fac-phone" data-alt="Phone number">';
	$p_fac_phone_number .= '<span class="dashicons dashicons--fac dashicons-phone"><span class="screen-reader-text">'.__('Phone number','dgh-wp-theme').'</span></span>';
	$p_fac_phone_number .= ($fac_phone_number_hidden) ? __('N/A','dgh-wp-theme') : $fac_phone_number;
	$p_fac_phone_number .= '</div>';
}

// construct office location
if ( $fac_office ) {
	$p_fac_office = '<div class="fac-office" data-alt="Office location">';
	$p_fac_office .= '<span class="dashicons dashicons--fac dashicons-location"><span class="screen-reader-text">'.__('Office location','dgh-wp-theme').'</span></span>';
	$p_fac_office .= ($fac_office_hidden) ? __('N/A','dgh-wp-theme') : wpautop( $fac_office );
	$p_fac_office .= '</div>';
}

// construct contact
if ( empty($fac_office) && empty($fac_email) && empty($fac_phone_number) ) {
	$p_fac_contact = $h_contact . 'N/A';
} else {
	$p_fac_contact = $h_contact . $p_fac_office . $p_fac_phone_number . $p_fac_email;
}

// construct bio
$p_fac_bio = ( !empty( $fac_bio ) ) ? $h_bio . $fac_bio : $h_bio . 'N/A';

// construct areas of expertise
$p_fac_research_interests = ( !empty( $fac_research_interests ) ) ? $h_research_interests . wpautop( $fac_research_interests ) : $h_research_interests . 'N/A';

// construct degrees
$p_fac_degrees .= $h_degrees;
if ( !empty( $fac_degrees) ) {
	$p_fac_degrees .= '<ul class="fac-degrees">';
	foreach ($fac_degrees as $key => $value) {
		$p_fac_degrees .= '<li role="listitem" aria-label="'.__('Academic Degree','dgh-wp-theme').'"><span class="dashicons dashicons--fac dashicons-welcome-learn-more"><span class="screen-reader-text">'.__('Academic Degree: ','dgh-wp-theme').'</span></span>' . $value . '</li>';
	}
	$p_fac_degrees .= '</ul>';
}

// construct publications
$p_fac_publications = ( !empty( $fac_publications ) ) ? $h_publications . wpautop( $fac_publications ) : $h_publications . 'N/A';


?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content entry-content-faculty-profile">

		<h1 class="entry-title screen-reader-text" aria-hidden="true"><?php the_title(); ?></h1> <!--//hidden from accessibility API. Title is duplicated in the card title//-->

		<div class="entry-content-wrapper">

			<section aria-label="<?php _e( 'Faculty profile header', 'dgh-wp-theme' ); ?>" aria-describedby="faculty-profile-header-description">
				
				<p id="faculty-profile-header-description" class="screen-reader-text"><?php _e( 'Above the fold content, presented visually as a card. Showing a faculty profile image on one side of the card, and their name, appointment titles, and email address on the other side of the card.', 'dgh-wp-theme' ); ?></p>
			
				<?php
				// construct the card shortcode with appointments and email as the body
				$uw_card = <<<UW_CARD
				[uw_card style="large" align="left" color="gold" title="{$title}" titletag="h1" image="{$fac_image_url}" alt="{$fac_image_alt_text}"]
				<div class="udub-slant-divider"><span></span></div>
				{$p_fac_appts}
				{$p_fac_email}
				[/uw_card]
				UW_CARD;
			
				// display the card
				echo do_shortcode( $uw_card ); 

				?>
			</section>
			<?php
			// the_content();

			// construct the 'About' tabs section
			$tabs_section_about_txt = __('About', 'dgh-wp-theme');
			$tabs_section_about = <<<TAB_ABOUT
			[tabs_section title="{$tabs_section_about_txt} {$fac_fname} {$fac_lname}" id="tab-fac-about"]
			[row]
			[col class="col-sm col-sm-12 col-xl-9 pl-xl-auto pr-xl-5"]
			{$p_fac_bio}
			{$p_fac_research_interests}
			[/col]
			[col class="col-sm col-sm-12 col-xl-3 pl-0 pl-xl-auto"]
			{$p_fac_degrees}
			{$p_fac_contact}
			[/col]
			[/row]
			[/tabs_section]
			TAB_ABOUT;

			$tabs_section_publications = <<<TAB_PUB
			[tabs_section title="Publications" id="tab-fac-publications"]
			[row]
			[col class="col-sm col-sm-12"]
			{$p_fac_publications}
			[/col]
			[/row]
			[/tabs_section]
			TAB_PUB;

			$fac_tabs = <<<FAC_TABS
			[uw_tabs id="tabs-fac-{$id}" style="alt-tab"]
			{$tabs_section_about}
			{$tabs_section_publications}
			[/uw_tabs] 
			FAC_TABS;

			// display the tabs
			echo do_shortcode( $fac_tabs );
			
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'uw_wp_theme' ),
					'after'  => '</div>',
				)
			);
			?>
			
		</div>

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
					uw_wp_theme_edit_post_link();
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div>
</article><!-- #post-<?php //the_ID();  ?> -->
