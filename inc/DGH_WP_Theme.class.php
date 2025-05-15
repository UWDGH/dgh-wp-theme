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
			
			/**
			 * implement hook init
			 */
			add_action('init', array( __CLASS__, 'dgh_wp_theme_audience_menu' ) );
			add_action('init', array( __CLASS__, 'dgh_wp_theme_template_faculty' ) );
			
			/**
			 * implement filter hook display_post_states
			 */
			add_filter('display_post_states', array( __CLASS__, 'dgh_wp_theme_add_faculty_page_post_state'), 10, 2);

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
             * implement hook admin_enqueue_scripts
             */
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'dgh_wp_theme_admin_enqueue_scripts' ) );

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
			
			/**
			 * implement hook wp_ajax_{action} and wp_ajax_nopriv_{action}
			 */
			// add_action( 'wp_ajax_dgh_wp_theme_fac_ajax_callback', array( __CLASS__, 'dgh_wp_theme_fac_ajax_callback' ) );
			// add_action( 'wp_ajax_nopriv_dgh_wp_theme_fac_ajax_callback', array( __CLASS__, 'dgh_wp_theme_fac_ajax_callback' ) );

			/**
			 * implement hook wp_head
			 */
			add_action('wp_head',  array( __CLASS__, 'dgh_wp_theme_faculty_profile_template_headers' ) );
			
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
        * callback function for hook admin_enqueue_scripts
        */
        static function dgh_wp_theme_admin_enqueue_scripts( $hook ) {
            global $typenow;
            global $pagenow;
            
            // Only add to this post.php page
            if ( 'post.php' != $hook ) {
                return;
            }
            
			wp_enqueue_script( 'dgh_wp_theme_admin', get_stylesheet_directory_uri() . '/js/dgh-wp-theme-admin.js', array('jquery') );
			// localize script with 'DGH_WP_Theme_Admin' object and 'admin_ajax_url' key => value
			// the dgh-faculty-sync-admin file uses the value in 
			// DGH_WP_Theme_Admin.admin_ajax_url to perform the jQuery Post.
			$DGH_WP_Theme_Admin = array( 
				'admin_ajax_url' => admin_url('admin-ajax.php'), 
				'faculty_home_page_id' => get_option( 'dgh_wp_theme_current_fac_page_id' )
			);
			wp_localize_script( 'dgh_wp_theme_admin', 'DGH_WP_Theme_Admin', $DGH_WP_Theme_Admin );
    
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

			// register script for faculty page
			// enqueue hook invoked from template-faculty.php
			wp_register_script('dgh-wp-theme-page-faculty', get_stylesheet_directory_uri() . '/js/dgh-wp-theme-page-faculty.js', array('jquery'));

		}
	
	/**
	 * callback function for hook wp_enqueue_scripts
	 */
	static function dgh_wp_theme_enqueue_page_faculty_scripts() {

		wp_enqueue_script('dgh-wp-theme-page-faculty');
		// localize script with 'DGH_Faculty' object and 'admin_ajax_url' key => value
		$DGH_Faculty = array( 
				'admin_ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('page-faculty-nonce'),
				'fac_btnGotoPrefix' => __( 'Go to profile page for ', 'dgh-wp-theme' ),
				'fac_btnPreviewPrefix' => __( 'Preview modal for ', 'dgh-wp-theme' ),
			 );
		wp_localize_script( 'dgh-wp-theme-page-faculty', 'DGH_Faculty', $DGH_Faculty );

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
				<a href="{$_logout_url}">{$_logout_greeting}&nbsp;<em>{$_current_user_login}</em>&nbsp;({$_logout_text})</a>
				LOGOUT_LINK;

      } else {

				// construct the login link as a heredoc string
				$_output = <<<LOGIN_LINK
				<a href="{$_login_url}">{$_login_text}</em></a>
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

		/**
		 * callback function for hook init
		 */
		static function dgh_wp_theme_template_faculty() {

			if ( !is_admin() )
				return;

			// empty the option if no page is using the Faculty Home template
			if ( !self::dgh_wp_theme_uses_faculty_home_template() ) {
				update_option( 'dgh_wp_theme_current_fac_page_id', null );
				// do_action('qm/debug', 'option "dgh_wp_theme_current_fac_page_id" UPDATED to null' );
			}

			// get the template
			$the_post_ID = null;
			$the_post_template = null;
			if ( isset($_GET['post']) ) {
				$the_post_ID = $_GET['post'];
				$the_post_template = get_post_meta($the_post_ID, '_wp_page_template', true);
			}

			if ( 'templates/template-faculty.php' == $the_post_template ) {

				// if this page is using the Faculty Home template then store the id in the option
				update_option( 'dgh_wp_theme_current_fac_page_id', $the_post_ID );
				// do_action('qm/debug', 'option "dgh_wp_theme_current_fac_page_id" UPDATED to '.get_option( 'dgh_wp_theme_current_fac_page_id' ) );

				// remove features for the current 'Faculty Home' template
				$features = array( 'editor','excerpt','author','thumbnail','trackbacks','custom-fields','comments','revisions','post-formats');
				foreach ($features as $feature) {
					remove_post_type_support('page', $feature);
				}
				
			}

		}

		/**
		 * helper function 
		 * determine if there's an existing post using the Faculty home template
		 * @return boolean
		 */
		private static function dgh_wp_theme_uses_faculty_home_template() {
			global $wpdb;
			$retval = false;
			$query_result = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT COUNT(post_id) as total FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",
					array( '_wp_page_template', 'templates/template-faculty.php' )
				),
				ARRAY_A  
			);
			// do_action('qm/debug', $query_result);
			// do_action('qm/debug', $query_result[0]['total'] );
			if ( $query_result[0]['total'] == 1 ) {
				$retval = true;
			}
			return $retval;
		}

		/**
		 * helper function 
		 * retrieve the post title and slug for the breadcrumb trail
		 * @returns array or false
		 */
		static function dgh_wp_theme_faculty_home_breadcrumb() {
			global $wpdb;
			$query_result = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT post_title, post_name FROM $wpdb->posts WHERE ID = %d",
					get_option( 'dgh_wp_theme_current_fac_page_id' )
				),
				ARRAY_A  
			);
			
			if ( isset( $query_result[0] ) ) {
				return $query_result[0];
			} else {
				return false;
			}
		}

		/**
		 * callback function for filter hook display_post_states
		 */
		static function dgh_wp_theme_add_faculty_page_post_state($post_states, $post) {

			// Check if the post ID matches the desired post
			if ( $post->ID === (int)get_option( 'dgh_wp_theme_current_fac_page_id' ) ) {
				// Add a custom post state with the key 'custom-state' and value 'Custom State'
				$post_states['custom-state'] = __('Faculty Home', 'dgh-wp-theme');
			}
		
			return $post_states;
			
		}
		
		/**
		 * callback function for hook wp_head
         * requires following template files in theme:
         * - templates/template-faculty.php
         * - templates/template-faculty-profile.php
		 */
		static function dgh_wp_theme_faculty_profile_template_headers() {

			if ( !is_page_template( 'templates/template-faculty.php' ) && !is_page_template( 'templates/template-faculty-profile.php' ) ) {
				return;
			}

			echo '<!--//unused by Google, but can be used by other search engines//-->';
			echo '<meta name="robots" content="noarchive,nocache" />';
			echo '<!--//END: unused by Google//-->';

		}


		/**
		 * callback function for hook wp_ajax_nopriv_{action}
		 */
		// static function dgh_wp_theme_fac_ajax_callback() {
		// 	error_log( print_r( $_GET['faculty_page'], true ) );

		// 	// defaults
		// 	// $current_faculty_page_number = 0;
		// 	// $previous_faculty_page_number = -1;
		// 	// $next_faculty_page_number = 1;
		// 	$offset = 0;
		// 	$posts_per_page = 4;

		// 	if ( isset( $_GET['faculty_page'] ) ) {
		// 		if ( $_GET['faculty_page'] == 'all' ) {
		// 			$posts_per_page = -1;
		// 		} else {
		// 			$offset = $_GET['faculty_page'];
		// 		}
		// 	}

		// 	$fac_list = <<<FAC_LIST
		// 	[su_posts template="su-posts-templates/faculty-card-loop.php" posts_per_page="{$posts_per_page}" offset="{$offset}" post_type="dgh_faculty_profile" orderby="meta_value" meta_key="_dgh_fac_name1" order="asc"]
		// 	FAC_LIST;
		// 	echo do_shortcode( $fac_list );

		// 	// $retval = 'foobar';
		// 	// if ( isset( $_POST['henkie'] ) ) {
		// 	// 	$retval = array(
		// 	// 		'html' => $_POST['henkie'],
		// 	// 	);
		// 	// }
		// 	// echo json_encode($retval, JSON_PRETTY_PRINT);

		// 	// Don't forget to stop execution afterward.
		// 	wp_die();

		// }

  }

  new DGH_WP_Theme;

}
