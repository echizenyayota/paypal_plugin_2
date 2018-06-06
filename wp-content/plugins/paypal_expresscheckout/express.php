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
	// wp_enqueue_script( 'paypal-expresscheckout', plugin_dir_url( __FILE__ ) . '/js/expresscheckout.js', array( 'paypal-checkout' ) );
}
add_action( 'wp_enqueue_scripts', 'paypal_scripts' );

// ショートコード記述によるPayPalボタンの表示
function paypaldiv_func( $atts ){
	$config = shortcode_atts( array(
		'id' => '',	// id名と第二引数が合致すれば良い
		'price' => '0',
		'currency' => '',
		'env' => 'sandbox',	// 管理画面上入力
		'color' => 'blue',
		'size' => 'small',
	), $atts );

	if ( !$config['id'] || $config['price'] === '0' || !$config['currency'] ) return;

	if ( $config['env'] === 'sandbox' ) {
		$token = "sandbox: 'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R'";
	} elseif ( $config['env'] === 'production' ) {
		$token = "production: 'input your production token'";
	}

	$paypaldiv = '<div id="' . $config['id'] . '"></div>';
	$paypaldiv .= "<script>
		paypal.Button.render({
			env: '$config[env]',
			client: {
				{$token},
			},
			style: {
				color: '$config[color]',
				size: '$config[size]',
			},
			commit: true,
			payment: function(data, actions) {
				return actions.payment.create({
					payment: {
						transactions: [{
							amount: { total: '$config[price]', currency: '$config[currency]' }
						}]
					}
				});
			},
			onAuthorize: function(data, actions) {
				return actions.payment.execute().then(function() {
					window.alert('Payment Complete!');
				});
			}
		}, '#$config[id]');
	</script>";
  return $paypaldiv;
}
add_shortcode( 'paypaldiv', 'paypaldiv_func' );

// 管理画面の表示
function paypalexpresscheckout_add_admin_menu(){
    add_submenu_page('plugins.php','PayPal Express Checkoutの設定','PayPal Express Checkoutの設定', 'administrator', __FILE__, 'paypalexpresscheckout_admin_menu');
}
add_action('admin_menu', 'paypalexpresscheckout_add_admin_menu');

require_once(__DIR__ . '/express_admin.php');
