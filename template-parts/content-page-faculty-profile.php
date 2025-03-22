<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package uw_wp_theme
 */

?>

<?php
// get the data
$title = get_the_title();

// image
$image_url = get_stylesheet_directory_uri() . '/assets/img/profile-placeholder.png';
$alt_text = '';
if ( has_post_thumbnail( $post->ID ) ) {
	$image_url = get_the_post_thumbnail_url( $post->ID );
	$att_id = attachment_url_to_postid( $image_url );
	$alt_text = wp_get_attachment_caption( $att_id );
}
// appointments
$appts =  get_post_meta( $post->ID, '_dgh_fac_appts' );
if ( !empty( $appts ) ) { $appts = $appts[0]; }
// job titles
$job_title =  get_post_meta( $post->ID, '_dgh_fac_job_title' );
if ( !empty( $job_title ) ) { $job_title = $job_title[0]; }
// that one strange title field
$fac_appt_ttl_dept = get_post_meta( $post->ID, '_dgh_fac_appt_ttl_dept', true );
do_action('qm/debug', empty($fac_appt_ttl_dept) );

// email
$fac_email =  get_post_meta( $post->ID, '_dgh_fac_email', true );
$card_email = '';
if ( $fac_email ) {
	$card_email  = '<div class="fac-email">';
	$card_email .= '<p><a href="'.esc_url( 'mailto:' . $fac_email ).'">'.esc_html( $fac_email ).'</a></p>';
	$card_email .= '</div>';
}

// construct the fac-appts div
$fac_appts = '';
if ( !empty( $appts) || !empty( $job_title)  ) {
	$fac_appts .= '<div class="fac-appts">';
	if ( !empty( $appts) ) {
		foreach ($appts as $key => $value) {
			$fac_appts .= '<div role="note" aria-label="University of Washington Appointment">' . $value . '</div>';
		}
	}
	if ( !empty( $job_title) ) {
		foreach ($job_title as $key => $value) {
			$fac_appts .= '<div role="note" aria-label="Job Title">' . $value . '</div>';
		}
	}
	$fac_appts .= '</div>';
} elseif ( !empty($fac_appt_ttl_dept) ) {
	$fac_appts .= '<div class="fac-appts">';
	$fac_appts .= '<div role="note" aria-label="Department Title">' . $fac_appt_ttl_dept . '</div>';
	$fac_appts .= '</div>';
}

// add the fac-appts div to the body of the card
$card_body = '';
$card_body .= $fac_appts;

do_action('qm/debug', $post );
do_action('qm/debug', get_post_meta( $post->ID ) );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content entry-content-faculty-profile">

	<?php 
	// construct the card
	$uw_card = <<<UW_CARD
	<style>
	.card.large .image-large {
		background-position: 0 0;	}
	.card.large .card-body {
		min-height: 360px; }
	</style>
	[uw_card 
	style="large" 
	align="left" 
	color="gold" 
	title="{$title}" 
	titletag="h1" 
	image="{$image_url}" 
	alt="{$alt_text}"]
	<div class="udub-slant-divider"><span></span></div>
	{$card_body}
	{$card_email}
	[/uw_card]
	UW_CARD;

	// display the card
	echo do_shortcode( $uw_card ); 

	/*
	?>

		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( !empty( $appts) || !empty( $job_title)  ) : ?>
		<div class="fac-appts">
			<?php if ( !empty( $appts) ) : ?>
				<?php foreach ($appts as $key => $value) : ?>
					<div><?php //echo $value; ?></div>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( !empty( $job_title) ) : ?>
				<?php foreach ($job_title as $key => $value) : ?>
					<div><?php //echo $value; ?></div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php
	*/
			// TO DO: design presentation
			/*
			if ( has_post_thumbnail( get_the_ID() ) ) {
				$url_full  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
				?>
				<div id="featured-image-<?php the_ID(); ?>" class="featured-image entry-thumbnail dgh-faculty-profile-image">
					<a href="<?php echo $url_full[0]; ?>" title="This link displays a full size photo of <?php echo get_the_title(); ?>.">
					<?php
					the_post_thumbnail( 'medium' , array('id' => 'wp-post-image-'.get_the_ID(), 'class' => 'alignright wp-post-image--faculty-profile wp-img' ) );
					?>
					<span class="screen-reader-text"> A headshot photo of <?php echo get_the_title(); ?></span>
					</a>
				</div>
				<?php
			} else {
				?>
				<div id="featured-image-placeholder" class="featured-image entry-thumbnail dgh-faculty-profile-image">
					<img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/profile-placeholder.png'; ?>" id="wp-post-image-placeholder" class="alignright wp-post-image-placeholder wp-img" decoding="async" fetchpriority="high" width="300" height="300">
					<span class="screen-reader-text"> A profile placeholder image.</span>
				</div>
				<?php
			}
				*/
		?>
		<div class="entry-content-wrapper">
			<!-- <h2>Bio</h2> -->
		<?php
			// the_content();

			$tabs_section_about = '';
			$fac_bio = apply_filters( 'the_content', get_the_content() );
			$fac_fname = get_post_meta( $post->ID, '_dgh_fac_fname', true );
			$fac_lname = get_post_meta( $post->ID, '_dgh_fac_lname', true );
			
			$fac_education = '';
			if ( !empty($fac_bio) ){
				$tabs_section_about = <<<TAB_ABOUT
				[tabs_section title="About {$fac_fname} {$fac_lname}" id="tab-fac-about"]
				[row]
				[col class="col-sm col-sm-12 col-xl-8"]
				<h2>Bio</h2>
				<p>{$fac_bio}</p>
				[/col]
				[col class="col-sm col-sm-12 col-xl-4 pl-0 pl-xl-auto"]
				<h2>Education</h2>
				<p>{$fac_education}</p>
				<p>
					<div>degrees[deg_ttl], degrees[inst_ttl]</div>
					<div>degrees[deg_ttl], degrees[inst_ttl]</div>
					<div>degrees[deg_ttl], degrees[inst_ttl]</div>
				</p>
				[/col]
				[/row]
				[/tabs_section]
				TAB_ABOUT;
			}

			$tab_contact = <<<TAB_CONTACT
			TAB_CONTACT;
			
			$fac_research_interests = get_post_meta( $post->ID, '_dgh_fac_research_interests', true );
			// do_action('qm/debug', empty($fac_research_interests) );
			$tab_expertise = '';
			if ( !empty($fac_research_interests) ){
				$tab_expertise = <<<TAB_EXPERTISE
				[tabs_section title="Areas of Expertise" id="tab-fac-expertise"]
				<h2>Areas of Expertise</h2>
				<p>{$fac_research_interests}</p>
				[/tabs_section]
				TAB_EXPERTISE;
			}

			$id = get_the_ID();
			$fac_tabs = <<<FAC_TABS
			[uw_tabs id="tabs-faculty-{$id}" style="alt-tab"]
			{$tabs_section_about}
			[tabs_section title="Contact Information" id="tab-fac-contact"]
				<h2>Contact Information</h2>
				<p>
					<div>contact[street_addr]</div>
					<div>contact[street_addr_2]</div>
					<div>contact[street_addr_3]</div>
					<div>contact[campus_box]</div>
					<div>contact[city] contact[state] contact[zip]</div>
				</p>
				<p>
					<div>contact[phone_number]</div>
				</p>
				<p>
					<div>contact[email]</div>
				</p>
			[/tabs_section]
			{$tab_expertise}
			[tabs_section title="Primary Research Area" id="tab-fac-research"]

			[/tabs_section]
			[tabs_section title="Publications" id="tab-fac-publications"]
				<h2>Publications</h2>
				<p>publications[fac_web_text]</p>
			[/tabs_section]
			[/uw_tabs] 
			FAC_TABS;
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
