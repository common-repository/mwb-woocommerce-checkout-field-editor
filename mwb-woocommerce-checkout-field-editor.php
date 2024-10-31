<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Mwb_Woocommerce_Checkout_Field_Editor
 *
 * @wordpress-plugin
 * Plugin Name:       MWB Woocommerce Checkout Field Editor
 * Plugin URI:        https://makewebbetter.com/
 * Description:       This plugin helps to customize woocommerce default checkout fields.
 * Version:           1.0.6
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       mwb-woocommerce-checkout-field-editor
 * Domain Path:       /languages
 *
 * Requires at least:        4.6
 * Tested up to: 	         5.0.0
 * WC requires at least:     3.2
 * WC tested up to:          3.3.4
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// To Activate plugin only when WooCommerce is active.
$activated = true;
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

	$activated = false;
}

if ( $activated ) {

	// Define plugin constants.
	function define_mwb_woocommerce_checkout_field_editor_constants() {

		mwb_woocommerce_checkout_field_editor_constants( 'MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_VERSION', '1.0.6' );
		mwb_woocommerce_checkout_field_editor_constants( 'MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_PATH', plugin_dir_path( __FILE__ ) );
		mwb_woocommerce_checkout_field_editor_constants( 'MWB_WOOCOMMERCE_CHECKOUT_FIELD_EDITOR_DIR_URL', plugin_dir_url( __FILE__ ) );
	}

	// Callable function for defining plugin constants.
	function mwb_woocommerce_checkout_field_editor_constants( $key, $value ) {

		if( ! defined( $key ) ) {
			
			define( $key, $value );
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-mwb-woocommerce-checkout-field-editor-activator.php
	 */
	function activate_mwb_woocommerce_checkout_field_editor() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-checkout-field-editor-activator.php';
		Mwb_Woocommerce_Checkout_Field_Editor_Activator::activate();

		// Create transient data.
    	set_transient( 'mwb_woocommerce_checkout_field_editor_transient_user_exp_notice', true, 5 );
	}

	// Add admin notice only on plugin activation.
	add_action( 'admin_notices', 'mwb_woocommerce_checkout_field_editor_user_exp_notice' );	

	// Facebook setup notice on plugin activation.
	function mwb_woocommerce_checkout_field_editor_user_exp_notice() {

		/**
		 * Check transient.
		 * If transient available display notice.
		 */
		if( get_transient( 'mwb_woocommerce_checkout_field_editor_transient_user_exp_notice' ) ):
			update_option('mwb_wbl_actived_plugin','1');
			?>
			<div class="notice notice-info is-dismissible">
				<p><strong><?php _e( 'Welcome to MWB Woocommerce Checkout Field Editor', 'mwb-woocommerce-checkout-field-editor' ); ?></strong><?php _e( ' – < Here try to explain the Next Process after plugin activation. >', 'mwb-woocommerce-checkout-field-editor' ); ?></p>
				<p class="submit"><a href="<?php echo admin_url( 'admin.php?page=mwb_woocommerce_checkout_field_editor_menu' ); ?>" class="button-primary"><?php _e( '< Next Process Link >', 'mwb-woocommerce-checkout-field-editor' ); ?></a></p>
			</div>

			<?php

			delete_transient( 'mwb_woocommerce_checkout_field_editor_transient_user_exp_notice' );

		endif;
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-mwb-woocommerce-checkout-field-editor-deactivator.php
	 */
	function deactivate_mwb_woocommerce_checkout_field_editor() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-checkout-field-editor-deactivator.php';
		Mwb_Woocommerce_Checkout_Field_Editor_Deactivator::deactivate();
		delete_option('mwb_wbl_actived_plugin');
	}

	register_activation_hook( __FILE__, 'activate_mwb_woocommerce_checkout_field_editor' );
	register_deactivation_hook( __FILE__, 'deactivate_mwb_woocommerce_checkout_field_editor' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-mwb-woocommerce-checkout-field-editor.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_mwb_woocommerce_checkout_field_editor() {

		define_mwb_woocommerce_checkout_field_editor_constants();

		$plugin = new Mwb_Woocommerce_Checkout_Field_Editor();
		$plugin->run();

	}
	run_mwb_woocommerce_checkout_field_editor();

	// Add settings link on plugin page.
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'mwb_woocommerce_checkout_field_editor_settings_link' );

	// Settings link.
	function mwb_woocommerce_checkout_field_editor_settings_link( $links ) {

	    $my_link = array(
	    		'<a href="' . admin_url( 'admin.php?page=mwb_woocommerce_checkout_field_editor_menu' ) . '">' . __( 'Settings', 'mwb-woocommerce-checkout-field-editor' ) . '</a>',
	    	);
	    return array_merge( $my_link, $links );
	}
	/**
	 * This function runs when WordPress completes its upgrade process
	 * It iterates through each plugin updated to see if ours is included
	 *
	 * @param object $upgrader_object .
	 * @param array  $options .
	 */
	function rma_lite_reno_plugin_upgrade_completed( $upgrader_object, $options ) {
		// The path to our plugin's main file.
		$our_plugin = plugin_basename( __FILE__ );
		// If an update has taken place and the updated type is plugins and the plugins element exists.
		if ( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
			// Iterate through the plugins being updated and check if ours is there.
			foreach ( $options['plugins'] as $plugin ) {
				if ( $plugin == $our_plugin ) {
					// Set a transient to record that our plugin has just been updated.
					require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-rma-lite-restore-settings-updation.php';
					$restore_data = new Woocommerce_Rma_Lite_Restore_Settings_Updation();
					$restore_data->mwb_rma_migrate_re_settings();
				}
			}
		}
	}
	add_action( 'upgrader_process_complete', 'rma_lite_reno_plugin_upgrade_completed', 10, 2 );
}

else {

	// WooCommerce is not active so deactivate this plugin.
	add_action( 'admin_init', 'mwb_woocommerce_checkout_field_editor_activation_failure' );

	// Deactivate this plugin.
	function mwb_woocommerce_checkout_field_editor_activation_failure() {

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	// Add admin error notice.
	add_action( 'admin_notices', 'mwb_woocommerce_checkout_field_editor_activation_failure_admin_notice' );

	// This function is used to display admin error notice when WooCommerce is not active.
	function mwb_woocommerce_checkout_field_editor_activation_failure_admin_notice() {

		// to hide Plugin activated notice.
		unset( $_GET['activate'] );

	    ?>

	    <div class="notice notice-error is-dismissible">
	        <p><?php _e( 'WooCommerce is not activated, Please activate WooCommerce first to activate MWB Woocommerce Checkout Field Editor.','mwb-woocommerce-checkout-field-editor' ); ?></p>
	    </div>

	    <?php
	}
}

function mwb_rma_migrate_re_settings_function() {
	$enable_refund = get_option( 'mwb_wrma_return_enable', false );
	if ( 'yes' === $enable_refund ) {
		update_option( 'mwb_rma_refund_enable', 'on' );
	}
	$attach_enable = get_option( 'mwb_wrma_return_attach_enable', false );
	if ( 'yes' === $attach_enable ) {
		update_option( 'mwb_rma_refund_attachment', 'on' );
	}
	$attach_limit = get_option( 'mwb_wrma_refund_attachment_limit', false );
	if ( ! empty( $attach_limit ) && $attach_limit > 0 ) {
		update_option( 'mwb_rma_attachment_limit', $attach_limit );
	}
	$manage_stock = get_option( 'mwb_wrma_return_request_manage_stock', false );
	if ( 'yes' === $manage_stock ) {
		update_option( 'mwb_rma_refund_manage_stock', 'on' );
	}
	$show_pages = get_option( 'mwb_wrma_refund_button_view', false );
	if ( ! empty( $show_pages ) ) {
		$button_hide = array();
		if ( ! in_array( 'order-page', $show_pages ) ) {
			$button_hide[] = 'order-page';
		}
		if ( ! in_array( 'My account', $show_pages ) ) {
			$button_hide[] = 'My account';
		}
		if ( ! in_array( 'thank-you-page', $show_pages ) ) {
			$button_hide[] = 'Checkout';
		}
		update_option( 'mwb_rma_refund_button_pages', $button_hide );
	}
	$refund_rule_enable = get_option( 'mwb_wrma_refund_rules_editor_enable', false );
	if ( 'yes' === $refund_rule_enable ) {
		update_option( 'mwb_rma_refund_rules', 'on' );
	}
	$refund_editor = get_option( 'mwb_wrma_return_request_rules_editor', false );
	if ( ! empty( $refund_editor ) ) {
		update_option( 'mwb_rma_refund_rules_editor', $refund_editor );
	}
	$refund_text = get_option( 'mwb_wrma_return_button_text', false );
	if ( ! empty( $refund_text ) ) {
		update_option( 'mwb_rma_refund_button_text', $refund_text );
	}
	$refund_desc = get_option( 'mwb_wrma_return_request_description', false );
	if ( 'yes' === $refund_desc ) {
		update_option( 'mwb_rma_refund_description', 'on' );
	}
	$refund_reason  = get_option( 'ced_rnx_return_predefined_reason', false );
	$refund_reason1 = get_option( 'mwb_wrma_return_predefined_reason', false );
	if ( ! empty( $refund_reason1 ) ) {
		$refund_reason = $refund_reason1;
	}
	if ( ! empty( $refund_reason ) ) {
		$refund_reason = implode( ',', $refund_reason );
		update_option( 'mwb_rma_refund_reasons', $refund_reason );
	}
	$order_msg_enable = get_option( 'mwb_wrma_order_message_view', false );
	if ( 'yes' === $order_msg_enable ) {
		update_option( 'mwb_rma_general_om', 'on' );
	}
	$order_attach = get_option( 'mwb_wrma_order_message_attachment', false );
	if ( 'yes' === $order_attach ) {
		update_option( 'mwb_rma_general_enable_om_attachment', 'on' );
	}
	$order_text = get_option( 'mwb_wrma_order_msg_text', false );
	if ( ! empty( $order_text ) ) {
		update_option( 'mwb_rma_order_message_button_text', $order_text );
	}

	// RMA Policies Setting Save.
	$tax_enable          = get_option( 'mwb_wrma_return_tax_enable', false );
	$refund_order_status = get_option( 'mwb_wrma_return_order_status', false );
	$return_days         = get_option( 'mwb_wrma_return_days', false );
	$refund_order_status = ! empty( $refund_order_status ) ? $refund_order_status : array();
	$set_policies_arr = array(
		'mwb_rma_setting' =>
		array(
			0 => array(
				'row_policy'           => 'mwb_rma_maximum_days',
				'row_functionality'    => 'refund',
				'row_conditions1'      => 'mwb_rma_less_than',
				'row_value'            => $return_days,
				'incase_functionality' => 'incase',
			),
			1 => array(
				'row_functionality'    => 'refund',
				'row_policy'           => 'mwb_rma_order_status',
				'row_conditions2'      => 'mwb_rma_equal_to',
				'row_statuses'         => $refund_order_status,
				'incase_functionality' => 'incase',
			),
		),
	);

	if ( 'yes' !== $tax_enable ) {
		unset( $set_policies_arr['mwb_rma_setting'][2] );
	}
	update_option( 'policies_setting_option', $set_policies_arr );

	// Refund Request Subject And Content Updation.
	$subject  = get_option( 'ced_rnx_notification_return_subject', false );
	$subject1 = get_option( 'mwb_wrma_notification_return_subject', false );
	if ( ! empty( $subject1 ) ) {
		$subject = $subject1;
	}
	if ( empty( $subject ) ) {
		$subject = '';
	}
	$content  = get_option( 'ced_rnx_notification_return_rcv', false );
	$content1 = get_option( 'mwb_wrma_notification_return_rcv', false );
	if ( ! empty( $content1 ) ) {
		$content = $content1;
	}
	if ( empty( $content ) ) {
		$content = '';
	}
	$refund_request_add = array(
		'enabled'            => 'yes',
		'subject'            => $subject,
		'heading'            => '',
		'additional_content' => $content,
		'email_type'         => 'html',
	);
	update_option( 'woocommerce_mwb_rma_refund_request_email_settings', $refund_request_add );

	// Refund Request Accept Subject And Content Updation.
	$subject  = get_option( 'ced_rnx_notification_return_approve_subject', false );
	$subject1 = get_option( 'mwb_wrma_notification_return_approve_subject', false );
	if ( ! empty( $subject1 ) ) {
		$subject = $subject1;
	}
	if ( empty( $subject ) ) {
		$subject = '';
	}
	$content  = get_option( 'ced_rnx_notification_return_approve', false );
	$content1 = get_option( 'mwb_wrma_notification_return_approve', false );
	if ( ! empty( $content1 ) ) {
		$content = $content1;
	}
	if ( empty( $content ) ) {
		$content = '';
	}
	$refund_request_accept_add = array(
		'enabled'            => 'yes',
		'subject'            => $subject,
		'heading'            => '',
		'additional_content' => $content,
		'email_type'         => 'html',
	);
	update_option( 'woocommerce_mwb_rma_refund_request_accept_email_settings', $refund_request_accept_add );

	// Refund Request Cancel Subject And Content Updation.

	$subject  = get_option( 'ced_rnx_notification_return_cancel_subject', false );
	$subject1 = get_option( 'mwb_wrma_notification_return_cancel_subject', false );
	if ( ! empty( $subject1 ) ) {
		$subject = $subject1;
	}
	if ( empty( $subject ) ) {
		$subject = '';
	}
	$content  = get_option( 'ced_rnx_notification_return_cancel', false );
	$content1 = get_option( 'mwb_wrma_notification_return_cancel', false );
	if ( ! empty( $content1 ) ) {
		$content = $content1;
	}
	if ( empty( $content ) ) {
		$content = '';
	}
	$refund_request_cancel_add = array(
		'enabled'            => 'yes',
		'subject'            => $subject,
		'heading'            => '',
		'additional_content' => $content,
		'email_type'         => 'html',
	);
	update_option( 'woocommerce_mwb_rma_refund_request_cancel_email_settings', $refund_request_cancel_add );
}


