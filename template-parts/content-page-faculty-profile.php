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
$fac_photo_hidden = false;
$fac_photo_url = '';		// the SPH photo URL. Only us as fallback
$fac_image_alt_text = '';	// alt text (appears unused in UW's 'large' card)
$fac_appts = array();		// UW appointments
$fac_job_title = array();	// Additional job title(s)
$fac_fname = '';			// first name
$fac_lname = '';			// last name
$fac_appt_ttl_dept = '';	// that one strange appointment title field. we can use it as a fallback
$fac_email = '';			// email
$fac_email_hidden = false;
$fac_phone_number = '';		// phone number
$fac_phone_number_hidden = false;
$fac_office = '';			// office location
$fac_office_hidden = false;
$fac_degrees = array();		// Degrees
$fac_publications = '';		// Publications
$fac_pubref_use = false;    // Use pubref
$fac_pubref = '';		    // pubref
$fac_image_url = get_stylesheet_directory_uri() . '/assets/img/W_placeholder.jpg';
$fac_links = array();		// Links
$fac_ranks = array();			// Faculty rank terms
$fac_ranks_links = array();

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
$p_fac_links = '';
$p_fac_ranks = '';
$p_fac_rank = '';

// content headings vars are prefixed with 'h'
$h_bio = '<h2>'.__('Bio', 'dgh-wp-theme').'</h2>';
$h_degrees = '<h2>'.__('Academic Degrees', 'dgh-wp-theme').'</h2>';
$h_contact = '<h2>'.__('Contact Information', 'dgh-wp-theme').'</h2>';
$h_research_interests = '<h2>'.__('Areas of Expertise', 'dgh-wp-theme').'</h2>';
$h_publications = '<h2>'.__('Select Publications', 'dgh-wp-theme').'</h2>';
$h_links = '<h2>'.__('Related Links', 'dgh-wp-theme').'</h2>';

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
$fac_email_hidden =  get_post_meta( $id, '_dgh_fac_email_hidden', true );
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
$fac_photo_hidden = get_post_meta( $post->ID, '_dgh_fac_photo_hidden', true );
// SPH photo url
$fac_photo_url = get_post_meta( $post->ID, '_dgh_fac_photo_url', true );
if ( ! $fac_photo_hidden ) {
	if ( has_post_thumbnail( $id ) ) {
		$fac_image_url = get_the_post_thumbnail_url( $id );
		$att_id = attachment_url_to_postid( $fac_image_url );
		$fac_image_alt_text = wp_get_attachment_caption( $att_id );
	} elseif ( $fac_photo_url ) {
		// fallback: deeplink the SPH photo URL
		if ( \DGH_Post_Types\DGH_Template::is_dgh_fac_photo_url_ok( $fac_photo_url ) ) {
			$fac_image_url = $fac_photo_url;
		}
	}
}
// publications
$fac_publications = get_post_meta( $id, '_dgh_fac_publications', true );
// pubref
$fac_pubref_use =  get_post_meta( $id, '_dgh_fac_pubref_use', true );
$fac_pubref = get_post_meta( $id, '_dgh_fac_pubref', true );
// links
$fac_links =  get_post_meta( $id, '_dgh_fac_links' );
if ( !empty( $fac_links ) ) { $fac_links = $fac_links[0]; }

/**
 * set sync status
 */
$fac_url_id = get_post_meta( $id, '_dgh_fac_url_id', true );
$fac_ignore_sync = get_post_meta( $id, '_dgh_fac_ignore_sync', true );
if ( empty( $fac_url_id ) || !empty( $fac_ignore_sync ) ) {
	$fac_is_synced = false;
}

// get the faculty ranks
if ( taxonomy_exists( 'dgh_faculty_rank' ) ) {
	$fac_ranks = get_the_terms( $id, 'dgh_faculty_rank' );
}

/**
 * construct output vars
 */
// construct appointments
if ( !empty( $fac_appts) || !empty( $fac_job_title)  ) {
	$p_fac_appts .= '<ul class="fac-appts" aria-label="'.__('Appointments','dgh-wp-theme').'">';
	if ( !empty( $fac_appts) && $fac_is_synced ) {
		foreach ($fac_appts as $key => $value) {
			$p_fac_appts .= '<li>' . esc_html($value) . '</li>';
		}
	}
	if ( !empty( $fac_job_title) ) {
		// $p_fac_appts .= ( !empty( $fac_appts)) ? '<hr>' : '' ;
		foreach ($fac_job_title as $key => $value) {
			$p_fac_appts .= '<li>' . esc_html($value) . '</li>';
		}
	} elseif ( empty( $fac_job_title) && !empty($fac_appt_ttl_dept) && !$fac_is_synced ) {
		$p_fac_appts .= '<li>' . esc_html($fac_appt_ttl_dept) . '</li>';
	}
	$p_fac_appts .= '</ul>';
} 

// construct email
if ( $fac_email ) {
	$p_fac_email = '<div class="fac-email" data-alt="Email">';
	$p_fac_email .= '<span class="dashicons dashicons--fac dashicons-email"><span class="screen-reader-text">'.__('Email','dgh-wp-theme').'</span></span>';
	$p_fac_email .= ($fac_email_hidden) ? '<i>' . __('undisclosed','dgh-wp-theme') . '</i>' : '<p><a href="'.esc_url( 'mailto:' . $fac_email ).'">'.esc_html( $fac_email ).'</a></p>';
	$p_fac_email .= '</div>';
}

// construct phone number
if ( $fac_phone_number ) {
	$fac_phone_number_href = '+1' . preg_replace('/\D+/', '', $fac_phone_number);	// remove all non-digit characters from phone nr, prefix with '+1'
	$p_fac_phone_number = '<div class="fac-phone" data-alt="Phone number">';
	$p_fac_phone_number .= '<span class="dashicons dashicons--fac dashicons-phone"><span class="screen-reader-text">'.__('Phone number','dgh-wp-theme').'</span></span>';
	$p_fac_phone_number .= ($fac_phone_number_hidden) ? '<i>' . __('undisclosed','dgh-wp-theme') . '</i>' :  '<p><a href="'.esc_url( 'tel:' . $fac_phone_number_href ).'">'.esc_html( $fac_phone_number ).'</a></p>';
	$p_fac_phone_number .= '</div>';
}

// construct office location
if ( $fac_office ) {
	$p_fac_office = '<div class="fac-office" data-alt="Office location">';
	$p_fac_office .= '<span class="dashicons dashicons--fac dashicons-location"><span class="screen-reader-text">'.__('Office location','dgh-wp-theme').'</span></span>';
	$p_fac_office .= ($fac_office_hidden) ? '<i>' . __('undisclosed','dgh-wp-theme') . '</i>' : wpautop( html_entity_decode( $fac_office ) );
	$p_fac_office .= '</div>';
}

// construct contact
if ( !empty($fac_office) || !empty($fac_email) || !empty($fac_phone_number) ) {
	// overwrite heading
	$h_contact = '<h2>'.__('Contact Information', 'dgh-wp-theme').' <span class="screen-reader-text">'.__('for', 'dgh-wp-theme').' '.$title.'</span></h2>';
	$p_fac_contact = $h_contact . '<address>' . $p_fac_office . $p_fac_phone_number . $p_fac_email . '</address>';
	// $p_fac_contact = $h_contact . $p_fac_office . $p_fac_phone_number . $p_fac_email;
}

// construct bio
$p_fac_bio = ( !empty( $fac_bio ) ) ? $h_bio . $fac_bio : $h_bio . 'N/A';

// construct areas of expertise
$p_fac_research_interests = ( !empty( $fac_research_interests ) ) ? $h_research_interests . wpautop( html_entity_decode( $fac_research_interests ) ) : '';

// construct degrees
if ( !empty( $fac_degrees) ) {
	$p_fac_degrees .= $h_degrees;
	$p_fac_degrees .= '<ul class="fac-degrees" aria-label="'.__('Academic Degrees','dgh-wp-theme').'">';
	foreach ($fac_degrees as $key => $value) {
		$p_fac_degrees .= '<li><span class="dashicons dashicons--fac dashicons-welcome-learn-more"></span>' . esc_html($value) . '</li>';
	}
	$p_fac_degrees .= '</ul>';
}

// construct publications
// $p_fac_publications = ( !empty( $fac_publications ) ) ? $h_publications . wpautop( html_entity_decode( $fac_publications ) ) : '';
$p_fac_publications = ( !empty( $fac_publications ) ) ? wpautop( html_entity_decode( $fac_publications ) ) : '';
if ( $fac_pubref && $fac_pubref_use ) {
	// $p_fac_publications = $h_publications . do_shortcode( html_entity_decode( $fac_pubref) );
	$p_fac_publications = do_shortcode( html_entity_decode( $fac_pubref) );
}

// construct email
if ( $fac_links ) {
	$p_fac_links .= $h_links;
	$p_fac_links .= '<ul class="fac-links">';
	foreach ($fac_links as $link) {
		$link_url = $link['url'];
		$link_text = ( !empty($link['text']) ) ? $link['text'] : $link['url'];
		$p_fac_links .= '<li><a href="'.$link_url.'">'.$link_text .'</a><span class="dashicons dashicons--fac dashicons-external"><span class="screen-reader-text">'.__('External link','dgh-wp-theme').'</span></span></li>';
	}
	$p_fac_links .= '</ul>';
}

// construct faculty ranks linkbacks
// do_action('qm/debug', $fac_ranks);
// do_action('qm/debug', is_null($fac_ranks));
if ( $fac_ranks) {
	$fac_page = DGH_WP_Theme::dgh_wp_theme_faculty_home_breadcrumb();
	$fac_page_url = home_url( '/'.$fac_page['post_name'] );
	$p_fac_ranks = '<h2>Faculty Rank</h2>';
	$p_fac_ranks .= '<ul style="list-style-type: none; margin-left: 0px;">';
	foreach ($fac_ranks as $rank) {
		$faculty_rank_linkback_url = add_query_arg( 'rank', $rank->slug, $fac_page_url );
		$faculty_rank_linkback_url = add_query_arg( 'page_index', 0, $faculty_rank_linkback_url );
		$faculty_rank_linkback_url = add_query_arg( '_wpnonce', wp_create_nonce( 'dgh-fac-page-index-0' ), $faculty_rank_linkback_url );
		$faculty_rank_linkback = '<a href="'. esc_url($faculty_rank_linkback_url).'" style="">'. esc_html($rank->name) .'</a>';
		$fac_ranks_links[] = $faculty_rank_linkback;
		$p_fac_ranks .= '<li><span class="dashicons dashicons--fac dashicons-category"></span>'.$faculty_rank_linkback.'</li>';
	}
	$p_fac_ranks .= '</ul>';
	// for the card
	$p_fac_rank = '<div><small>' . $fac_ranks_links[0] .  '</small></div>';
}

?>
<?php if ( $post->post_status != 'publish' ): ?>
	<div class="dgh-notice dgh-notice--uw-warning" data-nosnippet role="status" aria-live="polite"><?php _e( 'This Faculty Profile is not published.', 'dgh-wp-theme' ); ?></div>
<?php endif; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content entry-content-faculty-profile">

		<h1 class="entry-title screen-reader-text" aria-hidden="true"><?php the_title(); ?></h1> <!--//hidden from accessibility API. Title is duplicated in the card title//-->

		<div class="entry-content-wrapper">

			<section id="section-fac-header-<?php echo $id; ?>" aria-label="<?php _e( 'Faculty profile header', 'dgh-wp-theme' ); ?>" aria-describedby="faculty-profile-header-description">
				
				<p id="faculty-profile-header-description" class="screen-reader-text"><?php _e( 'Above the fold content, presented visually as a card. Showing a faculty profile image on one side of the card, and their name, appointment titles, and email address on the other side of the card.', 'dgh-wp-theme' ); ?></p>
			
				<?php
				// construct the card shortcode with appointments and email as the body
				$uw_card = <<<UW_CARD
				[uw_card style="half-block-large" align="left" color="gold" title="{$title}" titletag="h1" image="{$fac_image_url}" alt="{$fac_image_alt_text}"]
				<div class="udub-slant-divider"><span></span></div>
				{$p_fac_appts}
				{$p_fac_email}
				[/uw_card]
				UW_CARD;
			
				// display the card
				echo do_shortcode( $uw_card ); 

				?>
			</section>

			<section id="section-fac-content-<?php echo $id; ?>" aria-label="<?php _e( 'Faculty profile content', 'dgh-wp-theme' ); ?>">
			
			<?php
			// the_content();

			// // construct the 'About' tabs section
			// $tabs_section_about_txt = __('About', 'dgh-wp-theme');
			// $tabs_section_about = <<<TAB_ABOUT
			// [tabs_section title="{$tabs_section_about_txt} {$fac_fname} {$fac_lname}" id="tab-fac-about"]
			// [row]
			// [col class="col-sm col-sm-12 col-xl-9 pl-xl-auto pr-xl-5"]
			// {$p_fac_bio}
			// {$p_fac_research_interests}
			// [/col]
			// [col class="col-sm col-sm-12 col-xl-3 pl-0 pl-xl-auto"]
			// {$p_fac_degrees}
			// {$p_fac_contact}
			// {$p_fac_links}
			// [/col]
			// [/row]
			// [/tabs_section]
			// TAB_ABOUT;

			// $tabs_section_publications = '';
			// if ( !empty( $fac_publications ) ) {
			// 	$tabs_section_publications = <<<TAB_PUB
			// 	[tabs_section title="Select Publications" id="tab-fac-publications"]
			// 	[row]
			// 	[col class="col-sm col-sm-12"]
			// 	{$p_fac_publications}
			// 	[/col]
			// 	[/row]
			// 	[/tabs_section]
			// 	TAB_PUB;
			// }

			// $fac_tabs = <<<FAC_TABS
			// [uw_tabs id="tabs-fac-{$id}" style="alt-tab"]
			// {$tabs_section_about}
			// {$tabs_section_publications}
			// [/uw_tabs] 
			// FAC_TABS;

			// // display the tabs
			// echo do_shortcode( $fac_tabs );

			$publications_accordion = '';
			$h_publications = '<h2>'.__('Publications', 'dgh-wp-theme').'</h2>';
			$acc_pub_title = __( 'Publications', 'dgh-wp-theme' );
			$acc_pub_sec1_title = __( 'Select Publications', 'dgh-wp-theme' );
			if ( !empty( $fac_publications ) ) {
				$publications_accordion = <<<ACCORDION_PUB
				{$h_publications}
				[accordion id="accordion-fac-{$id}" name="{$acc_pub_title}" style="non-bold" titletag="h2"]
				[section title="{$acc_pub_sec1_title}"]{$p_fac_publications}[/section]
				[/accordion] 
				ACCORDION_PUB;
			}

			$fac_content = <<<FAC_CONTENT
			[row]
			[col class="col-sm col-sm-12 col-xl-9 pl-xl-auto pr-xl-5"]
			{$p_fac_bio}
			{$p_fac_research_interests}
			{$publications_accordion}
			[/col]
			[col class="col-sm col-sm-12 col-xl-3 pl-0 pl-xl-auto"]
			{$p_fac_degrees}
			{$p_fac_contact}
			{$p_fac_links}
			{$p_fac_ranks}
			[/col]
			[/row]
			FAC_CONTENT;
			
			echo do_shortcode( $fac_content );

			?>
			
			</section>

			<?php
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
