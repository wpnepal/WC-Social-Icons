<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Plugin Name: WC Social Icons
 * Plugin URI:  https://nepal.wordcamp.org/2015/
 * Description: WC Social Icons is a simple plugin to add site owner's social profiles such as Facebook, Twitter, LinkedIn etc. in a WordPress site.
 * Version:     1.0.0
 * Author:      WordCamp 2015
 * Author URI:  https://nepal.wordcamp.org/2015/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: wc-social-icons
 */

/**
 * Necessary Constants for plugin
 */

// Plugin version which we will use in the plugin js and css.
defined( 'WCSI_VERSION' ) or define( 'WCSI_VERSION', '1.0.0' ); 

// Plugin image directory
defined( 'WCSI_IMG_DIR' ) or define( 'WCSI_IMG_DIR', plugin_dir_url( __FILE__ ) . 'images' ); 

// Plugin JS directory.
defined( 'WCSI_JS_DIR' ) or define( 'WCSI_JS_DIR', plugin_dir_url( __FILE__ ) . 'js' );  

// Plugin CSS directory.
defined( 'WCSI_CSS_DIR' ) or define( 'WCSI_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' ); 

// Plugin path which can be used while including other files
defined( 'WCSI_PATH' ) or define( 'WCSI_PATH', plugin_dir_path( __FILE__ ) ); 

/**
 * Including Widget Class
 */
include_once( WCSI_PATH . '/inc/backend/widgets.php' );

/**
 * Plugin's main class
 */
if ( !class_exists( 'WCSI_Class' ) ) {

	class WCSI_Class {

		var $wcsi_settings;

		function __construct() {

			// Adding this into the attribute of our class so that we don't need to fetch the settings from database time and again.
			$this->wcsi_setings = get_option( 'wcsi_settings' ); 
			
			// Executes when init hook is fired.
			add_action( 'init', array( $this, 'wcsi_init' ) ); 
			
			// Executes when plugin is activated.
			register_activation_hook( __FILE__, array( $this, 'plugin_activation_tasks' ) ); 
			
			// Adds plugin menu in WordPress backend.
			add_action( 'admin_menu', array( $this, 'wcsi_menu' ) ); 
			
			// Registers admin assets such as JS and CSS.
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );
			
			// Registers frontend assets such as JS and CSS.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) ); 
			
			// Form action.
			add_action( 'admin_post_wcsi_save_settings', array( $this, 'save_settings' ) ); 
			
			// Restore plugin settings.
			add_action( 'admin_post_wcsi_restore_settings', array( $this, 'restore_settings' ) ); 
			
			// Plugin's shortcode register.
			add_shortcode( 'wc-social-icons', array( $this, 'wc_shortcode' ) ); 
			
			// Register wcsi widget.
			add_action( 'widgets_init', array( $this, 'register_wcsi_widget' ) ); 
		
		}

		/**
		 * Tasks to be done in init hook 
		 * Loads plugin for translation
		 * Starts session
		 */
		function wcsi_init() {

			// Loads plugin text domain for the translation.
			load_plugin_textdomain( 'wc-social-icons', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
			
			if ( !session_id() && !headers_sent() ) {
				
				// Starts session if already not started.
				session_start(); 
			
			}
			
			// Addition of plugin's custom hook.			
			do_action( 'wcsi_init' );

		}

		/**
		 * All the tasks that are to be done on plugin activation.
		 */
		function plugin_activation_tasks() {

			$default_settings = $this->get_default_settings();

			if ( !get_option( 'wcsi_settings' ) ) {

				update_option( 'wcsi_settings', $default_settings );

			}

		}

		/**
		 * Adds Plugin menu in WordPress backend.
		 */
		function wcsi_menu() {

			add_menu_page( __( 'Social Icons', 'wc-social-icons' ), __( 'Social Icons', 'wc-social-icons' ), 'manage_options', 'wc-social-icons', array( $this, 'plugin_main_page' ), 'dashicons-share' );

		}

		/**
		 * Plugin's main page.
		 */
		function plugin_main_page() {

			include( WCSI_PATH . '/inc/backend/settings.php' );

		}

		/**
		 * Registers admin assets.
		 */
		function register_admin_assets() {

			// Always load your plugin's css and js only where it is needed to prevent it from loading in unnecessary pages and slowing backend.
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'wc-social-icons' ) {

				// Backend styles
				wp_enqueue_style( 'wcsi-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), WCSI_VERSION );

				wp_enqueue_style( 'wcsi-css', WCSI_CSS_DIR . '/backend.css', array(), WCSI_VERSION );

				// Backend script
				wp_enqueue_script( 'jquery-ui-sortable' );

				wp_enqueue_script( 'wcsi-js', WCSI_JS_DIR . '/backend.js', array( 'jquery', 'jquery-ui-sortable' ), WCSI_VERSION );
			}

		}

		/**
		 * Registers front assets
		 */
		function register_frontend_assets() {

			wp_enqueue_style( 'wcsi-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), WCSI_VERSION );

			wp_enqueue_style( 'wcsi-css', WCSI_CSS_DIR . '/frontend.css', array(), WCSI_VERSION );

		}

		/**
		 * Returns default settings array
		 * @return array
		 */
		function get_default_settings() {

			$default_settings = array( 
				'icons_order' => array( 'facebook', 'twitter', 'google-plus', 'instagram', 'linkedin' ),
				'icons' => array( 'facebook' => array( 'status' => '0', 'url' => '' ),
								  'twitter' => array( 'status' => '0', 'url' => '' ),
								  'google-plus' => array( 'status' => '0', 'url' => '' ),
								  'instagram' => array( 'status' => '0', 'url' => '' ),
								  'linkedin' => array( 'status' => '0', 'url' => '' ),
				)
			);

			return $default_settings;

		}

		/**
		 * Prints array in pre format
		 */
		function print_array( $array ) {

			echo "<pre>";

			print_r( $array );

			echo "</pre>";

		}

		/**
		 * Saves plugin settings.
		 */
		function save_settings() {

			if ( !empty( $_POST ) && wp_verify_nonce( $_POST['wcsi_nonce_field'], 'wcsi_nonce' ) ) {

				include( WCSI_PATH . '/inc/backend/save-settings.php' );

			} else {

				die( 'No script kiddies please' );

			}

		}

		/**
		 * Restores default settings.
		 */
		function restore_settings() {

			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wpsi-restore-nonce' ) ) {

				$default_settings = $this->get_default_settings();

				update_option( 'wcsi_settings', $default_settings );

				$_SESSION['wcsi_message'] = __( 'Settings retored successfully', 'wc-social-icons' );

				wp_redirect( admin_url( 'admin.php?page=wc-social-icons' ) );

				exit;

			} else {

				die( 'No script kiddies please' );

			}

		}

		/**
		 * Plugin's shortcode.
		 */
		function wc_shortcode() {

			ob_start();

			include( WCSI_PATH . '/inc/frontend/shortcode.php' );

			$social_icons_html = ob_get_contents();

			ob_clean();

			return $social_icons_html;

		}
		
		/**
		 * Registers Plugin's widget.
		 */
		function register_wcsi_widget(){

			register_widget( 'WCSI_Widget' );

		}

	} # END class WCSI_Class

	$wcsi_obj = new WCSI_Class();

}