<?php
/**
 * Display Checkout Calendar if Shipping Selected
 */
function delivery_date_system_echo_fields( $checkout ) {
	echo '<div class="delivery-options">';
	
	woocommerce_form_field( 'delivery_date', array(
		'type'          => 'text',
		'class'         => array('form-row-wide'),
		'id'            => 'datepicker',
		'required'      => true,
		'label'         => __('Selecione um dos dias de entrega disponíveis', 'delivery-date-system' ),
		'placeholder'   => __('Abrir calendário', 'delivery-date-system' ),
		'autocomplete'  => 'off',
	));
	
	if ( ! empty( delivery_time_options() ) ) {
		woocommerce_form_field( 'delivery_time', array(
			'type'          => 'select',
			'class'         => array('form-row-wide'),
			'id'            => 'delivery-time',
			'required'      => true,
			'label'         => __('Selecione um período de entrega', 'delivery-date-system' ),
			'options'     	=> delivery_time_options()
		));
	}
		
	echo '</div>';
}
add_action( 'woocommerce_before_order_notes', 'delivery_date_system_echo_fields', 5 );
	
/**
 * Validate delivery date fields.
 */
function delivery_date_system_new_checkout_fields() {
	if ( isset( $_POST['delivery_date'] ) && empty( $_POST['delivery_date'] ) ) wc_add_notice( __( 'Escolha uma data de entrega disponível', 'delivery-date-system' ), 'error' );
	if ( $_POST['delivery_time'] == '' ) wc_add_notice( __( 'Escolha uma período de entrega disponível', 'delivery-date-system' ), 'error' );
}
add_action( 'woocommerce_checkout_process', 'delivery_date_system_validate_new_checkout_fields' );

/**
 * Save delivery date and time of order
 */
function delivery_date_system_save_date_time_order( $order_id ) {
    if ( $_POST['delivery_date'] ) update_post_meta( $order_id, '_delivery_date', esc_attr( $_POST['delivery_date'] ) );
	if ( $_POST['delivery_time'] ) update_post_meta( $order_id, '_delivery_time', esc_attr($_POST['delivery_time'] ) );
}
add_action( 'woocommerce_checkout_update_order_meta', 'delivery_date_system_save_date_time_order' );

/**
 * Display delivery date and time in admin order page
 */
function delivery_date_system_display_admin_order_meta( $order ) {
	echo '<p><strong>' . __('Data da entrega', 'delivery-date-system' ) . '</strong> ' . get_post_meta( $order->get_id(), '_delivery_date', true ) . '</p>';
	echo '<p><strong>' . __('Período da entrega', 'delivery-date-system' ) . '</strong> ' . delivery_time_label( $order->get_id() ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'delivery_date_system_display_admin_order_meta' );

/**
 * Display delivery date and time in order email
 */
function delivery_date_system_order_email_info( $order, $sent_to_admin, $plain_text, $email ) {
	$date = get_post_meta( $order->get_id(), '_delivery_date', true );
    $time = delivery_time_label( $order->get_id() );

    if ( $plain_text === false ) {
		echo '<h2>' . __( 'Entrega', 'delivery-date-system' ) . '</h2>';
        echo '<p>';
        printf( esc_html__( 'Sua entrega será feita no dia %1$s, no período da %2$s.', 'delivery-date-system' ), $date, $time );
        echo '</p>';
	} else {
		echo __( 'Entrega', 'delivery-date-system' ) . '\n';
		printf( esc_html__( 'Sua entrega será feita no dia %1$s, no período da %2$s.', 'delivery-date-system' ), $date, $time );
	}
}
add_action( 'woocommerce_email_after_order_table', 'delivery_date_system_order_email_info', 10, 4 );
