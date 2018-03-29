<?php
/*
Plugin Name: Masari - WooCommerce Gateway
Plugin URI: https://getmasari.org
Description: Extends WooCommerce by Adding the Masari Gateway
Version: 2.0
Author: Masari Project
Author URI: https://monerointegrations.com
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Include our Gateway Class and register Payment Gateway with WooCommerce
add_action('plugins_loaded', 'masari_init', 0);
function masari_init()
{
    /* If the class doesn't exist (== WooCommerce isn't installed), return NULL */
    if (!class_exists('WC_Payment_Gateway')) return;


    /* If we made it this far, then include our Gateway Class */
    include_once('include/masari_payments.php');
    require_once('library.php');

    // Lets add it too WooCommerce
    add_filter('woocommerce_payment_gateways', 'monero_gateway');
    function monero_gateway($methods)
    {
        $methods[] = 'Monero_Gateway';
        return $methods;
    }
}

/*
 * Add custom link
 * The url will be http://yourworpress/wp-admin/admin.php?=wc-settings&tab=checkout
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'monero_payment');
function monero_payment($links)
{
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout') . '">' . __('Settings', 'monero_payment') . '</a>',
    );

    return array_merge($plugin_links, $links);
}

add_action('admin_menu', 'monero_create_menu');
function monero_create_menu()
{
    add_menu_page(
        __('Masari', 'textdomain'),
        'Masari',
        'manage_options',
        'admin.php?page=wc-settings&tab=checkout&section=monero_gateway',
        '',
        plugins_url('monero/assets/monero_icon.png'),
        56 // Position on menu, woocommerce has 55.5, products has 55.6

    );
}


