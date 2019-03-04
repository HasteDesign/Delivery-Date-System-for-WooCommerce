<?php

/**
 * Add the delivery date and time to the printable invoice template
 * for WooCommerce Delivery Notes integration.
 *
 * @param [type] $fields
 * @param [type] $order
 * @return void
 */
function delivery_date_system_invoice_delivery_info( $fields, $order ) {
    $new_fields = array();

    if( get_post_meta( $order->get_id(), '_delivery_date', true ) ) {
        $new_fields['_delivery_date'] = array(
            'label' => __('Data da entrega', 'delivery-date-system' ),
            'value' => get_post_meta( $order->get_id(), '_delivery_date', true )
        );
    }

    if( get_post_meta( $order->get_id(), '_delivery_time', true ) ) {
        $new_fields['_delivery_time'] = array(
            'label' => __('Período da entrega', 'delivery-date-system' ),
            'value' => delivery_time_label( $order->get_id() )
        );
    }

    return array_merge( $fields, $new_fields );
}
add_filter( 'wcdn_order_info_fields', 'delivery_date_system_invoice_delivery_info', 10, 2 );

/**
 * Adds coupon info to the delivery note / invoice
 *
 * @param [type] $fields
 * @param [type] $order
 * @return void
 */
function delivery_date_system_invoice_coupon_info( $fields, $order ) {
    $new_fields = array();

	if( $order->get_used_coupons() ) {

		$coupons_count = count( $order->get_used_coupons() );

	    $coupons_list = '';
		$i = 1;

	    foreach( $order->get_used_coupons() as $coupon) {
	        $coupons_list .=  $coupon;
			if( $i < $coupons_count )
	        	$coupons_list .= ', ';
	        $i++;
	    }

		$new_fields['_coupons'] = array(
			'label' => __('Cupons usados', 'delivery-date-system' ),
			'value' => $coupons_list,
		);
	}

    return array_merge( $fields, $new_fields );
}
add_filter( 'wcdn_order_info_fields', 'delivery_date_system_invoice_coupon_info', 20, 2 );
