<?php
/**
 * Set up and load our class.
 */
class STKCL_Data
{
// a:5:{i:0;i:1016;i:1;i:1011;i:2;i:1000;i:3;i:996;i:4;i:993;}
	/**
	 * Fetch an option from the database with a default fallback.
	 *
	 * @param  string $key      The option key.
	 * @param  string $default  A default value.
	 * @param  string $serial   If we have a serialized data, look for one piece.
	 *
	 * @return mixed  $option   Either the found info, or false.
	 */
	public static function get_single_option( $key = '', $default = '', $serial = '' ) {

		// Bail without a key.
		if ( empty( $key ) ) {
			return false;
		}

		// Fetch the option.
		$option = get_option( $key );

		// Bail if no option is found, and no default was set.
		if ( empty( $option ) && empty( $default ) ) {
			return false;
		}

		// Handle the serial.
		if ( ! empty( $serial ) ) {
			return ! empty( $option[ $serial ] ) ? $option[ $serial ] : $default;
		}

		// Return whichever one we have.
		return ! empty( $option ) ? $option : $default;
	}

	/**
	 * Get the total number of sticky posts we have.
	 *
	 * @return integer  The total number, or zero.
	 */
	public static function count_sticky_posts() {

		// Get the data from our option table.
		$sticks = self::get_single_option( 'sticky_posts' );

		// Return the count, or zero.
		return ! empty( $sticks ) && is_array( $sticks ) ? count( $sticks ) : 0;
	}

	/**
	 * Get an array of the sticky post IDs
	 *
	 * @param  string $order  The order to return our list in. Defaults to decending.
	 * @param  bool   $list   Whether to just return the list of raw IDs.
	 *
	 * @return array  The data array (or false if none exist).
	 */
	public static function get_sticky_post_data( $order = 'DESC', $list = false ) {

		// Get the data from our option table and return false if none exist.
		if ( false === $ids = self::get_single_option( 'sticky_posts', array(), false ) ) {
			return false;
		}

		// We requested just the IDs, so return those.
		if ( false !== $list ) {
			return $ids;
		}

		// Set an empty.
		$items  = array();

		// Loop them.
		foreach ( $ids as $id ) {

			// Get my post date timestamp.
			$stamp  = get_the_date( 'U', $id );

			// Set my array up.
			$items[ $id ] = absint( $stamp );
		}

		// Sort descending (which is the default).
		if ( 'DESC' === $order ) {
			asort( $items );
		}

		// Sort them ascending.
		if ( 'ASC' === $order ) {
			arsort( $items );
		}

		// Return the array, sorted by date low to high.
		return $items;
	}

	// End the class.
}

// Instantiate our class
$STKCL_Data = new STKCL_Data();



