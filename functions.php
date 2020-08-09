<?php
/**
 * Use this file like a functions.php of your theme
 *
 * We recommend to keep your code organized and split in files, and
 * you can include them here.
 */

// Prevents direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Admin Options
require_once plugin_dir_path( __FILE__ ) . '/includes/adminoptions.php';

// Helpers
require_once plugin_dir_path( __FILE__ ) . '/includes/helpers.php';

// Enqueue Scripts
require_once plugin_dir_path( __FILE__ ) . '/includes/enqueue-scripts.php';

// WooCommerce Delivery Date
require_once plugin_dir_path( __FILE__ ) . '/includes/woocommerce-delivery-date.php';

// WooCommerce Invoice Customization
require_once plugin_dir_path( __FILE__ ) . '/includes/woocommerce-invoice-customization.php';
