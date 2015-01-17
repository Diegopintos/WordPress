<?php
/**
 * @wordpress-plugin
 * Plugin Name: WPMovieLibrary-Details-Standard
 * Plugin URI:  https://github.com/lesurfeur/WordPress/tree/master/Plugin/wpmovielibrary-details-standard
 * Description: Add Details Standard support to WPMovieLibrary.
 * Version:     1.0
 * Author:      lesurfeur
 * Author URI:  https://github.com/lesurfeur
 * License:     GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * GitHub Plugin URI: https://github.com/lesurfeur/WordPress/tree/master/Plugin/wpmovielibrary-details-standard
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WPMOLY_DETAILS_STANDARD_NAME',                   'WPMovieLibrary-Details-Standard' );
define( 'WPMOLY_DETAILS_STANDARD_VERSION',                '1.0' );
define( 'WPMOLY_DETAILS_STANDARD_SLUG',                   'wpmoly-details-standard' );
define( 'WPMOLY_DETAILS_STANDARD_URL',                    plugins_url( basename( __DIR__ ) ) );
define( 'WPMOLY_DETAILS_STANDARD_PATH',                   plugin_dir_path( __FILE__ ) );
define( 'WPMOLY_DETAILS_STANDARD_REQUIRED_PHP_VERSION',   '5.4' );
define( 'WPMOLY_DETAILS_STANDARD_REQUIRED_WP_VERSION',    '4.0' );


/**
 * Determine whether WPMOLY is active or not.
 *
 * @since    1.0
 *
 * @return   boolean
 */
if ( ! function_exists( 'is_wpmoly_active' ) ) :
	function is_wpmoly_active() {

		return defined( 'WPMOLY_VERSION' );
	}
endif;

/**
 * Checks if the system requirements are met
 * 
 * @since    1.0
 * 
 * @return   bool    True if system requirements are met, false if not
 */
function wpmoly_details_standard_requirements_met() {

	global $wp_version;

	if ( version_compare( PHP_VERSION, WPMOLY_DETAILS_STANDARD_REQUIRED_PHP_VERSION, '<' ) )
		return false;

	if ( version_compare( $wp_version, WPMOLY_DETAILS_STANDARD_REQUIRED_WP_VERSION, '<' ) )
		return false;

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 * 
 * @since    1.0
 */
function wpmoly_details_standard_requirements_error() {
	global $wp_version;

	require_once WPMOLY_DETAILS_STANDARD_PATH . '/views/requirements-error.php';
}

/**
 * Prints an error that the system requirements weren't met.
 * 
 * @since    1.0
 */
function wpmoly_details_standard_l10n() {

	$domain = 'wpmovielibrary-details-standard';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_textdomain( $domain, WPMOLY_DETAILS_STANDARD_PATH . 'languages/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, basename( __DIR__ ) . '/languages/' );
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the
 * plugin requirements are met. Otherwise older PHP installations could crash
 * when trying to parse it.
 */
if ( wpmoly_details_standard_requirements_met() ) {

	require_once( WPMOLY_DETAILS_STANDARD_PATH . 'includes/class-module.php' );
	require_once( WPMOLY_DETAILS_STANDARD_PATH . 'class-wpmoly-details-standard.php' );

	if ( class_exists( 'WPMovieLibrary_Details_Standard' ) ) {
		$GLOBALS['wpmoly_details_standard'] = new WPMovieLibrary_Details_Standard();
		register_activation_hook(   __FILE__, array( $GLOBALS['wpmoly_details_standard'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['wpmoly_details_standard'], 'deactivate' ) );
	}
}
else {
	wpmoly_details_standard_requirements_error();
}
