<?php
/**
 * Plugin Name: Sticky Post Cleanup
 * Plugin URI: https://github.com/norcross/sticky-post-cleanup
 * Description: Set an length of time to keep posts sticky.
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