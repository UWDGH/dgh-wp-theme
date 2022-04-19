<?php
/**
* Theme Class
*/

if ( !class_exists( 'UW_GlobalHealth' ) ) {

  class UW_GlobalHealth {

    /**
    * class constructor
    */
    function __construct()  {

      // add stylesheet
      add_action( 'wp_enqueue_scripts', array( __CLASS__, 'uw_globalhealth_enqueue_styles' ), 11 );

      // add menu item
      //add_action('admin_menu', array( __CLASS__, 'uw_globalhealth_admin_menu_item' ));

      // create the dgh audience menu
      self::uw_globalhealth_audience_menu();

    }

    /**
    * enqueue child stylesheet, require the uw_wp_theme bootstrap style sheet
    */
    static function uw_globalhealth_enqueue_styles() {
    	$parenthandle = 'uw_wp_theme-bootstrap';

    	wp_enqueue_style( 'uw_wp_theme-globalhealth', get_stylesheet_uri(),
            array( $parenthandle ),
            wp_get_theme()->get('Version') // this only works if you have Version in the style header
        );
    }

    /**
    * Add options page menu item to Appearance menu
    */
    function uw_globalhealth_admin_menu_item() {
        add_submenu_page('themes.php', 'UW Global Health options', 'UW Global Health options', 'manage_options', 'uw-globalhealth-theme-options', array( __CLASS__, 'uw_globalhealth_theme_options_page' ));
    }

    /**
    * Options page
    */
    function uw_globalhealth_theme_options_page() { ?>
    <div class="wrap">
        <h2><?php _e('UW Global Health theme options','uw_globalhealth');?></h2>
        <form action="options.php" method="post" id="uw-globalhealth-theme-options-form">
            <?php settings_fields('uw_globalhealth_theme_options'); ?>
            <table class="form-table">
                <tr class="even" valign="top">
                    <th scope="row">
                    </th>
                    <td>
                    </td>
                </tr>
                <tr class="odd" valign="top">
                    <th scope="row">
                    </th>
                    <td>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php }



    /**
     * Add the parent theme style.css.
     * This method is preferred over importing it into the style sheet
     */
    // function uwdgh_scripts_and_styles() {
    //     wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    //
    //     wp_register_script('uwdgh', get_stylesheet_directory_uri() . '/js/uwdgh.js', array('jquery'));
    //     wp_enqueue_script('uwdgh');
    // }



    /**
    * Builds the default audience menu for DGH.
    */
    private static function uw_globalhealth_audience_menu() {
        $run_once = get_option('menu_check');
        if (!$run_once){
            // Check if the menu exists
            $menu_name = "UW Global Health Audience Menu";
            $menu_exists = wp_get_nav_menu_object( $menu_name );
            // If it doesn't exist, let's create it.
            if( !$menu_exists){
                // Create the menu
                $menu_id = wp_create_nav_menu($menu_name);
                // Set up default menu items
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  __('Support Us', 'uw_globalhealth'),
                    'menu-item-url' => '//globalhealth.washington.edu/support-us',
                    'menu-item-attr-title' => __( 'Donate to Global Health', 'uw_globalhealth' ),
                    'menu-item-status' => 'publish'));
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  __('Contact Us', 'uw_globalhealth'),
                    'menu-item-url' => '//globalhealth.washington.edu/contact',
                    'menu-item-attr-title' => __('Contact Us', 'uw_globalhealth'),
                    'menu-item-status' => 'publish'));
            }
            // update the menu_check option to make sure this code only runs once
            update_option('menu_check', true);
        }
    }

    /**
    * Login/Logout link
    */
    public static function login_link() {
      if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        echo '<a href="' . wp_logout_url(get_permalink()) . '" title="' . __('Log out', 'uw_globalhealth') . '">' . __('Log out', 'uw_globalhealth') . '&hellip;&nbsp;<em>' . $current_user->user_login . '</em></a>';
      } else {
        echo '<a href="' . wp_login_url(get_permalink()) . '" title="' . __('Log in', 'uw_globalhealth') . '">' . __('Log in', 'uw_globalhealth') . '</a>';
      }
    }

  }

  new UW_GlobalHealth;
}
