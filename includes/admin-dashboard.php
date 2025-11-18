<?php
/**
 * Admin Dashboard for Registration Management
 * View and manage user registrations and payment statuses
 */

if (!defined('ABSPATH')) exit;

class TOCC_Admin_Dashboard {

    public static function init() {
        add_action('admin_menu', [self::class, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_styles']);
    }

    public static function add_admin_menu() {
        add_menu_page(
            'LCCI Registrations',
            'LCCI Registrations',
            'manage_options',
            'tocc_registrations',
            [self::class, 'render_dashboard'],
            'dashicons-groups',
            6
        );

        add_submenu_page(
            'tocc_registrations',
            'All Members',
            'All Members',
            'manage_options',
            'tocc_registrations',
            [self::class, 'render_dashboard']
        );

        add_submenu_page(
            'tocc_registrations',
            'Pending Payments',
            'Pending Payments',
            'manage_options',
            'tocc_pending_payments',
            [self::class, 'render_pending_payments']
        );

        add_submenu_page(
            'tocc_registrations',
            'Completed Payments',
            'Completed Payments',
            'manage_options',
            'tocc_completed_payments',
            [self::class, 'render_completed_payments']
        );
    }

    public static function enqueue_admin_styles() {
        if (!isset($_GET['page']) || strpos($_GET['page'], 'tocc_') !== 0) {
            return;
        }

        wp_enqueue_style(
            'tocc-admin-style',
            plugin_dir_url(__FILE__) . '../assets/admin-style.css'
        );
    }

    public static function render_dashboard() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        include(__DIR__ . '/../admin/dashboard.php');
    }

    public static function render_pending_payments() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        include(__DIR__ . '/../admin/pending-payments.php');
    }

    public static function render_completed_payments() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        include(__DIR__ . '/../admin/completed-payments.php');
    }
}

TOCC_Admin_Dashboard::init();
?>
