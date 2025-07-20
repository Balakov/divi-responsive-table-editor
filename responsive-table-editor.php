<?php
/**
 * Plugin Name: Responsive Table Editor for Divi
 * Description: A Divi module to preview responsive HTML tables from CSV input.
 * Version: 1.12
 * Author: Mike Stimpson
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function rte_divi_module_setup() {
    if ( class_exists( 'ET_Builder_Module' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/ResponsiveTableEditor.php';
        new ResponsiveTableEditor();
    }
}
add_action( 'et_builder_ready', 'rte_divi_module_setup' );

function rte_divi_enqueue_assets() {
    wp_enqueue_style( 'rte-style', plugin_dir_url( __FILE__ ) . 'css/module.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/module.css') );
}
add_action( 'wp_enqueue_scripts', 'rte_divi_enqueue_assets' );
add_action( 'admin_enqueue_scripts', 'rte_divi_enqueue_assets' );