<?php
/**
 * WP Dependency Installer
 * @link: https://github.com/afragen/wp-dependency-installer
 */
require_once __DIR__ . '/vendor/autoload.php';
add_action( 'init', static function() {
  WP_Dependency_Installer::instance( __DIR__ )->run();
});
add_filter(
  'wp_dependency_dismiss_label',
  function( $label, $source ) {
    $label = basename(__DIR__) !== $source ? $label : __( 'Global Health Theme', 'dgh-wp-theme' );
    return $label;
  },
  10,
  2
);

/**
 * UW WP Theme Child Theme functionality
 */
require( get_stylesheet_directory() . '/inc/DGH_WP_Theme.class.php' );

/**
 * uw_wp_theme template-functions.php overrides
 */
require get_stylesheet_directory() . '/inc/template-functions.php';