<?php
/**
 * Registration Handler
 * Processes user registration and payment information
 * Database operations and user creation
 */

if (!defined('ABSPATH')) exit;

class TOCC_Registration_Handler {

    /**
     * Initialize hooks
     */
    public static function init() {
        add_action('wp_ajax_tocc_register_user', [self::class, 'handle_registration']);
        add_action('wp_ajax_nopriv_tocc_register_user', [self::class, 'handle_registration']);
        
        // Create custom tables on plugin activation
        add_action('plugins_loaded', [self::class, 'create_tables']);
    }

    /**
     * Create custom database tables for registration tracking
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table for member registrations
        $sql_members = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tocc_members (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            company_name VARCHAR(255) NOT NULL,
            company_website VARCHAR(255),
            company_description TEXT,
            sector VARCHAR(100),
            employee_count VARCHAR(50),
            address_1 VARCHAR(255),
            address_2 VARCHAR(255),
            city VARCHAR(100),
            postcode VARCHAR(20),
            country VARCHAR(100),
            reason_for_joining VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_user (user_id),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        // Table for payment tracking
        $sql_payments = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tocc_payments (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            currency VARCHAR(3) DEFAULT 'GBP',
            status VARCHAR(50) DEFAULT 'pending',
            transaction_id VARCHAR(255),
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY status (status)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($sql_members);
        dbDelta($sql_payments);
    }

    /**
     * Handle user registration via AJAX
     */
    public static function handle_registration() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tocc_register_nonce')) {
            wp_send_json_error(['message' => 'Security check failed']);
        }

        // Get form data
        $data = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : [];
        
        if (!$data || empty($data['step1'])) {
            wp_send_json_error(['message' => 'Invalid registration data']);
        }

        // Extract data from steps
        $step1 = $data['step1'];
        $step2 = $data['step2'] ?? [];
        $step3 = $data['step3'] ?? [];

        // Validate required fields
        $required_fields = ['title', 'first_name', 'last_name', 'email', 'password'];
        foreach ($required_fields as $field) {
            if (empty($step1[$field])) {
                wp_send_json_error(['message' => "Missing required field: $field"]);
            }
        }

        // Check if user already exists
        if (email_exists($step1['email'])) {
            wp_send_json_error(['message' => 'Email already registered']);
        }

        // Create WordPress user
        $user_id = wp_create_user($step1['email'], $step1['password'], $step1['email']);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error(['message' => $user_id->get_error_message()]);
        }

        // Update user profile
        wp_update_user([
            'ID' => $user_id,
            'first_name' => sanitize_text_field($step1['first_name']),
            'last_name' => sanitize_text_field($step1['last_name']),
            'display_name' => sanitize_text_field($step1['first_name'] . ' ' . $step1['last_name']),
        ]);

        // Store additional user meta
        update_user_meta($user_id, 'tocc_title', sanitize_text_field($step1['title']));
        update_user_meta($user_id, 'tocc_job_title', sanitize_text_field($step1['job_title'] ?? ''));
        update_user_meta($user_id, 'tocc_phone', sanitize_text_field($step1['phone'] ?? ''));

        // Store member details
        if (!empty($step2['company_name'])) {
            self::store_member_details($user_id, $step2);
        }

        // Store payment information
        if (!empty($step3['payment_method'])) {
            self::store_payment_info($user_id, $step3);
        }

        // Set user role
        $user = new WP_User($user_id);
        $user->set_role('subscriber');

        // Send welcome email
        self::send_welcome_email($step1['email'], $step1['first_name']);

        wp_send_json_success([
            'message' => 'Registration successful',
            'user_id' => $user_id,
            'redirect' => wp_login_url()
        ]);
    }

    /**
     * Store member company details
     */
    private static function store_member_details($user_id, $data) {
        global $wpdb;

        $insert_data = [
            'user_id' => $user_id,
            'company_name' => sanitize_text_field($data['company_name'] ?? ''),
            'company_website' => esc_url($data['company_website'] ?? ''),
            'company_description' => sanitize_textarea_field($data['company_description'] ?? ''),
            'sector' => sanitize_text_field($data['sector'] ?? ''),
            'employee_count' => sanitize_text_field($data['employee_count'] ?? ''),
            'address_1' => sanitize_text_field($data['address_1'] ?? ''),
            'address_2' => sanitize_text_field($data['address_2'] ?? ''),
            'city' => sanitize_text_field($data['city'] ?? ''),
            'postcode' => sanitize_text_field($data['postcode'] ?? ''),
            'country' => sanitize_text_field($data['country'] ?? ''),
            'reason_for_joining' => sanitize_text_field($data['reason_for_joining'] ?? ''),
        ];

        // Check if record exists
        $existing = $wpdb->get_row(
            $wpdb->prepare("SELECT id FROM {$wpdb->prefix}tocc_members WHERE user_id = %d", $user_id)
        );

        if ($existing) {
            $wpdb->update(
                "{$wpdb->prefix}tocc_members",
                $insert_data,
                ['user_id' => $user_id],
                ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'],
                ['%d']
            );
        } else {
            $wpdb->insert(
                "{$wpdb->prefix}tocc_members",
                $insert_data,
                ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
            );
        }

        // Store in user meta as well
        update_user_meta($user_id, 'tocc_member_data', $insert_data);
    }

    /**
     * Store payment information
     */
    private static function store_payment_info($user_id, $data) {
        global $wpdb;

        $payment_method = sanitize_text_field($data['payment_method'] ?? 'card');
        
        // Determine amount based on payment method
        $amount = ($payment_method === 'direct_debit') ? 666.00 : 732.00;

        $insert_data = [
            'user_id' => $user_id,
            'payment_method' => $payment_method,
            'amount' => $amount,
            'currency' => 'GBP',
            'status' => 'pending', // Will be updated after payment processing
        ];

        $wpdb->insert(
            "{$wpdb->prefix}tocc_payments",
            $insert_data,
            ['%d', '%s', '%f', '%s', '%s']
        );

        // Store in user meta
        update_user_meta($user_id, 'tocc_payment_method', $payment_method);
        update_user_meta($user_id, 'tocc_payment_status', 'pending');
        update_user_meta($user_id, 'tocc_payment_amount', $amount);
    }

    /**
     * Send welcome email to new user
     */
    private static function send_welcome_email($email, $first_name) {
        $subject = 'Welcome to LCCI';
        $message = sprintf(
            'Welcome %s!<br><br>Your registration has been received. Our team will process your membership shortly.<br><br>You can login here: %s',
            sanitize_text_field($first_name),
            wp_login_url()
        );

        wp_mail($email, $subject, $message, ['Content-Type: text/html; charset=UTF-8']);
    }

    /**
     * Get member details
     */
    public static function get_member($user_id) {
        global $wpdb;
        
        return $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tocc_members WHERE user_id = %d", $user_id)
        );
    }

    /**
     * Get payment details
     */
    public static function get_payment($user_id) {
        global $wpdb;
        
        return $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tocc_payments WHERE user_id = %d ORDER BY created_at DESC LIMIT 1", $user_id)
        );
    }

    /**
     * Update payment status
     */
    public static function update_payment_status($user_id, $status, $transaction_id = '') {
        global $wpdb;

        $update_data = ['status' => sanitize_text_field($status)];
        
        if (!empty($transaction_id)) {
            $update_data['transaction_id'] = sanitize_text_field($transaction_id);
        }

        $wpdb->update(
            "{$wpdb->prefix}tocc_payments",
            $update_data,
            ['user_id' => $user_id],
            ['%s', '%s'],
            ['%d']
        );

        // Update user meta
        update_user_meta($user_id, 'tocc_payment_status', $status);
        
        if (!empty($transaction_id)) {
            update_user_meta($user_id, 'tocc_transaction_id', $transaction_id);
        }
    }

    /**
     * Get all members (for admin dashboard)
     */
    public static function get_all_members($limit = 50, $offset = 0) {
        global $wpdb;
        
        $query = "
            SELECT m.*, u.user_email, u.user_login, p.status as payment_status, p.payment_method, p.amount
            FROM {$wpdb->prefix}tocc_members m
            LEFT JOIN {$wpdb->users} u ON m.user_id = u.ID
            LEFT JOIN {$wpdb->prefix}tocc_payments p ON m.user_id = p.user_id
            ORDER BY m.created_at DESC
            LIMIT %d OFFSET %d
        ";
        
        return $wpdb->get_results($wpdb->prepare($query, $limit, $offset));
    }

    /**
     * Count total members
     */
    public static function count_members() {
        global $wpdb;
        
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}tocc_members");
    }
}

// Initialize
TOCC_Registration_Handler::init();
?>
