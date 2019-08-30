<?php
// Prevents direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * CMB2
 */
if ( file_exists( plugin_dir_path( __FILE__ ) . '../../../vendor/cmb2/init.php' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '../../../vendor/cmb2/init.php';
} elseif ( file_exists( plugin_dir_path( __FILE__ ) . '../../../vendor/CMB2/init.php' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '../../../vendor/CMB2/init.php';
}

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function delivery_date_system_register_options_metabox() {

	/**
	 * Registers options page menu item and form.
	 */
	$cmb_options = new_cmb2_box( array(
		'id'           => 'delivery-date-system',
		'title'        => esc_html__( 'Delivery Dates', 'delivery-date-system' ),
		'object_types' => array( 'options-page' ),
		'option_key'      => 'delivery_date', // The option key and admin menu page slug.
		'parent_slug'     => 'woocommerce', // Make options page a submenu item of the themes menu.
		'capability'      => 'manage_woocommerce', // Cap required to view options-page.
    ) );

    // Days of Week to include
    $cmb_options->add_field( array(
        'name'    => __( 'Delivery Days', 'delivery-date-system' ),
        'desc'    => __( 'Choose the delivery days your store will deliver products.', 'delivery-date-system' ),
        'id'      => 'available_weekdays',
        'type'    => 'multicheck',
        'options' => array(
            '0' => __( 'Sunday', 'delivery-date-system' ),
            '1' => __( 'Monday', 'delivery-date-system' ),
            '2' => __( 'Tuesday', 'delivery-date-system' ),
            '3' => __( 'Wednesday', 'delivery-date-system' ),
            '4' => __( 'Thursday', 'delivery-date-system' ),
            '5' => __( 'Friday', 'delivery-date-system' ),
            '6' => __( 'Saturday', 'delivery-date-system' )
        ),
    ) );

    // Excluded Dates
    $cmb_options->add_field( array(
        'name' => __( 'Excluded Dates', 'delivery-date-system' ),
        'desc' => __( 'Set specific dates to be excluded from delivery date datepicker in checkout.', 'delivery-date-system' ),
        'id'   => 'excluded_dates',
        'type' => 'text_date_timestamp',
        'repeatable'  => true,
        'date_format' => 'd/m/Y',
    ) );

    // Offset
    $cmb_options->add_field( array(
        'name' => __( 'Days Offset', 'delivery-date-system' ),
        'desc' => __( 'Set from how much days the first delivery day will be available.', 'delivery-date-system' ),
        'id'   => 'days_offset',
        'type' => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
            'min'   => 0,
            'max'   => 99
        ),
    ) );

    // Range
    $cmb_options->add_field( array(
        'name' => __( 'Days Span', 'delivery-date-system' ),
        'desc' => __( 'For how much days after the offset will be delivery available?', 'delivery-date-system' ),
        'id'   => 'days_span',
        'type' => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
            'min'   => 1,
            'max'   => 365
        ),
    ) );

    $cmb_options->add_field( array(
        'name' => __( 'Delivery Times', 'delivery-date-system' ),
        'id'   => 'delivery_times_title',
        'type' => 'title',
    ) );

    $delivery_times = $cmb_options->add_field( array(
        'id'          => 'delivery_times',
        'type'        => 'group',
        'description' => __( 'Define the available delivery times.', 'delivery-date-system' ),
        'options'     => array(
            'group_title'       => __( 'Delivery Time {#}', 'delivery-date-system' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Add Another Time', 'delivery-date-system' ),
            'remove_button'     => __( 'Remove Time', 'delivery-date-system' ),
            'sortable'          => true,
            'closed'         => true, // true to have the groups closed by default
        ),
    ) );

    $cmb_options->add_group_field( $delivery_times, array(
        'name' => __( 'Label', 'delivery-date-system' ),
        'id'   => 'label',
        'type' => 'text',
    ) );

    $cmb_options->add_group_field( $delivery_times, array(
        'name' => __( 'From', 'delivery-date-system' ),
        'id' => 'from',
        'type' => 'text_time'
    ) );

    $cmb_options->add_group_field( $delivery_times, array(
        'name' => __( 'To', 'delivery-date-system' ),
        'id' => 'to',
        'type' => 'text_time'
    ) );
}
add_action( 'cmb2_admin_init', 'delivery_date_system_register_options_metabox' );


/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function delivery_date_system_get_option( $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( 'delivery_date', $key, $default );
	}
	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( 'delivery_date', $default );
	$val = $default;
	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}
	return $val;
}
