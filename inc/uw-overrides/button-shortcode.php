<?php

/**
 * UW Button Shortcode.
 * allows for styled buttons to be added to content
 *
 * structure: [uw_button style="arrow" size="large" color="purple" target="#"](button copy)[/uw_button]
 */

 add_action('wp_loaded', 'replace_parent_theme_feature_uw_button', 11);
 function replace_parent_theme_feature_uw_button() {
	 // global $shortcode_tags;
	 // do_action('qm/debug', $shortcode_tags);
 
	 // First we remove the parent shortcode
	 if ( shortcode_exists( 'uw_button' ) )
		 remove_shortcode('uw_button');
 
	 // Then we add our own replacement shortcode
	 add_shortcode( 'uw_button', 'uw_button_shortcode' );
 }
 
	// enqueue client-side css/js
	// add_action('wp_enqueue_scripts', 'enqueue_uw_button_external');
	// function enqueue_uw_button_external() {
	// 	wp_enqueue_style( 'uw_button_external', plugin_dir_url(__FILE__) . 'css/uw_button_external.css' );
	// }
 
/**
 * Enqueue button script override
 * 
 * Overrides comments: 
 * added style attribute option for 'external'. see complementing style document: _theme_button_external.sass
 * added attribute 'display' with single option value 'new' to open link in new tab/window, but only in combination with style external
 */
 function uw_button_shortcode( $atts, $content = null ) {
	// Attributes.
	$atts = shortcode_atts(
		array(
			'style'  => '', // type of button. arrow (default), plus, play, primary, secondary, external.
			'size'   => '', // button size (large or small).
			'color'  => '', // button color.
			'target' => '', // where the button links to.
			'id'     => '', // optional ID.
			'display'=> '', // Where to display the linked URL. empty (default), new
		),
		$atts
	);

	// get the button ID, if there is one.
	$btn_id = ! empty( $atts['id'] ) ? 'id="' . esc_attr( $atts['id'] ) . '"' : '';

	if ( isset( $atts['style'] ) ) {
		if ( 'plus' === strtolower( $atts['style'] ) ) {
			$style = $atts['style'] . ' arrow';
		} elseif ( 'play' === strtolower( $atts['style'] ) ) {
			$style = $atts['style'] . ' arrow';
		} elseif ( 'arrow' === strtolower( $atts['style'] ) ) {
			$style = 'arrow';
		} elseif ( 'square-outline' === strtolower( $atts['style'] ) ) {
			$style = 'square-outline';
		} elseif ( 'external' === strtolower( $atts['style'] ) ) {
			$style = $atts['style'] . ' arrow';
		} else {
			$style = $atts['style'];
		}
	} else {
		$style = '';
	}

	$size = 'btn-lg';

	if ( isset( $atts['size'] ) ) {
		$size = $atts['size'] === 'small' && strpos( $style, 'square-outline' ) === false ? 'btn-sm' : 'btn-lg';
	}

	if ( isset( $atts['color'] ) ) {
		if ( false === strpos( $style, 'square-outline' ) ) {
			$color = ' ' . $atts['color'];
		} else {
			$color = ' ' . $atts['color'];
		}
	} else {
		$color = '';
	}

	$btn_target = '';
	if ( 'external' === strtolower( $atts['style'] ) && 'new' === strtolower( $atts['display'] ) ) {
		$btn_target = 'target="_blank"';
	}

	ob_start();
	?>

	<a href="<?php echo esc_attr( $atts['target'] ); ?>" <?php echo $btn_id; ?> <?php echo $btn_target; ?> class="btn <?php echo esc_attr( $size ); ?> <?php echo esc_attr( $style ); ?><?php echo esc_attr( $color ); ?>"><span><?php
	$output = ob_get_clean();

	if ( $content ) {
		$output .= $content;
	} else {
		$output .= 'Please add button text.';
	}

	if ( 'arrow' === strtolower( $atts['style'] ) || 'square-outline' === strtolower( $atts['style'] ) ) {
		$output .= '</span><span class="arrow-box"><span class="arrow"></span></span></a>';
	} elseif ( 'plus' === strtolower( $atts['style'] ) ) {
		$output .= '</span><span class="arrow-box"><span class="ic-plus"></span></span></a>';
	} elseif ( 'play' === strtolower( $atts['style'] ) ) {
		$output .= '</span><span class="arrow-box"><span class="ic-play"></span></span></a>';
	} elseif ( 'external' === strtolower( $atts['style'] ) ) {
		$output .= '</span><span class="arrow-box"><span class="ic-external"></span></span></a>';
	} else {
		$output .= '</span></span></a>';
	}

	return $output;
}