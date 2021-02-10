<?php
/*
Plugin Name: Quiero Spain product list override
Plugin URI: https://novadevs.com/
Description: Plugin que sobreeescribe las templates de producto de WooCommerce
Version: 0.0.0
Author: Bruno Lorente
Author URI: https://github.com/brunolorente
License: GPLv2 or later
Text Domain: novadevs
*/

if (!defined('QS_PL')) {
    define('QS_PL', plugin_dir_path(__FILE__));
}

// Including admin page file
require_once(QS_PL . '/activation.php');

/**
 * Check if WooCommerce is activated
 */
if (! function_exists('is_woocommerce_activated')) {
    function is_woocommerce_activated()
    {
        if (class_exists('woocommerce')) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * The plugin CSS
 */
if (! function_exists('QS_PL_styles')) {
    function QS_PL_styles()
    {
        wp_register_style('font-awesome', 'https://use.fontawesome.com/releases/v5.7.0/css/all.css');
        wp_enqueue_style('font-awesome');
        wp_register_style('QS_PL_css', plugin_dir_url(__FILE__) . 'css/main.css');
        wp_enqueue_style('QS_PL_css');
    }
}
add_action('wp_enqueue_scripts', 'QS_PL_styles');

/**
 * The plugin JS
 */
if (! function_exists('QS_PL_scripts')) {
    function QS_PL_scripts()
    {
        // Own scripts
        wp_deregister_script('QS_PL_scripts');
        wp_enqueue_script('QS_PL_scripts', plugin_dir_url(__FILE__) . 'scripts/main.js', array('jquery', 'wc-add-to-cart-variation'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'QS_PL_scripts');



function wc_product_list__plugin_path()
{
    // gets the absolute path to this plugin directory
    return untrailingslashit(plugin_dir_path(__FILE__));
}
  
// https://www.skyverge.com/blog/override-woocommerce-template-file-within-a-plugin/
function wc_product_list__woocommerce_locate_template($template, $template_name, $template_path)
{
    global $woocommerce;
  
    $_template = $template;
  
    if (! $template_path) {
        $template_path = $woocommerce->template_url;
    }
  
    $plugin_path  = wc_product_list__plugin_path() . '/woocommerce/';
  
    // Look within passed path within the theme - this is priority
    $template = locate_template(
        array(
        $template_path . $template_name,
        $template_name
      )
    );
  
    // Modification: Get the template from this plugin, if it exists
    if (! $template && file_exists($plugin_path . $template_name)) {
        $template = $plugin_path . $template_name;
    }
  
    // Use default template
    if (! $template) {
        $template = $_template;
    }
  
    // Return what we found
    return $template;
}

add_filter('woocommerce_locate_template', 'wc_product_list__woocommerce_locate_template', 10, 3);
