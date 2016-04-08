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

		// Get our sticky post data, and return if we have none.
		if ( false === $items = STKCL_Data::get_sticky_post_data() ) {
			return;
		}

		// Get my amount of days to check against.
		$check  = STKCL_Data::get_single_option( 'stickyclean', 30, 'range' );

		// Get my current time and calculate my offset.
		$now	= current_time( 'timestamp' );
		$offset = absint( $now ) - ( DAY_IN_SECONDS * absint( $check ) );

		// Now loop my items and run the comparison on the IDs.
		foreach ( $items as $id => $stamp ) {

			// If we are past the range, remove it from the array.
			if ( $stamp < $offset ) {
				unset( $items[ $id ] );
			}
		}

		// Set an variable for our updated key.
		$data   = ! empty( $items ) ? array_keys( $items ) : array();

		// Update our array.
		update_option( 'sticky_posts', $data );

		// And return.
		return;
	}

	// End the class.
}

// Instantiate our class
$STKCL_Crons = new STKCL_Crons();
$STKCL_Crons->init();


