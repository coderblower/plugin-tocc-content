<?php
/**
 * Plugin Name: Tabbed USP Widget for Elementor
 * Description: Custom Elementor widgets including Tabbed USP, Vertical Tabs, Stats Section, and Registration Form with payment tracking.
 * Version: 2.3.0
 * Author: Your Name
 * Text Domain: tabbed-usp-widget
 */ 

if (!defined('ABSPATH')) exit;

/**
 * Load Registration Handler and Stripe Settings
 */
require_once(__DIR__ . '/includes/registration-handler.php');
require_once(__DIR__ . '/includes/stripe-settings.php');
require_once(__DIR__ . '/includes/admin-dashboard.php');

/**
 * Enqueue Elementor Editor Scripts
 */
function tabbed_usp_enqueue_editor_scripts() {
    if (defined('ELEMENTOR_PLUGIN_BASE')) {
        wp_enqueue_script(
            'tabbed-usp-auto-refresh',
            plugin_dir_url(__FILE__) . 'assets/elementor-auto-refresh.js',
            ['jquery', 'elementor-editor'],
            '2.1.0',
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
 * Add Custom Widget Categories (must run before widgets are registered)
 */
function add_tabbed_usp_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'tabbed-widgets',
        [
            'title' => __('Tabbed Widgets', 'tabbed-usp-widget'),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'add_tabbed_usp_widget_categories');

/**
 * Register Widgets
 */
function register_tabbed_usp_widgets($widgets_manager) {
    // Register Tabbed USP Widget
    require_once(__DIR__ . '/widgets/tabbed-usp-widget-class.php');
    $widgets_manager->register(new \ElementorTabbedUSP\Tabbed_USP_Widget());
    
    // Register Vertical Tabs Widget
    require_once(__DIR__ . '/widgets/vertical-tabs-widget-class.php');
    $widgets_manager->register(new \ElementorVerticalTabs\Vertical_Tabs_Widget());

    // Register Stats Section Widget
    require_once(__DIR__ . '/widgets/stats-section-widget-class.php');
    $widgets_manager->register(new \ElementorStatsSection\Stats_Section_Widget());

    // Register Split Screen Slider Widget
    require_once(__DIR__ . '/widgets/split-screen-slider-widget-class.php');
    $widgets_manager->register(new \ElementorSplitScreenSlider\Split_Screen_Slider_Widget());

    // Register Registration Widget
    require_once(__DIR__ . '/widgets/registration-widget-class.php');
    $widgets_manager->register(new \ElementorTOCCRegistration\Registration_Widget());

    // Register Card Slider Widget
    require_once(__DIR__ . '/widgets/card-slider-widget-class.php');
    $widgets_manager->register(new \ElementorTOCCCardSlider\Card_Slider_Widget());

    // Register Pricing Calculator Widget
    require_once(__DIR__ . '/widgets/pricing-calculator-widget-class.php');
    $widgets_manager->register(new \ElementorTOCCPricingCalculator\Pricing_Calculator_Widget());

    // Register Login Register Widget
    require_once(__DIR__ . '/widgets/login-register-widget-class.php');
    $widgets_manager->register(new \ElementorTOCCLoginRegister\Login_Register_Widget());
}
add_action('elementor/widgets/register', 'register_tabbed_usp_widgets');