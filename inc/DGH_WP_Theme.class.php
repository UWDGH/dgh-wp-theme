<?php
/**
* Theme Class
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'DGH_WP_Theme' ) ) {

	/**
	 * DGH_WP_Theme class
	 */
  class DGH_WP_Theme {
		
    /**
    * class constructor
    */
    function __construct()  {
			
      /**
			 * implement hook after_setup_theme with priority 11
			 * that's one priority below the default 10, 
			 * so the callback will fire after the parent theme is setup
			 */
			add_action( 'after_setup_theme', array( __CLASS__, 'dgh_wp_theme_setup' ), 11 );

    }
		
		/**
		 * Callback function for after_setup_theme hook
		 */
		static function dgh_wp_theme_setup() {

			/**
			 * include files
			 */
			include_once( get_stylesheet_directory() . '/inc/DGH_WP_Theme_Options.class.php' );
			include_once( get_stylesheet_directory() . '/inc/DGH_WP_Theme_Shortcodes.class.php' );
			
			/**
			 * implement hook init
			 */
			add_action('init', array( __CLASS__, 'dgh_wp_theme_audience_menu' ) );

			/**
			 * implement hook after_switch_theme
			 * Callback functions attached to this hook are only triggered in the theme being activated
			 */
			add_action('after_switch_theme', array( __CLASS__, 'dgh_wp_theme_after_switch_theme' ), 10, 2 );
			
			/**
			 * implement hook switch_theme
			 * Callback functions attached to this hook are only triggered in the theme being deactivated
			 */
			add_action('switch_theme', array( __CLASS__, 'dgh_wp_theme_switch_theme' ), 10, 3 );

			/**
			 * implement hook wp_enqueue_scripts
			 */
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'dgh_wp_theme_enqueue_scripts' ) );
			
			/**
			 * implement custom hook uw_wp_child_theme_login_link
			 */
			add_action( 'uw_wp_child_theme_login_link', array( __CLASS__, 'uw_wp_child_theme_login_link_callback' ), 10, 0 );
			
			/**
			 * implement hook wp_dashboard_setup
			 */
			add_action( 'wp_dashboard_setup', array( __CLASS__, 'dgh_wp_theme_dashboard_setup' ) );

		}
		
		/**
		 * Callback function for hook after_switch_theme
		 * Fires once after theme activation
		 */
		static function dgh_wp_theme_after_switch_theme( $old_name, $old_theme ) {
			// nothing to do here
		}
		
		/**
		 * Callback function for hook switch_theme
		 * Fires once after theme deactivation
		 */
		static function dgh_wp_theme_switch_theme( $new_name, $new_theme, $old_theme ) {
			// nothing to do here
		}

		/**
     * Callback for hook wp_enqueue_scripts
		 * 
		 * This is where front-end styles and scripts are added
     */
    static function dgh_wp_theme_enqueue_scripts() {
			
			// add the child-theme stylesheet, require the uw_wp_theme bootstrap style sheet
			$parenthandle = 'uw_wp_theme-bootstrap';
			wp_enqueue_style( 'dgh-wp-theme', get_stylesheet_uri(),
						array( $parenthandle ), 
						wp_get_theme()->get('Version') // this only works if you have Version in the style header
				);
			
			// add dashicons to front-end
			// @link( https://developer.wordpress.org/resource/dashicons )
			wp_enqueue_style( 'dashicons' );

			// add dgh-wp-theme script file
			wp_register_script('dgh-wp-theme', get_stylesheet_directory_uri() . '/js/dgh-wp-theme.js', array('jquery'));
			wp_enqueue_script('dgh-wp-theme');

			// localize script with 'UW_WP_Child_Theme' object and 'admin_ajax_url' key => value
			$UW_WP_Child_Theme = array( 'admin_ajax_url' => admin_url('admin-ajax.php') );
			wp_localize_script( 'dgh-wp-theme', 'UW_WP_Child_Theme', $UW_WP_Child_Theme );

		}

    /**
    * Callback function for hook init
    * Builds the default audience menu for DGH.
    */
    static function dgh_wp_theme_audience_menu() {

			$run_once = get_option('menu_check');
			if (!$run_once) {
				// Check if the menu exists
				$menu_name = __('Global Health Audience Menu', 'dgh-wp-theme');
				$menu_exists = wp_get_nav_menu_object( $menu_name );
				// If it doesn't exist, let's create it.
				if( !$menu_exists){
					// Create the menu
					$menu_id = wp_create_nav_menu($menu_name);
					// Set up default menu items
					wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  __('Support Us', 'dgh-wp-theme'),
						'menu-item-url' => '//globalhealth.washington.edu/support-us',
						'menu-item-attr-title' => __('Donate to Global Health', 'dgh-wp-theme'),
						'menu-item-status' => 'publish'));
					wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  __('Contact Us', 'dgh-wp-theme'),
						'menu-item-url' => '//globalhealth.washington.edu/contact',
						'menu-item-attr-title' => __('Contact Us', 'dgh-wp-theme'),
						'menu-item-status' => 'publish'));
				}
				// update the menu_check option to make sure this code only runs once
				update_option('menu_check', true);
			}

    }

		/**
		 * Callback function for custom hook uw_wp_child_theme_login_link
		 */
		static function uw_wp_child_theme_login_link_callback() {

			// function variables
			$_output = '';
			$_login_url = wp_login_url( get_permalink() );
			$_logout_url = wp_logout_url( get_permalink() );
			$_login_text = __( 'Log in', 'dgh-wp-theme' );
			$_logout_text = __( 'Log out', 'dgh-wp-theme' );
			$_logout_greeting = __( 'Hello', 'dgh-wp-theme' );

      if (is_user_logged_in()) {

				// conditional variables
        $_current_user = wp_get_current_user();
				$_current_user_login = $_current_user->user_login;

				// construct the logout link as a heredoc string
				$_output = <<<LOGOUT_LINK
				<a href="{$_logout_url}" title="{$_logout_text}">{$_logout_greeting}&nbsp;<em>{$_current_user_login}</em>&nbsp;({$_logout_text})</a>
				LOGOUT_LINK;

      } else {

				// construct the login link as a heredoc string
				$_output = <<<LOGIN_LINK
				<a href="{$_login_url}" title="{$_login_text}">{$_login_text}</em></a>
				LOGIN_LINK;

      }

			// echo the output to the action call
			echo $_output;

    }
		
		/**
		 * Callback function for hook wp_dashboard_setup
		 * Priority 11 (default + 1)
		 */
		static function dgh_wp_theme_dashboard_setup() {
			
			// remove dashboard meta boxes for non-admins
			$user = wp_get_current_user();
      if ( ( (!in_array( 'administrator', (array) $user->roles ) || !current_user_can('manage_options')) ) ) {

				// remove core widgets
				self::clear_core_dashboard_widgets();
				
			}
			
			// add custom dashboard widgets
			self::add_custom_dashboard_widgets();
			
			/**
			 * implement hook default_hidden_meta_boxes
			 */
			add_filter( 'default_hidden_meta_boxes' , array( __CLASS__, 'dgh_wp_theme_hidden_meta_boxes' ), 11, 2);

		}
		
		/**
		 * Helper function to clear the core widgets off the dashboard
		 */
		private static function clear_core_dashboard_widgets() {

			global $wp_meta_boxes;
			foreach( $wp_meta_boxes["dashboard"] as $position => $core ) {
				
				foreach( $core["core"] as $widget_id => $widget_info ){
					
					// keep the 'dashboard_activity' core widget
					if ( $widget_id != 'dashboard_activity' ) {

						remove_meta_box( $widget_id, 'dashboard', $position );

					}

				}

			}

		}
				
		/**
		 * Helper function adding custom dashboard widgets
		 */
		private static function add_custom_dashboard_widgets() {
			
			// add resources widget
			wp_add_dashboard_widget(
				'dashboard_dgh_wp_theme_resources_widget',		//Widget ID (used in the 'id' attribute for the widget).
				'WordPress Resources',		//Title of the widget.
				array( __CLASS__, 'dashboard_dgh_wp_theme_resources_widget_content' ),		//Callback function echoing its output.
				null,		//Function that outputs controls for the widget.
				null,		//Data that should be set as the $args property of the widget array (which is the second parameter passed to your callback).
				'normal',		//The context within the screen where the box should display.
				'default'		//The priority within the context where the box should show.
			);


			// Little dogs (easter egg)
			wp_add_dashboard_widget(
				'dashboard_little_dogs_widget',		//Widget ID (used in the 'id' attribute for the widget).
				__('Little Dogs', 'dgh-wp-theme'),		//Title of the widget.
				array( __CLASS__, 'dashboard_little_dogs_widget_content' ),		//Callback function echoing its output.
				null,		//Function that outputs controls for the widget.
				null,		//Data that should be set as the $args property of the widget array (which is the second parameter passed to your callback).
				'column3',		//The context within the screen where the box should display.
				'low'		//The priority within the context where the box should show.
			);

		}
		
    /**
		 * Callback for hook default_hidden_meta_boxes
     * Filters the default list of hidden meta boxes.
     */
		static function dgh_wp_theme_hidden_meta_boxes( $hidden, $screen ) {

			$user = wp_get_current_user();
      if ( ( (!in_array( 'administrator', (array) $user->roles ) || !current_user_can('manage_options')) ) ) {
				
				// hide the UW Theme dashboard widget for non-admins
				$hidden[] ='uw-dashboard-widget';

			}

			$hidden[] ='dashboard_little_dogs_widget';

			return $hidden;

		}
			
    /**
    * Callback function for wp_add_dashboard_widget
    */
		static function dashboard_dgh_wp_theme_resources_widget_content() {

			echo '<style> #dashboard_dgh_wp_theme_resources_widget .columns2 { width: 50%; display: inline-block; vertical-align: top;} </style>';

			echo '<div class="columns2">';

			echo '<h3>WordPress Manual</h3>';
			echo '<p><a href="' . esc_url('https://ewp.guide/go/wordpress-manual') . '" rel="nofollow noreferrer" target="_blank">Easy WP Guide WordPress Manual for WordPress</a> <span aria-hidden="true" class="dashicons dashicons-external"></span><p>';
			echo '<ul><li><a href="' . esc_url('https://ewp.guide/go/pages') . '" rel="nofollow noreferrer" target="_blank">—Pages</a> <span aria-hidden="true" class="dashicons dashicons-external"></span></li><li><a href="' . esc_url('https://ewp.guide/go/ce/classic-editor') . '" rel="nofollow noreferrer" target="_blank">—Classic Editor</a> <span aria-hidden="true" class="dashicons dashicons-external"></span></li><li><a href="' . esc_url('https://ewp.guide/go/media-library') . '" rel="nofollow noreferrer" target="_blank">—Media Library</a> <span aria-hidden="true" class="dashicons dashicons-external"></span></li></ul>';

			echo '</div>';

			echo '<div class="columns2">';
			
			echo '<h3>UW Theme Shortcodes</h3>';
			echo '<p><a href="' . esc_url('https://www.washington.edu/docs/shortcode-cookbook/') . '" rel="nofollow noreferrer" target="_blank">UW Shortcode Cookbook</a> <span aria-hidden="true" class="dashicons dashicons-external"></span><p>';
			
				
			echo '<h3>WordPress Training</h3>';
			echo '<p><a href="' . esc_url('https://www.linkedin.com/learning/wordpress-essential-training-22616273') . '" rel="nofollow noreferrer" target="_blank">WordPress Essential Training (LinkedIn Learning)</a> <span aria-hidden="true" class="dashicons dashicons-external"></span> (UW NetID required)<p>';

			echo '</div>';

			echo '<h3 style="text-align: center">WordPress introduction video</h3>';
			echo '<iframe width="100%" height="288" src="https://www.youtube.com/embed/8OBfr46Y0cQ" allowfullscreen></iframe>';

		}
		
    /**
    * Callback for dashboard_little_dogs_widget
    */
		static function dashboard_little_dogs_widget_content() {

			// embed Little Dogs TouTube video
			echo '<iframe width="100%" height="288" src="https://www.youtube.com/embed/SxM_Bh5lIPk" allowfullscreen></iframe>';

		}

  }

  new DGH_WP_Theme;

}
