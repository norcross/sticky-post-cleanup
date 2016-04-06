<?php
/**
 * Set up and load our class.
 */
class STKCL_Crons
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
$STKCL_Crons = new STKCL_Crons();
$STKCL_Crons->init();


