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

        add_submenu_page(
            'tocc_registrations',
            'Stripe Settings',
            'Stripe Settings',
            'manage_options',
            'tocc_stripe_settings',
            [self::class, 'render_stripe_settings']
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

    public static function render_stripe_settings() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        // Register settings
        register_setting('tocc_stripe_settings', 'tocc_stripe_secret_key', [
            'sanitize_callback' => function($value) {
                return sanitize_text_field($value);
            }
        ]);

        register_setting('tocc_stripe_settings', 'tocc_stripe_publishable_key', [
            'sanitize_callback' => function($value) {
                return sanitize_text_field($value);
            }
        ]);

        $secret_key = get_option('tocc_stripe_secret_key', '');
        $publishable_key = get_option('tocc_stripe_publishable_key', '');
        ?>

        <div class="wrap tocc-stripe-settings">
            <h1>Stripe Settings</h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('tocc_stripe_settings'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="tocc_stripe_secret_key">Secret Key</label>
                        </th>
                        <td>
                            <input type="password" 
                                   id="tocc_stripe_secret_key" 
                                   name="tocc_stripe_secret_key" 
                                   value="<?php echo esc_attr($secret_key); ?>"
                                   placeholder="sk_test_..."
                                   class="regular-text">
                            <p class="description">
                                <strong>⚠️ Keep this secret!</strong> Get from: <a href="https://dashboard.stripe.com/test/apikeys" target="_blank">Stripe Dashboard</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="tocc_stripe_publishable_key">Publishable Key</label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="tocc_stripe_publishable_key" 
                                   name="tocc_stripe_publishable_key" 
                                   value="<?php echo esc_attr($publishable_key); ?>"
                                   placeholder="pk_test_..."
                                   class="regular-text">
                            <p class="description">
                                Get from: <a href="https://dashboard.stripe.com/test/apikeys" target="_blank">Stripe Dashboard</a>
                            </p>
                        </td>
                    </tr>
                </table>

                <?php submit_button('Save Stripe Settings'); ?>
            </form>

            <hr>

            <h2>Setup Instructions</h2>
            <ol>
                <li><strong>Get Your Keys:</strong> Visit <a href="https://dashboard.stripe.com/test/apikeys" target="_blank">Stripe API Keys</a></li>
                <li><strong>Test Mode:</strong> Use test keys (starting with sk_test_ and pk_test_) to test payments</li>
                <li><strong>Save Keys:</strong> Paste them above and click "Save Stripe Settings"</li>
                <li><strong>Test Payment:</strong> Use test card: 4242 4242 4242 4242</li>
                <li><strong>Go Live:</strong> Switch to live keys (starting with sk_live_ and pk_live_) in production</li>
            </ol>

            <h2>Security Guidelines</h2>
            <ul>
                <li>✅ <strong>Never</strong> commit API keys to version control</li>
                <li>✅ <strong>Never</strong> expose secret keys in frontend code</li>
                <li>✅ Secret keys are stored securely in the database</li>
                <li>✅ Use HTTPS in production</li>
                <li>✅ Enable Stripe webhook verification for production</li>
            </ul>

            <h2>Webhook Configuration (Production Only)</h2>
            <p>For production, configure webhooks in your Stripe Dashboard:</p>
            <ol>
                <li>Go to <a href="https://dashboard.stripe.com/webhooks" target="_blank">Stripe Webhooks</a></li>
                <li>Add endpoint: <code><?php echo esc_html(home_url('/wp-json/tocc/v1/webhook')); ?></code></li>
                <li>Select events: <code>payment_intent.succeeded</code>, <code>payment_intent.payment_failed</code></li>
            </ol>

            <style>
                .tocc-stripe-settings {
                    max-width: 800px;
                    margin: 20px;
                }

                .tocc-stripe-settings h2 {
                    color: #1a3a52;
                    margin-top: 30px;
                    border-bottom: 2px solid #1a3a52;
                    padding-bottom: 10px;
                }

                .tocc-stripe-settings ol,
                .tocc-stripe-settings ul {
                    margin: 15px 0;
                    margin-left: 20px;
                }

                .tocc-stripe-settings li {
                    margin: 8px 0;
                    line-height: 1.6;
                }

                .tocc-stripe-settings code {
                    background: #f5f5f5;
                    padding: 3px 6px;
                    border-radius: 3px;
                    font-family: monospace;
                    font-size: 0.9em;
                }

                input[type="password"],
                input[type="text"] {
                    max-width: 500px;
                }
            </style>
        </div>
        <?php
    }
}

TOCC_Admin_Dashboard::init();
?>
