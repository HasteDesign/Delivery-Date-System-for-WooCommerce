<?php

/**
 * Display the delivery date and period fields in checkout.
 *
 * @param [object] $checkout
 * @return void
 */
function delivery_date_system_echo_fields( $checkout ) {
	echo '<div class="delivery-options">';
	
	woocommerce_form_field( 'delivery_date', array(
		'type'          => 'text',
		'class'         => array('form-row-wide'),
		'id'            => 'datepicker',
		'required'      => true,
		'label'         => __( 'Select one of the available delivery days', 'delivery-date-system' ),
		'placeholder'   => __( 'Open calendar', 'delivery-date-system' ),
		'autocomplete'  => 'off',
	));
	
	if ( ! empty( delivery_time_options() ) ) {
		woocommerce_form_field( 'delivery_time', array(
			'type'          => 'select',
			'class'         => array('form-row-wide'),
			'id'            => 'delivery-time',
			'required'      => true,
			'label'         => __( 'Select a delivery time', 'delivery-date-system' ),
			'options'     	=> delivery_time_options()
		));
	}
		
	echo '</div>';
}
add_action( 'woocommerce_before_order_notes', 'delivery_date_system_echo_fields', 5 );
	
/**
 * Validates delivery date fields
 *
 * @return void
 */
function delivery_date_system_validate_new_checkout_fields() {
	if ( empty( $_POST['delivery_date'] ) ) wc_add_notice( __( 'Select an available delivery date.', 'delivery-date-system' ), 'error' );
	if ( empty( $_POST['delivery_time'] ) ) wc_add_notice( __( 'Select an available delivery time.', 'delivery-date-system' ), 'error' );
}
add_action( 'woocommerce_checkout_process', 'delivery_date_system_validate_new_checkout_fields' );

/**
 * Save delivery date and time in order meta.
 *
 * @param int $order_id
 * @return void
 */
function delivery_date_system_save_date_time_order( $order_id ) {
    if ( $_POST['delivery_date'] ) update_post_meta( $order_id, '_delivery_date', sanitize_text_field( $_POST['delivery_date'] ) );
	if ( $_POST['delivery_time'] ) update_post_meta( $order_id, '_delivery_time', sanitize_text_field( $_POST['delivery_time'] ) );
}
add_action( 'woocommerce_checkout_update_order_meta', 'delivery_date_system_save_date_time_order' );

/**
 * Display delivery date and period in admin order view
 *
 * @param [type] $order
 * @return void
 */
function delivery_date_system_display_admin_order_meta( $order ) {
	echo '<p><strong>' . __( 'Delivery date:', 'delivery-date-system' ) . '</strong> ' . get_post_meta( $order->get_id(), '_delivery_date', true ) . '</p>';
	echo '<p><strong>' . __( 'Delivery time:', 'delivery-date-system' ) . '</strong> ' . delivery_time_label( $order->get_id() ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'delivery_date_system_display_admin_order_meta' );

/**
 * Display delivery date and time in order email
 *
 * @param [type] $order
 * @param [type] $sent_to_admin
 * @param [type] $plain_text
 * @param [type] $email
 * @return void
 */
function delivery_date_system_order_email_info( $order, $sent_to_admin, $plain_text, $email ) {
	$date = get_post_meta( $order->get_id(), '_delivery_date', true );
    $time = delivery_time_label( $order->get_id() );

    if ( $plain_text === false ) {
		echo '<h2>' . __( 'Delivery', 'delivery-date-system' ) . '</h2>';
        echo '<p>';
        printf( esc_html__( 'Your order will be delivered in %1$s, at %2$s.', 'delivery-date-system' ), $date, $time );
        echo '</p>';
	} else {
		echo __( 'Delivery', 'delivery-date-system' ) . '\n';
		printf( esc_html__( 'Your order will be delivered in %1$s, at %2$s.', 'delivery-date-system' ), $date, $time );
	}
}
add_action( 'woocommerce_email_after_order_table', 'delivery_date_system_order_email_info', 10, 4 );
