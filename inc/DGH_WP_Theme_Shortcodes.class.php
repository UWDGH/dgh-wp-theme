<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'DGH_WP_Theme_Shortcodes' ) ) {


	/**
	 * DGH_WP_Theme_Shortcodes class
	 */
  class DGH_WP_Theme_Shortcodes {

    /**
    * class constructor
    */
    function __construct()  {

			/**
			 * add custom shortcode
			 */
			if ( !shortcode_exists('udub_slant') ) {
				add_shortcode( 'udub_slant', array( __CLASS__, 'udub_slant_shortcode' ) );
			}

		}

		/**
		 * Shortcode 'udub_slant'
		 * Callback function for function add_shortcode
		 */
		static function udub_slant_shortcode( $atts ){

			$params = shortcode_atts( array(
				'color' => '',
				'width' => false,
				'height' => false,
				'mt' => false,
				'mb' => false
			), $atts );
			// color 'gold' or 'purple', any other value is ignored and will render purple
			$color = $params['color'];
			// inline styles for the span
			$spanstyle = 'style="';
			if ( $params['width'] != false ) {
				$spanstyle .= 'width:'.$params['width'].';';
			}
			if ( $params['height'] != false ) {
				$spanstyle .= 'height:'.$params['height'].';';
			}
			$spanstyle .= '"';
			// inline styles for the div
			$divstyle = 'style="';
			if ( $params['mt'] != false ) {
				$divstyle .= 'margin-top:'.$params['mt'].';';
			}
			if ( $params['mb'] != false ) {
				$divstyle .= 'margin-bottom:'.$params['mb'].';';
			}
			$divstyle .= '"';

			$return = '';
			if ( array_key_exists( 'uw_wp_theme', wp_get_themes() ) ) {
				// heredoc return string
				$return = <<<UDUB_SLANT
				<div class="udub-slant-divider udub-slant-divider-uw-wp-child-theme {$color}" {$divstyle}><span {$spanstyle}"></span></div>
				UDUB_SLANT;
			}
			
			return $return;

		}
		
	}
	
  new DGH_WP_Theme_Shortcodes;

}