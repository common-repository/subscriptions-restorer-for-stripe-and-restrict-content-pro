<?php
/**
 * @link              https://wpspins.com
 * @since             1.0.0
 * @package           Restore_Cancelled_Stripe_Subscriptions
 *
 * @wordpress-plugin
 * Plugin Name:       Subscriptions Restorer for Stripe and Restrict Content Pro
 * Plugin URI:        https://wpspins.com?utm-ref=subscriptions-restorer-for-stripe-and-restrict-content-pro
 * Description:       Restore cancelled stripe subscriptions using stripe API for Restrict content.
 * Version:           1.0.0
 * Author:            WPSPIN LLC
 * Author URI:        https://wpspins.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpsinrcss
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RCP restore cencelled stripe subscriptions.
 *
 * @return void
 */
function rcp_restore_cancelled_subscriptions() {
	add_options_page( 'RC Restore Cencelled Stripe Subscriptions', 'RC Restore Cencelled Stripe Subscriptions', 'manage_options', 'rcp_restore', 'rcp_restore_cancelled_subscriptions_page' );
}
add_action( 'admin_menu', 'rcp_restore_cancelled_subscriptions' );

/**
 * RCP restore cencelled stripe subscriptions page.
 * Includes stripe token field and button.
 *
 * @return void
 */
function rcp_restore_cancelled_subscriptions_page() {
	?>
	<div class="wrap">
		<form method="post" action="options.php">
			<?php
			settings_fields( 'rcp_restore_options' );
			do_settings_sections( 'rcp_restore' );
			submit_button();
			?>
		</form>
		<!-- show number of cancelled subscriptions -->
		<?php
		// stripe API only get all cancelled subscriptions by webhook.
		$url      = 'https://api.stripe.com/v1/subscriptions?status=canceled';
		$options  = get_option( 'wpsinrcss' );
		$token    = isset( $options['stripe-token'] ) ? $options['stripe-token'] : '';
		$args     = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
			),
		);
		$response = wp_remote_get( $url, $args );
		$body     = wp_remote_retrieve_body( $response );
		$body     = json_decode( $body );
		if ( ! $body ) {
			esc_html_e( 'Stripe token is not valid.', 'wpsinrcss' );
			return;
		}
		if ( ! isset( $body->data ) ) {
			esc_html_e( 'Stripe token is not valid.', 'wpsinrcss' );
			return;
		}
		$subscriptions       = $body->data;
		$subscriptions_count = count( $subscriptions );
		if ( 0 === $subscriptions_count ) {
			esc_html_e( 'There are no cancelled subscriptions.', 'wpsinrcss' );
			return;
		}
		esc_html_e( 'Number of cancelled subscriptions: ', 'wpsinrcss' );
		echo intval( $subscriptions_count );
		?>
		<!-- show table of cancelled subscriptions -->
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1">
							<?php esc_html_e( 'Select All', 'wpsinrcss' ); ?>
						</label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<th>
						<?php esc_html_e( 'Subscription ID', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Customer ID', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Customer email', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Customer name', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan ID', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan name', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan amount', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan currency', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan interval', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan interval count', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan trial period days', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan canceled at', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan created', 'wpsinrcss' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Plan status', 'wpsinrcss' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $subscriptions as $subscription ) {
					$subscription_id        = $subscription->id;
					$customer_id            = $subscription->customer;
					$plan_id                = $subscription->plan->id;
					$plan_name              = $subscription->plan->nickname;
					$plan_amount            = $subscription->plan->amount;
					$plan_currency          = $subscription->plan->currency;
					$plan_interval          = $subscription->plan->interval;
					$plan_interval_count    = $subscription->plan->interval_count;
					$plan_trial_period_days = $subscription->plan->trial_period_days;
					$plan_canceled_at       = $subscription->canceled_at;
					$plan_created           = $subscription->created;
					$plan_status            = $subscription->status;
					$metadata               = $subscription->metadata;
					// get metadata.
					$rcp_membership_id         = isset( $metadata->rcp_membership_id ) ? $metadata->rcp_membership_id : '';
					$rcp_initial_payment_id    = isset( $metadata->rcp_initial_payment_id ) ? $metadata->rcp_initial_payment_id : '';
					$rcp_customer_id           = isset( $metadata->rcp_customer_id ) ? $metadata->rcp_customer_id : '';
					$rcp_subscription_level_id = isset( $metadata->rcp_subscription_level_id ) ? $metadata->rcp_subscription_level_id : '';
					$rcp_member_id             = isset( $metadata->rcp_member_id ) ? $metadata->rcp_member_id : '';
					// get customer email and name.
					$url            = 'https://api.stripe.com/v1/customers/' . $customer_id;
					$args           = array(
						'headers' => array(
							'Authorization' => 'Bearer ' . $token,
						),
					);
					$response       = wp_remote_get( $url, $args );
					$body           = wp_remote_retrieve_body( $response );
					$body           = json_decode( $body );
					$customer       = $body;
					$customer_email = isset( $customer->email ) ? $customer->email : '';
					$customer_name  = isset( $customer->name ) ? $customer->name : '';
					$plan_status    = get_post_meta( $rcp_membership_id, 'is_restored', true ) ? 'restored' : $plan_status;
					?>
					<tr>
						<!-- bulk action checkbox -->
						<td>
							<label class="screen-reader-text" for="cb-select-<?php echo esc_attr( $subscription_id ); ?>">
								<?php esc_html_e( 'Select subscription', 'wpsinrcss' ); ?>
								<?php echo esc_html( $subscription_id ); ?>
							</label>
							<input id="cb-select-<?php echo esc_attr( $subscription_id ); ?>" type="checkbox" name="subscriptions[]" value="<?php echo esc_html( $subscription_id ); ?>">
							<div class="locked-indicator"></div>
						</td>
						<td><?php echo esc_html( $subscription_id ); ?></td>
						<td><?php echo esc_html( $customer_id ); ?></td>
						<td><?php echo esc_html( $customer_email ); ?></td>
						<td><?php echo esc_html( $customer_name ); ?></td>
						<td><?php echo esc_html( $plan_id ); ?></td>
						<td><?php echo esc_html( $plan_name ); ?></td>
						<td><?php echo esc_html( $plan_amount ); ?></td>
						<td><?php echo esc_html( $plan_currency ); ?></td>
						<td><?php echo esc_html( $plan_interval ); ?></td>
						<td><?php echo esc_html( $plan_interval_count ); ?></td>
						<td><?php echo esc_html( $plan_trial_period_days ); ?></td>
						<td><?php echo esc_html( get_date_from_gmt( gmdate( 'Y-m-d H:i:s', $plan_canceled_at ) ) ); ?></td>
						<td><?php echo esc_html( get_date_from_gmt( gmdate( 'Y-m-d H:i:s', $plan_created ) ) ); ?></td>
						<td style="text-transform: capitalize;">
							<?php if ( 'restored' === $plan_status ) { ?>
								<!-- check mark -->
								<span class="dashicons dashicons-yes" style="color:green"></span>
							<?php } ?>
							<?php echo esc_html( $plan_status ); ?>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<!-- bulk restore button -->
		<div class="tablenav bottom">
			<div class="alignleft actions bulkactions">
				<label for="bulk-action-selector-bottom" class="screen-reader-text">
					<?php esc_html_e( 'Select bulk action', 'wpsinrcss' ); ?>
				</label>
				<select name="action2" id="bulk-action-selector-bottom">
					<option value="-1">
						<?php esc_html_e( 'Bulk Actions', 'wpsinrcss' ); ?>
					</option>
					<option value="restore">
						<?php esc_html_e( 'Restore', 'wpsinrcss' ); ?>
					</option>
				</select>
				<?php wp_nonce_field( 'wpsinrcss-bulk-action', 'wpsinrcss-bulk-action-nonce' ); ?>
				<input type="submit" id="doaction2" class="button action" value="Apply">
			</div>
		</div>
	</div>
	<?php
}

/**
 * Restore cancelled subscriptions bulk action js.
 *
 * @return void
 */
function rcp_restore_cancelled_subscriptions_bulk_action_js() {
	wp_enqueue_script( 'wpsinrcss-admin', plugin_dir_url( __FILE__ ) . 'assets/js/wpsinrcss-admin.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'rcp_restore_cancelled_subscriptions_bulk_action_js' );


/**
 * RCP restore cencelled stripe subscriptions settings.
 *
 * @return void
 */
function rcp_restore_cancelled_subscriptions_settings() {
	register_setting( 'rcp_restore_options', 'wpsinrcss' );

	add_settings_section(
		'rcp_restore_section',
		__( 'RCP Restore Cancelled Stripe Subscriptions', 'wpsinrcss' ),
		'rcp_restore_cancelled_subscriptions_settings_section',
		'rcp_restore'
	);

	add_settings_field(
		'rcp_restore_stripe_token',
		__( 'Stripe Token', 'wpsinrcss' ),
		'rcp_restore_cancelled_subscriptions_settings_stripe_token',
		'rcp_restore',
		'rcp_restore_section'
	);
}
add_action( 'admin_init', 'rcp_restore_cancelled_subscriptions_settings' );

/**
 * RCP restore cencelled stripe subscriptions settings section.
 *
 * @return void
 */
function rcp_restore_cancelled_subscriptions_settings_section() {
	echo '<p>' . esc_html__( 'Enter stripe token.', 'wpsinrcss' ) . '</p>';
}
/**
 * RCP restore cencelled stripe subscriptions settings stripe token.
 *
 * @return void
 */
function rcp_restore_cancelled_subscriptions_settings_stripe_token() {
	$options = get_option( 'wpsinrcss' );
	?>
	<input type="text" name="wpsinrcss[stripe-token]" value="<?php echo isset( $options['stripe-token'] ) ? esc_attr( $options['stripe-token'] ) : ''; ?>" />
	<?php
}


/**
 * RCP restore cencelled stripe subscriptions ajax.
 *
 * @return void
 */
function rcp_restore_cancelled_subscriptions_ajax() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wpsinrcss-bulk-action' ) ) {
		wp_send_json_error( 'Nonce is not set!' );
	}
	if ( ! isset( $_POST['subscriptions'] ) ) {
		wp_send_json_error( 'Subscriptions not set!' );
	}
	$subscriptions = ! empty( $_POST['subscriptions'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['subscriptions'] ) ) : array();
	if ( ! $subscriptions ) {
		wp_send_json_error( 'subscriptions not set' );
	}
	global $wpdb;
	$table_name  = rcp_get_memberships_db_name();
	$table_name  = esc_sql( $table_name );
	$res         = $wpdb->prepare( "SELECT * FROM {$table_name} WHERE `gateway`='stripe' AND `gateway_subscription_id` IN (%s)", implode( "','", $subscriptions ) );
	$memberships = $wpdb->get_results( $res, ARRAY_A );
	if ( ! $memberships ) {
		wp_send_json_error( 'No memberships found!' );
	}
	foreach ( $memberships as $membership ) {
		$prev_subscription_id = $membership['gateway_subscription_id'];
		$url                  = 'https://api.stripe.com/v1/subscriptions/' . $prev_subscription_id;
		$brearer_token        = get_option( 'wpsinrcss' )['stripe-token'];
		$args                 = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $brearer_token,
			),
		);
		// remote get.
		$res  = wp_remote_get( $url, $args );
		$body = wp_remote_retrieve_body( $res );
		// decode body.
		$old_subscription_info     = json_decode( $body, true );
		$plan_id                   = $old_subscription_info['items']['data'][0]['plan']['id'];
		$default_payment_method    = $old_subscription_info['default_payment_method'];
		$prev_expiration_date      = $membership['expiration_date'];
		$prev_expiration_timestamp = strtotime( $prev_expiration_date );
		// new subscription.
		$trial_end = $prev_expiration_timestamp;
		$metadata  = $old_subscription_info['metadata'];
		$customer  = $old_subscription_info['customer'];
		// ags.
		$args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $brearer_token,
			),
			'body' => array(
				'proration_behavior'     => 'none',
				'plan'                   => $plan_id,
				'default_payment_method' => $default_payment_method,
				'trial_end'              => $trial_end,
				'metadata'               => $metadata,
				'customer'               => $customer,
			),
		);
		// res.
		$res                   = wp_remote_post( 'https://api.stripe.com/v1/subscriptions', $args );
		$body                  = wp_remote_retrieve_body( $res );
		$new_subscription_info = json_decode( $body, true );
		$new_subscription_id   = $new_subscription_info['id'];
		rcp_update_membership(
			$membership['id'],
			array(
				'gateway_subscription_id' => $new_subscription_id,
				'auto_renew'              => true,
				'status'                  => 'active',
			)
		);
		// update.
		$wpdb->update( //phpcs:ignore
			$table_name,
			array(
				'cancellation_date' => null,
			),
			array(
				'id' => $membership['id'],
			)
		);
		update_post_meta( $membership['id'], 'is_restored', true );
	}
	wp_send_json_success( count( $subscriptions ) );
}
add_action( 'wp_ajax_rcp_restore_cancelled_subscriptions', 'rcp_restore_cancelled_subscriptions_ajax' );
add_action( 'wp_ajax_nopriv_rcp_restore_cancelled_subscriptions', 'rcp_restore_cancelled_subscriptions_ajax' );
