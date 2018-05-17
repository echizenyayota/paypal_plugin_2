<?php
/**
 * @package express
 */
/*
Plugin Name: PayPal Express Checkout
Plugin URI: https://example.com
Description: PayPal Express Checkout
Version: 0.0.0
Author: echizenya
Author URI: https://e-yota.com
License: GPLv2 or later
Text Domain: paypal_expresscheckout
*/

// Client Side Restの読み込み
function paypal_scripts() {
	wp_enqueue_script( 'paypal-checkout', 'https://www.paypalobjects.com/api/checkout.js' );
	wp_enqueue_script( 'paypal-expresscheckout', plugin_dir_url( __FILE__ ) . '/js/expresscheckout.js', array( 'paypal-checkout' ) );
}
add_action( 'wp_enqueue_scripts', 'paypal_scripts' );

// ショートコード記述によるPayPalボタンの表示
function paypaldiv_func(){
  $paypaldiv = '<div id="paypal-button-container"></div>';
  return $paypaldiv;
}
add_shortcode( 'paypaldiv', 'paypaldiv_func' );
