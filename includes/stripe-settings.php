<?php
/**
 * Stripe Settings Page
 * Configure Stripe API keys for payment processing
 */

if (!defined('ABSPATH')) exit;

class TOCC_Stripe_Settings {

    public static function init() {
        add_action('admin_menu', [self::class, 'add_settings_page']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    public static function add_settings_page() {
        add_submenu_page(
            'tocc_registrations',
            'Stripe Settings',
            'Stripe Settings',
            'manage_options',
            'tocc_stripe_settings',
            [self::class, 'render_settings_page']
        );
    }

    public static function register_settings() {
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
    }

    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $secret_key = get_option('tocc_stripe_secret_key', '');
        $publishable_key = get_option('tocc_stripe_publishable_key', '');
        ?>

        <div class="wrap tocc-stripe-settings">
            <h1>Stripe Payment Settings</h1>

            <div class="tocc-info-box">
                <h3>Configure Your Stripe API Keys</h3>
                <p>To enable Stripe payments, you need to add your Stripe API keys below. You can find these in your Stripe Dashboard.</p>
                <p><strong>Important:</strong> Use your <strong>Test Keys</strong> while testing, and switch to <strong>Live Keys</strong> when ready for production.</p>
            </div>

            <form method="post" action="options.php">
                <?php settings_fields('tocc_stripe_settings'); ?>

                <table class="form-table tocc-stripe-table">
                    <tr valign="top">
                        <th scope="row" style="width: 200px;">
                            <label for="tocc_stripe_secret_key">Secret Key *</label>
                        </th>
                        <td>
                            <input 
                                type="password" 
                                id="tocc_stripe_secret_key" 
                                name="tocc_stripe_secret_key" 
                                value="<?php echo esc_attr($secret_key); ?>" 
                                class="regular-text"
                                placeholder="sk_test_..."
                                style="font-family: monospace;"
                            />
                            <p class="description">
                                Your Stripe Secret Key. Keep this secure - it should never be exposed on the client side.
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="tocc_stripe_publishable_key">Publishable Key *</label>
                        </th>
                        <td>
                            <input 
                                type="text" 
                                id="tocc_stripe_publishable_key" 
                                name="tocc_stripe_publishable_key" 
                                value="<?php echo esc_attr($publishable_key); ?>" 
                                class="regular-text"
                                placeholder="pk_test_..."
                                style="font-family: monospace;"
                            />
                            <p class="description">
                                Your Stripe Publishable Key. This can be safely exposed on the client side and is used in the checkout form.
                            </p>
                        </td>
                    </tr>
                </table>

                <?php submit_button('Save Stripe Settings'); ?>
            </form>

            <div class="tocc-stripe-info">
                <h3>How to Get Your Stripe Keys</h3>
                <ol>
                    <li>Sign in to your <a href="https://dashboard.stripe.com" target="_blank">Stripe Dashboard</a></li>
                    <li>Click on "Developers" in the left menu</li>
                    <li>Click on "API Keys"</li>
                    <li>You'll see your "Publishable key" and "Secret key" under the "Standard keys" section</li>
                    <li>Copy and paste them into the fields above</li>
                    <li>Click "Save Stripe Settings"</li>
                </ol>
            </div>

            <div class="tocc-stripe-warning">
                <h3>⚠️ Important Notes</h3>
                <ul>
                    <li><strong>Secret Key:</strong> Must be kept secret and stored securely on the server side only</li>
                    <li><strong>Publishable Key:</strong> Can be public and is used in the checkout form</li>
                    <li><strong>Test Mode:</strong> Use test keys (starting with sk_test_ and pk_test_) to test payments</li>
                    <li><strong>Live Mode:</strong> Switch to live keys (starting with sk_live_ and pk_live_) for real payments</li>
                    <li><strong>Security:</strong> Never commit secret keys to version control</li>
                </ul>
            </div>

            <div class="tocc-stripe-webhook">
                <h3>Webhook Configuration</h3>
                <p>To track payment status, configure a webhook in your Stripe Dashboard:</p>
                <ol>
                    <li>Go to Developers > Webhooks</li>
                    <li>Click "Add endpoint"</li>
                    <li>Enter your webhook URL: <code><?php echo esc_url(home_url('/wp-json/tocc/v1/stripe-webhook')); ?></code></li>
                    <li>Select events: <strong>payment_intent.succeeded</strong>, <strong>payment_intent.payment_failed</strong></li>
                    <li>Click "Add endpoint"</li>
                    <li>Copy the signing secret and add it to your settings if needed</li>
                </ol>
            </div>
        </div>

        <style>
            .tocc-stripe-settings {
                margin: 20px;
            }

            .tocc-info-box {
                background: #e3f2fd;
                border-left: 4px solid #2196F3;
                padding: 20px;
                margin-bottom: 30px;
                border-radius: 4px;
            }

            .tocc-stripe-table {
                background: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 30px;
            }

            .tocc-stripe-info,
            .tocc-stripe-warning,
            .tocc-stripe-webhook {
                background: white;
                border: 1px solid #ddd;
                padding: 20px;
                margin-bottom: 20px;
                border-radius: 4px;
            }

            .tocc-stripe-info h3,
            .tocc-stripe-warning h3,
            .tocc-stripe-webhook h3 {
                margin-top: 0;
            }

            .tocc-stripe-info ol,
            .tocc-stripe-warning ul,
            .tocc-stripe-webhook ol {
                margin-left: 20px;
            }

            .tocc-stripe-info li,
            .tocc-stripe-warning li,
            .tocc-stripe-webhook li {
                margin-bottom: 10px;
                line-height: 1.6;
            }

            .tocc-stripe-warning {
                border-left: 4px solid #ff9800;
                background: #fff3e0;
            }

            code {
                background: #f5f5f5;
                padding: 2px 6px;
                border-radius: 3px;
                font-family: monospace;
                font-size: 0.9em;
            }

            input[type="password"],
            input[type="text"] {
                max-width: 500px;
            }
        </style>
        <?php
    }
}

TOCC_Stripe_Settings::init();
?>
