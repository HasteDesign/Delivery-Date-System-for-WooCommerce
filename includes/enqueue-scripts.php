<?php

// Prevents direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue delivery date system scripts
 *
 * @return void
 */
function delivery_date_system_enable_datepicker() {
	if ( function_exists( 'is_woocommerce' ) ) {
		if ( is_checkout() ) {
			// jQuery UI
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			
			// Delivery Date
			wp_enqueue_script( 'delivery-date-system', plugins_url( '../assets/js/delivery.date.min.js', __FILE__ ), array( 'jquery-ui-datepicker', 'jquery-ui-draggable' ) );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'delivery_date_system_enable_datepicker', 10 );

/**
 * Pass options and translated strings to JavaScript
 *
 * @return void
 */
function delivery_date_system_js_data() {
	// jQuery UI Datepicker options
	$data = array();
	$data['locale'] = get_locale();
	$data['closeText'] = __( 'Close', 'delivery-date-system' );
	$data['prevText'] = __( '&#x3C; Previous', 'delivery-date-system' );
	$data['nextText'] = __( 'Next &#x3E;', 'delivery-date-system' );
	$data['currentText'] = __( 'Today', 'delivery-date-system' );
	$data['monthNames'] = delivery_date_system_month_names();
	$data['monthNamesShort'] = delivery_date_system_month_names('M');
	$data['dayNames'] = delivery_date_system_day_names();
	$data['dayNamesShort'] = delivery_date_system_day_names('D');
	$data['dayhNamesMin'] = delivery_date_system_day_names_first_letter();
	$data['weekHeader'] = 'Sm';
	$data['dateFormat'] = 'dd/mm/yy';

	$data['availableWeekdays'] = delivery_date_system_get_option( 'available_weekdays', [0,1,2,3,4,5,6] );
	
	$excludedDates = delivery_date_system_get_option( 'excluded_dates', '' );
	$data['excludedDates'] = delivery_date_system_timestamp_to_datestring( $excludedDates );
	$data['excludedDates_m_d_Y'] = delivery_date_system_timestamp_to_datestring( $excludedDates, 'n-j-Y' );

	$data['daysOffset'] = delivery_date_system_get_option( 'days_offset', 0 );
	$data['daysSpan'] = delivery_date_system_get_option( 'days_span', 1 );

	wp_localize_script( 'delivery-date-system', 'delivery_data', $data );
}
add_action( 'wp_enqueue_scripts', 'delivery_date_system_js_data', 15 );