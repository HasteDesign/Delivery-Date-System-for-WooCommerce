<?php

// Prevents direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the delivery time options in an array
 *
 * @return array $options
 */
function delivery_time_options() {
	$times = delivery_date_system_get_option( 'delivery_times', '' );
	$options = array( '' => __( 'Select an option...', 'delivery-date-system' ) );

	if ( ! empty( $times ) ) {
		foreach( $times as $time ) {
			$from = date('H:i', strtotime( $time['from'] ) );
			$to = date('H:i', strtotime( $time['to'] ) );
			$options[sanitize_title( $time['label'] )] = sprintf( __( '%1$s - From %2$s to %3$s', 'delivery-date-system' ), $time['label'], $from, $to );
		}
	}

	return $options;
}

/**
 * Return delivery time label to be displayed
 *
 * @param int $order_id
 * @return string $period
 */
function delivery_time_label( $order_id ) {
	$period = get_post_meta( $order_id, '_delivery_time', true );
	$periods = delivery_time_options();

	if ( ! empty( $periods[ $period ] ) )
		return $periods[ $period ];

	return __( 'Unknown delivery time.', 'delivery-date-woocommerce' );
}

/**
 * Return an array of month names
 *
 * @param string $format The expected month format
 * @return array $months
 */
function delivery_date_system_month_names( $format = 'F' ) {
	$months = array();

	for( $m = 1; $m <= 12; ++$m ){
		$months[] = date_i18n( $format, mktime(0, 0, 0, $m, 1) );
	}

	return $months;
}

/**
 * Returns an array of day names
 * 
 * @param string $format The expected day format
 * @return void
 */
function delivery_date_system_day_names( $format = 'l' ) {
	$timestamp = strtotime( 'next Sunday' );
	$days = array();
	
	for ($i = 0; $i < 7; $i++) {
		$days[] = date_i18n( $format, $timestamp );
		$timestamp = strtotime( '+1 day', $timestamp );
	}

	return $days;
}

/**
 * Returns an array of first letter day names
 *
 * @return array $days
 */
function delivery_date_system_day_names_first_letter() {
	$timestamp = strtotime( 'next Sunday' );
	$days = array();
	
	for ( $i = 0; $i < 7; $i++) {
		$days[] = substr( date_i18n( 'l', $timestamp ), 0, 1 );
		$timestamp = strtotime( '+1 day', $timestamp );
	}

	return $days;
}

/**
 * Returns an array of timestamps as formatted dates
 *
 * @param [type] $dates
 * @param string $format
 * @return array $formatted
 */
function delivery_date_system_timestamp_to_datestring( $dates, $format = 'Y-m-d' ) {
	$formatted = array();
	
	if ( is_array( $dates ) ) {
		foreach ( $dates as $date ) {
			$formatted[] = date( $format, $date );
		}
	}

	return $formatted;
}