<?php
/**
 * Plugin Name: Delivery Date System for WooCommerce
 * Plugin URI: https://github.com/HasteDesign/Delivery-Date-System-for-Woocommerce
 * Description: Offers to your customers the option to select the delivery date and period
 * Version: 1.0.0
 * Author: Haste
 * Author URI: https://www.hastedesign.com.br
 * License: GPLv2
 * Text Domain: delivery-date-system
 * Domain Path: languages/
 * WC requires at least: 3.0.0
 * WC tested up to:      3.5.5
 */

// Prevents direct access
if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

if( ! class_exists( 'Delivery_Date_System' ) ) {
   class Delivery_Date_System {
	   /**
		* Current version number
		*
		* @var   string
		* @since 1.0.0
		*/
	   const VERSION = '1.0.0';

	   /**
		* Instance of this class.
		*
		* @var object
		*/
	   protected static $instance = null;

	   /**
		* Plugin directory path
		*
		* @var string
		*/
	   private $plugin_dir = null;

	   /**
		* Initialize the plugin.
		*/
	   function __construct() {
		   $this->plugin_dir = plugin_dir_path( __FILE__ );
		   add_action( 'init', array( $this, 'load_textdomain' ) );
		   add_action( 'init', array( $this, 'includes' ), 0 );
	   }

	   /**
		* Return the plugin instance.
		*/
	   public static function init()
	   {
		   // If the single instance hasn't been set, set it now.
		   if ( null == self::$instance ) {
			   self::$instance = new self;
		   }
		   return self::$instance;
	   }

	   /**
		* A final check if Haste Toolkit exists before kicking off our Haste Toolkit loading.
		* Delivery_Date_System_VERSION is defined at this point.
		*
		* @since  1.0.0
		*/
	   public function includes() {			
		   // Load the functions.php
		   require_once $this->plugin_dir . '/functions.php';
	   }

	   /**
		* Load plugin translation
		*/
	   public function load_textdomain() {
		   load_plugin_textdomain( 'delivery-date-system', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	   }
   }
}

/**
* Initialize the plugin actions.
*/
add_action( 'plugins_loaded', array( 'Delivery_Date_System', 'init' ) );
