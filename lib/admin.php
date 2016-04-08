<?php
/**
 * Set up and load our class.
 */
class STKCL_Admin
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init',                   array( $this, 'do_sticky_clear'     )           );
		add_action( 'admin_init',                   array( $this, 'load_settings'       )           );
		add_action( 'save_post',                    array( $this, 'check_sticky_limit'  ),  9999    );
	}

	/**
	 * Look for our option key and clean up the sticky posts.
	 *
	 * @return void.
	 */
	public function do_sticky_clear() {

		// Get our data to run.
		if ( false !== $list = STKCL_Data::get_single_option( 'stickyclean_list' ) ) {

			// Update the option.
			update_option( 'sticky_posts', maybe_unserialize( $list ) );

			// And delete the key.
			delete_option( 'stickyclean_list' );
		}
	}

	/**
	 * Register our new settings and load our settings fields.
	 *
	 * @return void
	 */
	public function load_settings() {

		// Add our setting for the amount of days to keep a sticky, and the total number of them.
		register_setting( 'writing', 'stickyclean', array( $this, 'stickyclean_data_sanitize' ) );

		// And create our settings section.
		add_settings_section( 'sticky-posts', __( 'Sticky Posts', 'sticky-post-cleanup' ), array( $this, 'stickyclean_settings' ), 'writing' );
	}

	/**
	 * Our settings section.
	 *
	 * @return HTML  The data fields.
	 */
	public function stickyclean_settings( $args ) {

		// Get our actual settings.
		$data   = STKCL_Data::get_single_option( 'stickyclean' );

		// Parse our each piece.
		$range  = ! empty( $data['range'] ) ? $data['range'] : 30;
		$total  = ! empty( $data['total'] ) ? $data['total'] : 0;

		// Get our total number of current sticky posts.
		$sticks = STKCL_Data::count_sticky_posts();

		// Add our intro content.
		echo '<p>' . __( 'Set the amount of days a post can stay "sticky", and also the total amount of allowed sticky posts.', 'sticky-post-cleanup' ) . ' ' . sprintf( _n( 'You currently have <strong>%d</strong> sticky post.', 'You currently have <strong>%d</strong> sticky posts.', $sticks, 'sticky-post-cleanup' ), $sticks ) . '</p>';

		// Now set up the table with each value.
		echo '<table id="' . esc_attr( $args['id'] ) . '" class="form-table">';
		echo '<tbody>';

			// Our range field.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label for="stickyclean-range">' . __( 'Days Until Expiration', 'sticky-post-cleanup' ) . '</label>';
				echo '</th>';

				// The field input.
				echo '<td>';
					echo '<input type="number" id="stickyclean-range" class="small-text" name="stickyclean[range]" value="' . absint( $range ) . '"/>';
				echo '</td>';

			// Close our range field.
			echo '</tr>';

			// Our total allowed field.
			echo '<tr>';

				// The field label.
				echo '<th scope="row">';
					echo '<label for="stickyclean-total">' . __( 'Total Sticky Posts Allowed', 'sticky-post-cleanup' ) . '</label>';
				echo '</th>';

				// The field input.
				echo '<td>';
					echo '<input type="number" id="stickyclean-total" class="small-text" name="stickyclean[total]" value="' . absint( $total ) . '"/>';
				echo '</td>';

			// Close our total field.
			echo '</tr>';

		// Close the table.
		echo '</tbody>';
		echo '</table>';
	}

	/**
	 * Sanitize the user data inputs.
	 *
	 * @param  array $input  The data entered in a settings field.
	 *
	 * @return array $input  The sanitized data.
	 */
	public function stickyclean_data_sanitize( $input ) {

		// Make sure we have an array.
		$input  = (array) $input;

		// Loop it.
		foreach ( $input as $key => $value ) {
			$input[ $key ]  = absint( $value );
		}

		// And return our input.
		return $input;
	}

	/**
	 * Our check to run on save to make sure we are within the limit of sticky posts.
	 *
	 * @param  integer $post_id  The post ID being passed.
	 *
	 * @return void
	 */
	public function check_sticky_limit( $post_id ) {

		// Bail out if running an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Bail out if running an ajax.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Bail out if running a cron, unless we've skipped that.
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Bail if this isn't a post, since only posts can be sticky.
		if ( 'post' !== get_post_type( $post_id ) ) {
			return;
		}

		// Bail out if user does not have permission to edit posts.
		if ( ! current_user_can( apply_filters( 'stickypost_cleanup_cap', 'edit_posts' ) ) ) {
			return;
		}

		// Get my total allowed count.
		$total  = STKCL_Data::get_single_option( 'stickyclean', 0, 'total' );

		// If we are less than 1 (zero) then bail.
		if ( absint( $total ) < 1 ) {

			// Make sure we delete the key.
			delete_option( 'stickyclean_run' );

			// And return.
			return;
		}

		// Now get our sticky post data, and return if we have none.
		if ( false === $items = STKCL_Data::get_sticky_post_data() ) {

			// Make sure we delete the key.
			delete_option( 'stickyclean_run' );

			// And return.
			return;
		}

		// Set our number.
		$count  = count( $items );

		// If our total number is less that our allowed, bail.
		if ( absint( $count ) <= $total ) {

			// Make sure we delete the key.
			delete_option( 'stickyclean_run' );

			// And return.
			return;
		}

		// Pull out the IDs.
		$data   = array_keys( $items );

		// Carve out the remaining.
		$list   = array_slice( $data, 0, absint( $total ) );

		// Make an array of our stuff to run after all the post stuff is done.
		update_option( 'stickyclean_list', $list );

		// And return.
		return;
	}

	// End the class.
}

// Instantiate our class
$STKCL_Admin = new STKCL_Admin();
$STKCL_Admin->init();


