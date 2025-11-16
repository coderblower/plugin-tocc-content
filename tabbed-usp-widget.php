<?php
/**
 * Plugin Name: Tabbed USP Widget for Elementor
 * Version: 2.0.0
 */

if (!defined('ABSPATH')) exit;

// Check if Elementor is installed
function tabbed_usp_check_elementor() {
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'tabbed_usp_missing_elementor');
        return;
    }
}
add_action('plugins_loaded', 'tabbed_usp_check_elementor');

function tabbed_usp_missing_elementor() {
    echo '<div class="notice notice-warning is-dismissible"><p>';
    echo '<strong>Tabbed USP Widget</strong> requires <strong>Elementor</strong> to be installed and activated.';
    echo '</p></div>';
}

// Register Widget
function register_tabbed_usp_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/tabbed-usp-widget-class.php');
    $widgets_manager->register(new \ElementorTabbedUSP\Tabbed_USP_Widget());
}
add_action('elementor/widgets/register', 'register_tabbed_usp_widget');