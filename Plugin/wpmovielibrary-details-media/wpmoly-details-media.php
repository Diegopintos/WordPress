<?php
/**
 * @wordpress-plugin
 * Plugin Name: WPMovieLibrary-Details-Media
 * Plugin URI:  https://github.com/lesurfeur/WordPress/tree/master/Plugin/wpmovielibrary-details-media
 * Description: Add Details Media support to WPMovieLibrary.
 * Version:     1.0
 * Author:      lesurfeur
 * Author URI:  https://github.com/lesurfeur
 * License:     GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * GitHub Plugin URI: https://github.com/lesurfeur/WordPress/tree/master/Plugin/wpmovielibrary-details-media
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WPMOLY_DETAILS_MEDIA_NAME',                   'WPMovieLibrary-Details-Media' );
define( 'WPMOLY_DETAILS_MEDIA_VERSION',                '1.0' );
define( 'WPMOLY_DETAILS_MEDIA_SLUG',                   'wpmoly-details-media' );
define( 'WPMOLY_DETAILS_MEDIA_URL',                    plugins_url( basename( __DIR__ ) ) );
define( 'WPMOLY_DETAILS_MEDIA_PATH',                   plugin_dir_path( __FILE__ ) );
define( 'WPMOLY_DETAILS_MEDIA_REQUIRED_PHP_VERSION',   '5.4' );
define( 'WPMOLY_DETAILS_MEDIA_REQUIRED_WP_VERSION',    '4.0' );


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
function wpmoly_details_media_requirements_met() {

	global $wp_version;

	if ( version_compare( PHP_VERSION, WPMOLY_DETAILS_MEDIA_REQUIRED_PHP_VERSION, '<' ) )
		return false;

	if ( version_compare( $wp_version, WPMOLY_DETAILS_MEDIA_REQUIRED_WP_VERSION, '<' ) )
		return false;

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 * 
 * @since    1.0
 */
function wpmoly_details_media_requirements_error() {
	global $wp_version;

	require_once WPMOLY_DETAILS_MEDIA_PATH . '/views/requirements-error.php';
}

/**
 * Prints an error that the system requirements weren't met.
 * 
 * @since    1.0
 */
function wpmoly_details_media_l10n() {

	$domain = 'wpmovielibrary-details-media';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_textdomain( $domain, WPMOLY_DETAILS_MEDIA_PATH . 'languages/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, basename( __DIR__ ) . '/languages/' );
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the
 * plugin requirements are met. Otherwise older PHP installations could crash
 * when trying to parse it.
 */
if ( wpmoly_details_media_requirements_met() ) {

	require_once( WPMOLY_DETAILS_MEDIA_PATH . 'includes/class-module.php' );
	require_once( WPMOLY_DETAILS_MEDIA_PATH . 'class-wpmoly-details-media.php' );

	if ( class_exists( 'WPMovieLibrary_Details' ) ) {
		$GLOBALS['wpmoly_details'] = new WPMovieLibrary_Details();
		register_activation_hook(   __FILE__, array( $GLOBALS['wpmoly_details'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['wpmoly_details'], 'deactivate' ) );
	}
}
else {
	wpmoly_details_media_requirements_error();
}
