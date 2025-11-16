<?php
/**
 * Plugin Name: Tabbed USP Widget for Elementor
 * Description: A custom Elementor widget to display tabbed Unique Selling Propositions (USPs) with icons, titles, summaries, and detailed content items.
 * Version: 2.0.0
 * Author: Your Name
 * Text Domain: tabbed-usp-widget
 */ 

if (!defined('ABSPATH')) exit;

/**
 * Enqueue Elementor Editor Scripts
 */
function tabbed_usp_enqueue_editor_scripts() {
    if (defined('ELEMENTOR_PLUGIN_BASE')) {
        wp_enqueue_script(
            'tabbed-usp-auto-refresh',
            plugin_dir_url(__FILE__) . 'assets/elementor-auto-refresh.js',
            ['jquery', 'elementor-editor'],
            '2.0.0',
            true
        );
    }
}
add_action('elementor/editor/after_enqueue_scripts', 'tabbed_usp_enqueue_editor_scripts');

/**
 * Check if Elementor is installed and activated
 */
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

/**
 * Register Widget
 */
function register_tabbed_usp_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/tabbed-usp-widget-class.php');
    $widgets_manager->register(new \ElementorTabbedUSP\Tabbed_USP_Widget());
}
add_action('elementor/widgets/register', 'register_tabbed_usp_widget');