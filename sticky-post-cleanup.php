<?php
/**
 * Plugin Name: Sticky Post Cleanup
 * Plugin URI: https://github.com/norcross/sticky-post-cleanup
 * Description: Set an automatic expiration date to sticky posts.
 * Author: Andrew Norcross
 * Author URI: http://reaktivstudios.com/
 * Version: 0.0.2
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
	define( 'STKCL_VER', '0.0.2' );
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

		// Load our data file.
		require_once( 'lib/data.php' );
	}

	/**
	 * Add our scheduled cron jobs.
	 *
	 * @return void
	 */
	public function schedule_crons() {

		// Set my frequency.
		$frequency  = apply_filters( 'stickypost_cleanup_frequency', 'twicedaily' );

		// Set our scheduled cron job if it isn't there already.
		if ( ! wp_next_scheduled( 'sticky_cleanup' ) ) {
			wp_schedule_event( time(), $frequency, 'sticky_cleanup' );
		}
	}

	/**
	 * Remove the cron jobs on deactivation.
	 *
	 * @return void
	 */
	public function remove_crons() {

		// Delete our stored options.
		delete_option( 'stickyclean' );
		delete_option( 'stickyclean_list' );

		// Fetch the timestamp.
		$stamp  = wp_next_scheduled( 'sticky_cleanup' );

		// Remove the jobs.
		wp_unschedule_event( $stamp, 'sticky_cleanup', array() );
	}

	// End the class.
}

// Instantiate our class
$StickyPostCleanup = new StickyPostCleanup();
$StickyPostCleanup->init();


