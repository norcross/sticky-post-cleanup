<?php
/**
 * Plugin Name: Sticky Post Cleanup
 * Plugin URI: https://github.com/norcross/sticky-post-cleanup
 * Description: Set an automatic expiration length to sticky posts.
 * Author: Andrew Norcross
 * Author URI: http://reaktivstudios.com/
 * Version: 0.0.1
 * Text Domain: sticky-post-cleanup
 * Domain Path: languages
 * License: MIT
 * GitHub Plugin URI: https://github.com/norcross/sticky-post-cleanup
 */

// Set my base for the plugin.
if ( ! defined( 'STKCL_BASE' ) ) {
	define( 'STKCL_BASE', plugin_basename( __FILE__ ) );
}

// Set my directory for the plugin.
if ( ! defined( 'STKCL_DIR' ) ) {
	define( 'STKCL_DIR', plugin_dir_path( __FILE__ ) );
}

// Set my version for the plugin.
if ( ! defined( 'STKCL_VER' ) ) {
	define( 'STKCL_VER', '0.0.1' );
}

/**
 * Set up and load our class.
 */
class StickyPostCleanup
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'plugins_loaded',               array( $this, 'textdomain'          )           );
		add_action( 'plugins_loaded',               array( $this, 'load_files'          )           );

		// handle the scheduling and removal of cron jobs.
		add_action( 'plugins_loaded',               array( $this, 'schedule_crons'      )           );
		register_deactivation_hook      ( __FILE__, array( $this, 'remove_crons'        )           );
	}

	/**
	 * Load textdomain for international goodness.
	 *
	 * @return textdomain
	 */
	public function textdomain() {
		load_plugin_textdomain( 'sticky-post-cleanup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Call our files in the appropriate place.
	 *
	 * @return void
	 */
	public function load_files() {

		// Load our back end.
		if ( is_admin() ) {
			require_once( 'lib/admin.php' );
		}

		// Load our cron file.
		require_once( 'lib/cron.php' );
	}

	/**
	 * Add our scheduled cron jobs.
	 *
	 * @return void
	 */
	public function schedule_crons() {

		// Set our scheduled cron job if it isn't there already.
		if ( ! wp_next_scheduled( 'sticky_cleanup' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'sticky_cleanup' );
		}
	}

	/**
	 * Remove the cron jobs on deactivation.
	 *
	 * @return void
	 */
	public function remove_crons() {

		// Fetch the timestamp.
		$stamp  = wp_next_scheduled( 'sticky_cleanup' );

		// Remove the jobs.
		wp_unschedule_event( $stamp, 'sticky_cleanup', array() );
	}

	/**
	 * Fetch an option from the database with a default fallback.
	 *
	 * @param  string $key      The option key.
	 * @param  string $default  A default value.
	 *
	 * @return mixed  $option   Either the found info, or false.
	 */
	public function get_single_option( $key = '', $default = '' ) {

		// Bail without a key.
		if ( empty( $key ) ) {
			return false;
		}

		// Fetch the option.
		$option = get_option( $key, $default );

		// Bail if no option is found, and no default was set.
		if ( empty( $option ) && empty( $default ) ) {
			return false;
		}

		// Return whichever one we have.
		return ! empty( $option ) ? $option : $default;
	}

	// End the class.
}

// Instantiate our class
$StickyPostCleanup = new StickyPostCleanup();
$StickyPostCleanup->init();


