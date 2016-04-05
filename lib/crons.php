<?php
/**
 * Set up and load our class.
 */
class StickyPostCleanup_Crons
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'sticky_cleanup',               array( $this, 'sticky_cleanup_cron' )           );
	}

	/**
	 * The actual function to run the cleanup with.
	 *
	 * @return void
	 */
	public function sticky_cleanup_cron() {

	}

	// End the class.
}

// Instantiate our class
$StickyPostCleanup_Crons = new StickyPostCleanup_Crons();
$StickyPostCleanup_Crons->init();


