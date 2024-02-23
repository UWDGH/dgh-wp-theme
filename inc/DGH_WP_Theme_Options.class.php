<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'DGH_WP_Theme_Options' ) ) {


	/**
	 * DGH_WP_Theme_Options class
	 */
  class DGH_WP_Theme_Options {
		
		/**
		 * Class variables
		 */
		private static $settingsupdated;

    /**
    * class constructor
    */
    function __construct()  {
			
			self::_dgh_wp_theme_settings_updated();

			/**
			 * implement hook admin_init
			 */
			add_action('admin_init', array( __CLASS__, 'dgh_wp_theme_add_settings' ) );
			add_action('admin_init', array( __CLASS__, 'dgh_wp_theme_register_settings' ) );

      /**
			 * implement hook admin_menu
			 */
      add_action( 'admin_menu', array( __CLASS__, 'dgh_wp_theme_admin_menu_item' ) );

			/**
			 * implement hook wp_ajax_{action}
			 */
			add_action( 'wp_ajax_enable_card_title_attribute_ajax_callback', array( __CLASS__, 'enable_card_title_attribute_ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_enable_card_title_attribute_ajax_callback', array( __CLASS__, 'enable_card_title_attribute_ajax_callback' ) );

    }

    /**
    * Is Postback from submitting the option form
    */
    private static function _dgh_wp_theme_settings_updated() {
			
      // Inititalize settings updated
      self::$settingsupdated = false;

      if( isset( $_GET['settings-updated'] ) ){

        self::$settingsupdated = $_GET['settings-updated'];

      }

			return self::$settingsupdated;

    }

		
    /**
     * Callback for hook admin_init
		 * 
		 * Add settings sections/fields
     */
		static function dgh_wp_theme_add_settings() {

			add_settings_section( 
				'dgh-wp-theme-options-global',	//Slug-name to identify the section
				__('Global settings', 'dgh-wp-theme'),	//Formatted title of the section
				array( __CLASS__, 'dgh_wp_theme_options_section_global' ),	//Function that echos out any content at the top of the section (between heading and fields)
				'dgh-wp-theme-options',	//The slug-name of the settings page on which to show the section
				array(
					'before_section' => '<section>',
					'after_section ' => '</section>',
					'section_class  ' => 'dgh-wp-theme-options-global',
				)	//Arguments used to create the settings section
			);

			add_settings_section( 
				'dgh-wp-theme-options-cards',	//Slug-name to identify the section
				__('Cards settings', 'dgh-wp-theme'),	//Formatted title of the section
				array( __CLASS__, 'dgh_wp_theme_options_section_cards' ),	//Function that echos out any content at the top of the section (between heading and fields)
				'dgh-wp-theme-options',	//The slug-name of the settings page on which to show the section
				array(
					'before_section' => '<section>',
					'after_section ' => '</section>',
					'section_class  ' => 'dgh-wp-theme-options-cards',
				)	//Arguments used to create the settings section
			);

			// setting field to enable sticky header
			add_settings_field(
				'dgh-wp-theme_enable_sticky_header',	//Slug-name to identify the field. Used in the 'id' attribute of tags
				__('Enable sticky header', 'dgh-wp-theme'),	//Formatted title of the field. Shown as the label for the field during output
				array( __CLASS__, 'dgh_wp_theme_enable_sticky_header_callback' ),	//Callback Function that fills the field with the desired form inputs. The function should echo its output
				'dgh-wp-theme-options',	//The slug-name of the settings page
				'dgh-wp-theme-options-global',	//The slug-name of the section of the settings page in which to show the box
				array(
					'label_for' => 'dgh-wp-theme_enable_sticky_header',
					'default_value' => 0,
					'type' => 'checkbox',
					'id' => 'dgh-wp-theme_enable_sticky_header',
					'name' => 'dgh-wp-theme_enable_sticky_header',
					'description' => "Enables sticky header, i.e. makes the purple bar and white menu bar sticky."
				)	//Extra arguments that get passed to the callback function
			);

			// setting field to enable sticky header mobile
			add_settings_field(
				'dgh-wp-theme_enable_sticky_header_sm',	//Slug-name to identify the field. Used in the 'id' attribute of tags
				__('Enable sticky header mobile only', 'dgh-wp-theme'),	//Formatted title of the field. Shown as the label for the field during output
				array( __CLASS__, 'dgh_wp_theme_enable_sticky_header_sm_callback' ),	//Callback Function that fills the field with the desired form inputs. The function should echo its output
				'dgh-wp-theme-options',	//The slug-name of the settings page
				'dgh-wp-theme-options-global',	//The slug-name of the section of the settings page in which to show the box
				array(
					'label_for' => 'dgh-wp-theme_enable_sticky_header_sm',
					'default_value' => 0,
					'type' => 'checkbox',
					'id' => 'dgh-wp-theme_enable_sticky_header_sm',
					'name' => 'dgh-wp-theme_enable_sticky_header_sm',
					'description' => "Enables sticky header on small screens only. This option overrides the 'Enable sticky header' option if checked, and disables stickiness on larger screens."
				)	//Extra arguments that get passed to the callback function
			);
			
			// setting field to enable the title attribute in card
			add_settings_field(
				'dgh-wp-theme_enable_card_title_attribute',	//Slug-name to identify the field. Used in the 'id' attribute of tags
				__('Set the title attribute', 'dgh-wp-theme'),	//Formatted title of the field. Shown as the label for the field during output
				array( __CLASS__, 'dgh_wp_theme_enable_card_title_attribute_callback' ),	//Callback Function that fills the field with the desired form inputs. The function should echo its output
				'dgh-wp-theme-options',	//The slug-name of the settings page
				'dgh-wp-theme-options-cards',	//The slug-name of the section of the settings page in which to show the box
				array(
					'label_for' => 'dgh-wp-theme_enable_card_title_attribute',
					'default_value' => 0,
					'type' => 'checkbox',
					'id' => 'dgh-wp-theme_enable_card_title_attribute',
					'name' => 'dgh-wp-theme_enable_card_title_attribute',
					'description' => "Sets the title attribute of the element with the .card class using the text of the element with the .card-title class."
				)	//Extra arguments that get passed to the callback function
			);

		}

		/**
		 * Callback for hook admin_init
		 * Register theme settings
		 */
		static function dgh_wp_theme_register_settings() {
			
			// Option to enable sticky header
			register_setting(
				"dgh-wp-theme_options",		//settings group name
				"dgh-wp-theme_enable_sticky_header",		//name of an option to sanitize and save
				array('default' => 0,)		//Data used to describe the setting when registered
			);

			// Option to enable sticky header mobile
			register_setting(
				"dgh-wp-theme_options",		//settings group name
				"dgh-wp-theme_enable_sticky_header_sm",		//name of an option to sanitize and save
				array('default' => 0,)		//Data used to describe the setting when registered
			);

			// Option to enable the title attribute on card elements
			register_setting(
				"dgh-wp-theme_options",		//settings group name
				"dgh-wp-theme_enable_card_title_attribute",		//name of an option to sanitize and save
				array('default' => 0,)		//Data used to describe the setting when registered
			);

		}

    /**
    * Callback function for hook admin_menu
    */
    static function dgh_wp_theme_admin_menu_item() {
							
			// Add a submenu page to the UW theme's main menu item.
			add_submenu_page(
				'uw-theme-settings',		//The slug name for the parent menu (or the file name of a standard WordPress admin page).
				__('Global Health Theme Settings', 'dgh-wp-theme'),	//The text to be displayed in the title tags of the page when the menu is selected.
				__('Global Health Theme Settings', 'dgh-wp-theme'),	//The text to be used for the menu.
				'manage_options',				//The capability required for this menu to be displayed to the user.
				'dgh-wp-theme-options',	//The slug name to refer to this menu by.
				array( __CLASS__, 'dgh_wp_theme_options_page' )	//The function to be called to output the content for this page.
			);

    }
		
		/**
		 * Callback function for settings section dgh-wp-theme-options-global output
		 */
		static function dgh_wp_theme_options_section_global( $args ) {
			?>
			<p><?php _e('Here you can manage global options for the theme.', 'dgh-wp-theme'); ?></p>
			<?php
		}

		/**
		 * Callback function for settings section dgh-wp-theme-options-cards output
		 */
		static function dgh_wp_theme_options_section_cards( $args ) {
			?>
			<p><?php _e('Here you can manage options for the Cards.', 'dgh-wp-theme'); ?></p>
			<?php
		}

		/**
		 * Callback function for setting dgh-wp-theme_enable_sticky_header output
		 */
		static function dgh_wp_theme_enable_sticky_header_callback( $args ) {
			?>
				<input type="<?php echo esc_attr( $args['type'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>"  name="<?php echo esc_attr( $args['name'] ); ?>"  value="1" <?php checked(1, get_option('dgh-wp-theme_enable_sticky_header'), true); ?> />
				<p class="description"><?php echo esc_attr( $args['description'] ); ?></p>
			<?php
		}

		/**
		 * Callback function for setting dgh-wp-theme_enable_sticky_header_sm output
		 */
		static function dgh_wp_theme_enable_sticky_header_sm_callback( $args ) {
			?>
				<input type="<?php echo esc_attr( $args['type'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>"  name="<?php echo esc_attr( $args['name'] ); ?>"  value="1" <?php checked(1, get_option('dgh-wp-theme_enable_sticky_header_sm'), true); ?> />
				<p class="description"><?php echo esc_attr( $args['description'] ); ?></p>
			<?php
		}

		/**
		 * Callback function for setting dgh-wp-theme_enable_card_title_attribute output
		 */
		static function dgh_wp_theme_enable_card_title_attribute_callback( $args ) {
			?>
				<input type="<?php echo esc_attr( $args['type'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>"  name="<?php echo esc_attr( $args['name'] ); ?>"  value="1" <?php checked(1, get_option('dgh-wp-theme_enable_card_title_attribute'), true); ?> />
				<p class="description"><?php echo esc_attr( $args['description'] ); ?></p>
			<?php
		}

    /**
    * Callback function to create the output for the options page.
    */
    static function dgh_wp_theme_options_page() {
			
			if ( !current_user_can('manage_options') )
				return;

			?>
			<div class="wrap">
				<h1><?php _e('Global Health Theme Options','dgh-wp-theme');?></h1>
				<?php settings_errors(); ?>
				<form action="options.php" method="post" id="dgh-wp-theme-options-form">
					<?php 
					settings_fields('dgh-wp-theme_options');
					do_settings_sections( 'dgh-wp-theme-options' );	//The slug name of the page
					submit_button( __('Save Settings', 'dgh-wp-theme') ); 
					?>
				</form>
			</div>
			<?php 
		}

		/**
		 * Callback for hook wp_ajax_{action}
		 */
		static function enable_card_title_attribute_ajax_callback() {

			echo get_option('dgh-wp-theme_enable_card_title_attribute');
			
			// Don't forget to stop execution afterward.
			wp_die();
		}


	}
	
  new DGH_WP_Theme_Options;

}