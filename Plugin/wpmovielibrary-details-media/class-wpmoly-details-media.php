<?php
/**
 * WPMovieLibrary-Details-Media
 *
 * @package   WPMovieLibrary-Details-Media
 * @author    lesurfeur
 * @license   GPL-3.0
 * @link      https://github.com/lesurfeur
 * @copyright 2014 Charlie MERLAND
 */

if ( ! class_exists( 'WPMovieLibrary_Details_Media' ) ) :

	/**
	* Plugin class
	*
	* @package WPMovieLibrary-Details-Media
	* @author  Charlie MERLAND <charlie@caercam.org>
	*/
	class WPMovieLibrary_Details_Media extends WPMOLY_Details_Module {

		/**
		 * Settings for new detail
		 * 
		 * @since     1.0
		 * @var       array
		 */
		protected $detail = array();

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 * and styles.
		 *
		 * @since     1.0
		 */
		public function __construct() {

			$this->init();
		}

		/**
		 * Initializes variables
		 *
		 * @since    1.0
		 */
		public function init() {

			if ( ! wpmoly_details_media_requirements_met() ) {
				add_action( 'init', 'wpmoly_details_media_l10n' );
				add_action( 'admin_notices', 'wpmoly_details_media_requirements_error' );
				return false;
			}

			$this->register_hook_callbacks();
		}
		
		/**
		 * Register callbacks for actions and filters
		 * 
		 * @since    1.0
		 */
		public function register_hook_callbacks() {

			add_action( 'plugins_loaded', 'wpmoly_details_media_l10n' );

			add_action( 'activated_plugin', __CLASS__ . '::require_wpmoly_first' );

			// Add a new movie media
			add_filter( 'wpmoly_filter_detail_media', array( $this, 'add_movie_media' ), 10, 1 );
		}

		/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 *
		 *                     Plugin  Activate/Deactivate
		 * 
		 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		/**
		 * Make sure WPMovieLibrary is active and compatible.
		 *
		 * @since    1.0
		 * 
		 * @return   boolean    Requirements met or not?
		 */
		private function wpmoly_details_media_requirements_error() {

			$wpml_active  = is_wpmoly_active();
			$wpml_version = ( $wpml_active && version_compare( WPML_VERSION, WPMLTR_REQUIRED_WPML_VERSION, '>=' ) );

			if ( ! $wpml_active || ! $wpml_version )
				return false;

			return true;
		}

		/**
		 * Fired when the plugin is activated.
		 *
		 * @since    1.0
		 *
		 * @param    boolean    $network_wide    True if WPMU superadmin uses
		 *                                       "Network Activate" action, false if
		 *                                       WPMU is disabled or plugin is
		 *                                       activated on an individual blog.
		 */
		public function activate( $network_wide ) {

			global $wpdb;

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $network_wide ) {
					$blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

					foreach ( $blogs as $blog ) {
						switch_to_blog( $blog );
						$this->single_activate( $network_wide );
					}

					restore_current_blog();
				} else {
					$this->single_activate( $network_wide );
				}
			} else {
				$this->single_activate( $network_wide );
			}

		}

		/**
		 * Fired when the plugin is deactivated.
		 * 
		 * When deactivatin/uninstalling WPML, adopt different behaviors depending
		 * on user options. Movies and Taxonomies can be kept as they are,
		 * converted to WordPress standars or removed. Default is conserve on
		 * deactivation, convert on uninstall.
		 *
		 * @since    1.0
		 */
		public function deactivate() {
		}

		/**
		 * Runs activation code on a new WPMS site when it's created
		 *
		 * @since    1.0
		 *
		 * @param    int    $blog_id
		 */
		public function activate_new_site( $blog_id ) {
			switch_to_blog( $blog_id );
			$this->single_activate( true );
			restore_current_blog();
		}

		/**
		 * Prepares a single blog to use the plugin
		 *
		 * @since    1.0
		 *
		 * @param    bool    $network_wide
		 */
		protected function single_activate( $network_wide ) {

			self::require_wpmoly_first();
		}

		/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 *
		 *                     Scripts/Styles and Utils
		 * 
		 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		/**
		 * Register and enqueue public-facing style sheet.
		 *
		 * @since    1.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( WPMLTR_SLUG . '-css', WPMLTR_URL . '/assets/css/public.css', array(), WPMLTR_VERSION );
		}

		/**
		 * Register and enqueue public-facing style sheet.
		 *
		 * @since    1.0
		 */
		public function admin_enqueue_styles() {

			wp_enqueue_style( WPMLTR_SLUG . '-admin-css', WPMLTR_URL . '/assets/css/admin.css', array(), WPMLTR_VERSION );
		}

		/**
		 * Register and enqueue public-facing style sheet.
		 *
		 * @since    1.0
		 */
		public function admin_enqueue_scripts() {

			wp_enqueue_script( WPMLTR_SLUG . 'admin-js', WPMLTR_URL . '/assets/js/admin.js', array( WPML_SLUG ), WPMLTR_VERSION, true );
		}

		/**
		 * Make sure the plugin is load after WPMovieLibrary and not
		 * before, which would result in errors and missing files.
		 *
		 * @since    1.0
		 */
		public static function require_wpmoly_first() {

			$this_plugin_path = plugin_dir_path( __FILE__ );
			$this_plugin      = basename( $this_plugin_path ) . '/wpmoly-details-media.php';
			$active_plugins   = get_option( 'active_plugins' );
			$this_plugin_key  = array_search( $this_plugin, $active_plugins );
			$wpml_plugin_key  = array_search( 'wpmovielibrary/wpmovielibrary.php', $active_plugins );

			if ( $this_plugin_key < $wpml_plugin_key ) {

				unset( $active_plugins[ $this_plugin_key ] );
				$active_plugins = array_merge(
					array_slice( $active_plugins, 0, $wpml_plugin_key ),
					array( $this_plugin ),
					array_slice( $active_plugins, $wpml_plugin_key )
				);

				update_option( 'active_plugins', $active_plugins );
			}
		}

		/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 *
		 *                            Plugin methods
		 * 
		 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

		/**
		 * Add a new movie media
		 *
		 * @since    1.0
		 * 
		 * @param    array    Exisiting media
		 * 
		 * @return   array    Updated media
		 */
		public function add_movie_media( $media ) {

			$new_media = array( 'mkv' => __( 'MKV', 'wpmovielibrary-details' ) );

			$offset = array_search( 'divx', array_keys( $media ) ) + 1;
			$media  = array_merge(
				array_slice( $media, 0, $offset ),
				$new_media,
				array_slice( $media, $offset, null )
			);

			$media = array_merge( $media, $new_media );

			return $media;
		}

	}
endif;
