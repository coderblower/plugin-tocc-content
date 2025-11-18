<?php
/**
 * Registration Widget with Payment Status
 * Save as: /widgets/registration-widget-class.php
 * 
 * Features:
 * - Multi-step registration form
 * - User data stored in WordPress database
 * - Payment status tracking
 * - User login integration
 * - Admin dashboard to view registrations
 */

namespace ElementorTOCCRegistration;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Registration_Widget extends Widget_Base {

    public function get_name() {
        return 'tocc_registration';
    }

    public function get_title() {
        return 'TOCC Registration Form';
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['registration', 'form', 'payment', 'membership'];
    }

    public function get_script_depends() {
        return ['jquery'];
    }

    protected function register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Registration Form Settings',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'form_title',
            [
                'label' => 'Form Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'Become an LCCI Premier Plus Member',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'form_subtitle',
            [
                'label' => 'Form Subtitle',
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Sign up to membership online in a few easy steps. Please note LCCI does not currently accept Amex payments online.',
                'rows' => 3,
            ]
        );

        $this->add_control(
            'support_phone',
            [
                'label' => 'Support Phone',
                'type' => Controls_Manager::TEXT,
                'default' => '+44 (0)20 7203 1713',
            ]
        );

        $this->add_control(
            'support_email',
            [
                'label' => 'Support Email',
                'type' => Controls_Manager::TEXT,
                'default' => 'mssupport@londonchamber.co.uk',
            ]
        );

        $this->add_control(
            'card_payment_cost',
            [
                'label' => 'Card Payment Cost',
                'type' => Controls_Manager::TEXT,
                'default' => '732.00',
            ]
        );

        $this->add_control(
            'direct_debit_cost',
            [
                'label' => 'Direct Debit Cost (10% discount)',
                'type' => Controls_Manager::TEXT,
                'default' => '666.00',
            ]
        );

        $this->add_control(
            'stripe_publishable_key',
            [
                'label' => 'Stripe Publishable Key',
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'pk_test_...',
                'description' => 'Your Stripe publishable key',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'login_redirect_url',
            [
                'label' => 'Login Redirect URL',
                'type' => Controls_Manager::URL,
                'placeholder' => '/login',
                'description' => 'Where to redirect after successful payment',
                'default' => [
                    'url' => '/login',
                ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Style',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => 'Primary Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a3a52',
            ]
        );

        $this->add_control(
            'accent_color',
            [
                'label' => 'Accent Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b35',
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => 'Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#e8ebed',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'tocc-reg-' . $this->get_id();
        
        // Get Stripe publishable key from widget or WordPress settings
        $stripe_key = !empty($settings['stripe_publishable_key']) 
            ? $settings['stripe_publishable_key'] 
            : get_option('tocc_stripe_publishable_key', '');
        
        // Validate Stripe key
        if (!empty($stripe_key)) {
            // Check if secret key was used instead of publishable key
            if (strpos($stripe_key, 'sk_') === 0) {
                echo '<div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 4px; margin: 20px; color: #856404;">';
                echo '<strong>⚠️ Stripe Configuration Error:</strong> ';
                echo 'You appear to be using your <strong>Secret Key</strong> instead of your <strong>Publishable Key</strong>. ';
                echo 'Secret keys start with "sk_" and should NEVER be used in frontend code. ';
                echo 'Please use your Publishable Key (starts with "pk_") instead. ';
                echo 'Update this in WordPress Admin > LCCI Registrations > Stripe Settings.';
                echo '</div>';
                return;
            }
        }
        
        // Get login redirect URL
        $redirect_url = !empty($settings['login_redirect_url']['url']) 
            ? $settings['login_redirect_url']['url'] 
            : wp_login_url();
        ?>

        <div class="tocc-registration-widget" id="<?php echo esc_attr($widget_id); ?>">
            <style>
                #<?php echo esc_attr($widget_id); ?> * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                #<?php echo esc_attr($widget_id); ?> {
                    background: <?php echo esc_attr($settings['background_color']); ?>;
                    padding: 40px 20px;
                    min-height: 100vh;
                }

                #<?php echo esc_attr($widget_id); ?> .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    display: flex;
                    gap: 60px;
                }

                #<?php echo esc_attr($widget_id); ?> .left-section {
                    flex: 0 0 280px;
                }

                #<?php echo esc_attr($widget_id); ?> .header {
                    margin-bottom: 40px;
                }

                #<?php echo esc_attr($widget_id); ?> h1 {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-size: 2rem;
                    margin-bottom: 12px;
                }

                #<?php echo esc_attr($widget_id); ?> .subtitle {
                    color: #475467;
                    font-size: 0.95rem;
                    font-style: italic;
                    line-height: 1.5;
                }

                #<?php echo esc_attr($widget_id); ?> .step-indicator {
                    display: flex;
                    flex-direction: column;
                    gap: 0;
                    margin-bottom: 40px;
                }

                #<?php echo esc_attr($widget_id); ?> .step {
                    display: flex;
                    align-items: center;
                    gap: 16px;
                    position: relative;
                }

                #<?php echo esc_attr($widget_id); ?> .step:not(:last-child)::after {
                    content: '';
                    position: absolute;
                    left: 19px;
                    top: 40px;
                    width: 2px;
                    height: 35px;
                    background: #d0d5dd;
                }

                #<?php echo esc_attr($widget_id); ?> .step.active:not(:last-child)::after {
                    background: <?php echo esc_attr($settings['primary_color']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .step-number {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    border: 2px solid #d0d5dd;
                    background: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 600;
                    color: #667085;
                    font-size: 1.1rem;
                    flex-shrink: 0;
                }

                #<?php echo esc_attr($widget_id); ?> .step.active .step-number,
                #<?php echo esc_attr($widget_id); ?> .step.completed .step-number {
                    background: <?php echo esc_attr($settings['primary_color']); ?>;
                    border-color: <?php echo esc_attr($settings['primary_color']); ?>;
                    color: white;
                }

                #<?php echo esc_attr($widget_id); ?> .step-label {
                    color: #667085;
                    font-size: 0.875rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                #<?php echo esc_attr($widget_id); ?> .step.active .step-label {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-weight: 600;
                }

                #<?php echo esc_attr($widget_id); ?> .support-box {
                    background: #d4dfe8;
                    padding: 24px;
                    border-radius: 8px;
                }

                #<?php echo esc_attr($widget_id); ?> .support-title {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-weight: 600;
                    font-size: 1rem;
                    margin-bottom: 16px;
                }

                #<?php echo esc_attr($widget_id); ?> .support-text {
                    color: #475467;
                    font-size: 0.875rem;
                    line-height: 1.6;
                    margin-bottom: 16px;
                }

                #<?php echo esc_attr($widget_id); ?> .support-contact {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-size: 0.875rem;
                    margin-bottom: 8px;
                }

                #<?php echo esc_attr($widget_id); ?> .right-section {
                    flex: 1;
                    background: white;
                    padding: 50px;
                    border-radius: 8px;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                }

                #<?php echo esc_attr($widget_id); ?> .form-step {
                    display: none;
                }

                #<?php echo esc_attr($widget_id); ?> .form-step.active {
                    display: block;
                }

                #<?php echo esc_attr($widget_id); ?> .form-title {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-size: 1.75rem;
                    margin-bottom: 30px;
                    font-weight: 600;
                }

                #<?php echo esc_attr($widget_id); ?> .form-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                    margin-bottom: 24px;
                }

                #<?php echo esc_attr($widget_id); ?> .form-group {
                    display: flex;
                    flex-direction: column;
                }

                #<?php echo esc_attr($widget_id); ?> .form-group.full-width {
                    grid-column: 1 / -1;
                }

                #<?php echo esc_attr($widget_id); ?> label {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-size: 0.875rem;
                    margin-bottom: 8px;
                    font-weight: 500;
                }

                #<?php echo esc_attr($widget_id); ?> input,
                #<?php echo esc_attr($widget_id); ?> select {
                    padding: 12px 16px;
                    border: 1px solid #d0d5dd;
                    border-radius: 6px;
                    font-size: 1rem;
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    background: white;
                    transition: all 0.2s;
                }

                #<?php echo esc_attr($widget_id); ?> input:focus,
                #<?php echo esc_attr($widget_id); ?> select:focus {
                    outline: none;
                    border-color: <?php echo esc_attr($settings['primary_color']); ?>;
                    box-shadow: 0 0 0 3px rgba(26, 58, 82, 0.1);
                }

                #<?php echo esc_attr($widget_id); ?> select {
                    appearance: none;
                    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%23667085' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
                    background-repeat: no-repeat;
                    background-position: right 12px center;
                    background-size: 12px 8px;
                    padding-right: 36px;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none;
                    cursor: pointer;
                }

                #<?php echo esc_attr($widget_id); ?> .cost-display {
                    display: flex;
                    gap: 12px;
                    margin-top: 16px;
                }

                #<?php echo esc_attr($widget_id); ?> .cost-badge {
                    padding: 10px 18px;
                    border-radius: 6px;
                    font-size: 0.875rem;
                    font-weight: 600;
                }

                #<?php echo esc_attr($widget_id); ?> .cost-badge.regular {
                    background: #d4dfe8;
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .cost-badge.direct-debit {
                    background: <?php echo esc_attr($settings['primary_color']); ?>;
                    color: white;
                }

                #<?php echo esc_attr($widget_id); ?> .info-text {
                    color: #667085;
                    font-size: 0.875rem;
                    line-height: 1.6;
                    margin-top: 12px;
                }

                #<?php echo esc_attr($widget_id); ?> .info-link {
                    color: <?php echo esc_attr($settings['accent_color']); ?>;
                    text-decoration: underline;
                    cursor: pointer;
                }

                #<?php echo esc_attr($widget_id); ?> .password-requirements {
                    background: #f9fafb;
                    padding: 20px;
                    border-radius: 6px;
                    margin-top: 20px;
                }

                #<?php echo esc_attr($widget_id); ?> .password-requirements h4 {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-size: 0.875rem;
                    margin-bottom: 12px;
                    font-weight: 600;
                }

                #<?php echo esc_attr($widget_id); ?> .requirement {
                    color: #667085;
                    font-size: 0.875rem;
                    margin-bottom: 8px;
                    padding-left: 20px;
                    position: relative;
                }

                #<?php echo esc_attr($widget_id); ?> .requirement::before {
                    content: '•';
                    position: absolute;
                    left: 6px;
                    color: <?php echo esc_attr($settings['accent_color']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .payment-options {
                    display: flex;
                    flex-direction: column;
                    gap: 16px;
                    margin-bottom: 30px;
                }

                #<?php echo esc_attr($widget_id); ?> .payment-option {
                    border: 2px solid #d0d5dd;
                    border-radius: 8px;
                    padding: 20px;
                    cursor: pointer;
                    transition: all 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }

                #<?php echo esc_attr($widget_id); ?> .payment-option:hover {
                    border-color: <?php echo esc_attr($settings['primary_color']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .payment-option.selected {
                    border-color: <?php echo esc_attr($settings['primary_color']); ?>;
                    background: #f0f4f7;
                }

                #<?php echo esc_attr($widget_id); ?> .radio {
                    width: 20px;
                    height: 20px;
                    border: 2px solid #d0d5dd;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 12px;
                }

                #<?php echo esc_attr($widget_id); ?> .payment-option.selected .radio {
                    border-color: <?php echo esc_attr($settings['primary_color']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .radio-dot {
                    width: 10px;
                    height: 10px;
                    background: <?php echo esc_attr($settings['primary_color']); ?>;
                    border-radius: 50%;
                    display: none;
                }

                #<?php echo esc_attr($widget_id); ?> .payment-option.selected .radio-dot {
                    display: block;
                }

                #<?php echo esc_attr($widget_id); ?> .payment-label {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-weight: 600;
                }

                #<?php echo esc_attr($widget_id); ?> .payment-price {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-weight: 700;
                    font-size: 1.1rem;
                }

                #<?php echo esc_attr($widget_id); ?> .checkbox-group {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    margin-top: 20px;
                }

                #<?php echo esc_attr($widget_id); ?> .checkbox-group input[type="checkbox"] {
                    width: 20px;
                    height: 20px;
                    margin-top: 2px;
                    cursor: pointer;
                }

                #<?php echo esc_attr($widget_id); ?> .checkbox-label {
                    color: #475467;
                    font-size: 0.875rem;
                    line-height: 1.6;
                }

                #<?php echo esc_attr($widget_id); ?> .button-group {
                    display: flex;
                    gap: 16px;
                    margin-top: 40px;
                }

                #<?php echo esc_attr($widget_id); ?> .btn {
                    padding: 14px 28px;
                    border-radius: 6px;
                    font-size: 1rem;
                    font-weight: 600;
                    cursor: pointer;
                    border: none;
                    transition: all 0.2s;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                #<?php echo esc_attr($widget_id); ?> .btn-back {
                    background: white;
                    color: <?php echo esc_attr($settings['accent_color']); ?>;
                    border: 1px solid <?php echo esc_attr($settings['accent_color']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .btn-back:hover {
                    background: #fff5f2;
                }

                #<?php echo esc_attr($widget_id); ?> .btn-primary {
                    background: <?php echo esc_attr($settings['accent_color']); ?>;
                    color: white;
                }

                #<?php echo esc_attr($widget_id); ?> .btn-primary:hover {
                    opacity: 0.9;
                }

                #<?php echo esc_attr($widget_id); ?> .btn-primary:disabled {
                    background: #d0d5dd;
                    cursor: not-allowed;
                }

                #<?php echo esc_attr($widget_id); ?> .error-message {
                    color: #d32f2f;
                    font-size: 0.875rem;
                    margin-top: 8px;
                }

                #<?php echo esc_attr($widget_id); ?> .success-message {
                    color: #2e7d32;
                    font-size: 0.875rem;
                    margin-top: 8px;
                }

                #<?php echo esc_attr($widget_id); ?> .overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(26, 58, 82, 0.85);
                    display: none;
                    align-items: center;
                    justify-content: center;
                    z-index: 1000;
                }

                #<?php echo esc_attr($widget_id); ?> .overlay.active {
                    display: flex;
                }

                #<?php echo esc_attr($widget_id); ?> .overlay-content {
                    background: white;
                    padding: 40px;
                    border-radius: 12px;
                    text-align: center;
                    max-width: 500px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                }

                #<?php echo esc_attr($widget_id); ?> .overlay-title {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    font-size: 1.5rem;
                    margin-bottom: 16px;
                    font-weight: 600;
                }

                #<?php echo esc_attr($widget_id); ?> .overlay-text {
                    color: #475467;
                    line-height: 1.6;
                    margin-bottom: 12px;
                }

                @media (max-width: 968px) {
                    #<?php echo esc_attr($widget_id); ?> .container {
                        flex-direction: column;
                    }
                    #<?php echo esc_attr($widget_id); ?> .left-section {
                        flex: 1;
                    }
                    #<?php echo esc_attr($widget_id); ?> .right-section {
                        padding: 30px;
                    }
                    #<?php echo esc_attr($widget_id); ?> .form-grid {
                        grid-template-columns: 1fr;
                    }
                }
            </style>

            <div class="container">
                <!-- Left Section -->
                <div class="left-section">
                    <div class="header">
                        <h1><?php echo esc_html($settings['form_title']); ?></h1>
                        <p class="subtitle"><?php echo esc_html($settings['form_subtitle']); ?></p>
                    </div>

                    <div class="step-indicator">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Your Details</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Company Details</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Payment Method</div>
                        </div>
                    </div>

                    <div class="support-box">
                        <div class="support-title">Need support?</div>
                        <p class="support-text">If you would like to speak with our Membership team please call or email us.</p>
                        <div class="support-contact">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328z"/>
                            </svg>
                            <?php echo esc_html($settings['support_phone']); ?>
                        </div>
                        <div class="support-contact">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                            </svg>
                            <?php echo esc_html($settings['support_email']); ?>
                        </div>
                    </div>
                </div>

                <!-- Right Section - Registration Form -->
                <div class="right-section">
                    <!-- Step 1: Your Details -->
                    <div class="form-step active" id="step1">
                        <h2 class="form-title">Your Details</h2>
                        <form id="step1-form" class="tocc-reg-form">
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label>Title*</label>
                                    <select name="title" required>
                                        <option value="">Select title</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>First name*</label>
                                    <input type="text" name="first_name" placeholder="First name" required>
                                </div>
                                <div class="form-group">
                                    <label>Surname*</label>
                                    <input type="text" name="last_name" placeholder="Surname" required>
                                </div>
                                <div class="form-group">
                                    <label>Job title*</label>
                                    <input type="text" name="job_title" placeholder="Job title" required>
                                </div>
                                <div class="form-group">
                                    <label>Telephone number*</label>
                                    <input type="tel" name="phone" placeholder="Telephone number" required>
                                </div>
                                <div class="form-group full-width">
                                    <label>Email address*</label>
                                    <input type="email" name="email" placeholder="your@email.com" required>
                                </div>
                                <div class="form-group">
                                    <label>Password*</label>
                                    <input type="password" name="password" placeholder="••••••••" required>
                                    <div class="error-message"></div>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password*</label>
                                    <input type="password" name="confirm_password" placeholder="••••••••" required>
                                    <div class="error-message"></div>
                                </div>
                            </div>

                            <div class="password-requirements">
                                <h4>Password Requirements</h4>
                                <div class="requirement">At least 8 characters long</div>
                                <div class="requirement">At least one Uppercase letter A-Z</div>
                                <div class="requirement">At least one Lowercase letter a-z</div>
                                <div class="requirement">At least one special character (-!@#$%^&*_-+=`|(){}[]:;"'<>,.?/)</div>
                            </div>

                            <div class="button-group">
                                <button type="button" class="btn btn-primary" onclick="toccRegFormNext(event, 1)">CONTINUE</button>
                            </div>
                        </form>
                    </div>

                    <!-- Step 2: Company Details -->
                    <div class="form-step" id="step2">
                        <h2 class="form-title">Company Details</h2>
                        <form id="step2-form" class="tocc-reg-form">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Company name*</label>
                                    <input type="text" name="company_name" placeholder="Company name" required>
                                </div>
                                <div class="form-group">
                                    <label>Company website*</label>
                                    <input type="url" name="company_website" placeholder="https://example.com" required>
                                </div>
                                <div class="form-group full-width">
                                    <label>Number of employees*</label>
                                    <select name="employee_count" required>
                                        <option value="">Select employee count</option>
                                        <option value="1-2">Premier Plus / 1-2 employees</option>
                                        <option value="3-12">3-12 employees</option>
                                        <option value="13-50">13-50 employees</option>
                                        <option value="51-100">51-100 employees</option>
                                        <option value="101-250">101-250 employees</option>
                                        <option value="251-500">251-500 employees</option>
                                        <option value="501-1000">501-1000 employees</option>
                                        <option value="1001+">1001+ employees</option>
                                    </select>
                                    <div class="cost-display">
                                        <div class="cost-badge regular">Card: £<?php echo esc_html($settings['card_payment_cost']); ?> inc VAT</div>
                                        <div class="cost-badge direct-debit">Direct Debit: £<?php echo esc_html($settings['direct_debit_cost']); ?> inc VAT</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>What does your company do?*</label>
                                    <input type="text" name="company_description" placeholder="Company description" required>
                                </div>
                                <div class="form-group">
                                    <label>Sector*</label>
                                    <select name="sector" required>
                                        <option value="">Select sector</option>
                                        <option value="Hospitality">Hospitality & Leisure</option>
                                        <option value="Technology">Technology</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group full-width">
                                    <label>Reason for joining*</label>
                                    <select name="reason_for_joining" required>
                                        <option value="">Select reason</option>
                                        <option value="Networking">Networking</option>
                                        <option value="Resources">Resources & Support</option>
                                        <option value="Events">Events</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Address line 1*</label>
                                    <input type="text" name="address_1" placeholder="Street address" required>
                                </div>
                                <div class="form-group">
                                    <label>Address line 2</label>
                                    <input type="text" name="address_2" placeholder="Apartment, suite, etc.">
                                </div>
                                <div class="form-group">
                                    <label>City*</label>
                                    <input type="text" name="city" placeholder="City" required>
                                </div>
                                <div class="form-group">
                                    <label>Postcode*</label>
                                    <input type="text" name="postcode" placeholder="Postcode" required>
                                </div>
                                <div class="form-group full-width">
                                    <label>Country*</label>
                                    <select name="country" required>
                                        <option value="">Select country</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="Island of Man">Island of Man</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <p class="info-text">When you join, we give you permission to contact you about our events and services. Your company will also be listed in the Members' Directory.</p>

                            <div class="checkbox-group">
                                <input type="checkbox" name="terms" required>
                                <label class="checkbox-label">I agree to the <span class="info-link">terms and conditions</span> and have read the <span class="info-link">Privacy Policy</span></label>
                            </div>

                            <div class="button-group">
                                <button type="button" class="btn btn-back" onclick="toccRegFormPrev(event, 1)">BACK</button>
                                <button type="button" class="btn btn-primary" onclick="toccRegFormNext(event, 2)">CONTINUE</button>
                            </div>
                        </form>
                    </div>

                    <!-- Step 3: Payment Method -->
                    <div class="form-step" id="step3">
                        <h2 class="form-title">Payment Method</h2>
                        <p class="info-text">The membership subscription fee is annual. Pay by Direct Debit to save 10%. We currently do not accept Amex payments online.</p>

                        <form id="step3-form" class="tocc-reg-form">
                            <div class="payment-options">
                                <div class="payment-option" onclick="toccSelectPayment(event, 'card')">
                                    <div class="payment-option-left">
                                        <div class="radio">
                                            <div class="radio-dot"></div>
                                        </div>
                                        <span class="payment-label">Debit or Credit Card</span>
                                    </div>
                                    <span class="payment-price">£<?php echo esc_html($settings['card_payment_cost']); ?>/year</span>
                                    <input type="hidden" name="payment_method" value="">
                                </div>
                                <div class="payment-option selected" onclick="toccSelectPayment(event, 'direct_debit')">
                                    <div class="payment-option-left">
                                        <div class="radio">
                                            <div class="radio-dot"></div>
                                        </div>
                                        <div>
                                            <div class="payment-label">Direct Debit</div>
                                            <p class="info-text" style="margin-top: 4px;">Save 10% with Direct Debit</p>
                                        </div>
                                    </div>
                                    <span class="payment-price">£<?php echo esc_html($settings['direct_debit_cost']); ?>/year</span>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="button" class="btn btn-back" onclick="toccRegFormPrev(event, 2)">BACK</button>
                                <button type="submit" class="btn btn-primary" onclick="toccRegFormSubmit(event)">SUBMIT REGISTRATION</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="overlay" id="tocc-overlay">
                <div class="overlay-content">
                    <h3 class="overlay-title">Processing...</h3>
                    <p class="overlay-text">Please wait while we process your registration.</p>
                    <p class="overlay-text"><strong>Do not refresh the page...</strong></p>
                </div>
            </div>
        </div>

        <script src="https://js.stripe.com/v3/"></script>
        <script>
            let toccCurrentStep = 1;
            let toccFormData = {
                step1: {},
                step2: {},
                step3: {}
            };

            function toccUpdateSteps() {
                document.querySelectorAll('#<?php echo esc_attr($widget_id); ?> .form-step').forEach(step => {
                    step.classList.remove('active');
                });
                document.getElementById(`step${toccCurrentStep}`).classList.add('active');

                document.querySelectorAll('#<?php echo esc_attr($widget_id); ?> .step').forEach(step => {
                    const stepNum = parseInt(step.dataset.step);
                    step.classList.remove('active', 'completed');
                    if (stepNum === toccCurrentStep) {
                        step.classList.add('active');
                    } else if (stepNum < toccCurrentStep) {
                        step.classList.add('completed');
                    }
                });
            }

            function toccRegFormNext(event, currentStep) {
                event.preventDefault();
                
                const form = document.getElementById(`step${currentStep}-form`);
                if (form && !form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                if (currentStep === 1) {
                    const password = form.querySelector('input[name="password"]').value;
                    const confirmPassword = form.querySelector('input[name="confirm_password"]').value;
                    
                    if (password !== confirmPassword) {
                        alert('Passwords do not match');
                        return;
                    }
                    
                    if (!toccValidatePassword(password)) {
                        alert('Password does not meet requirements');
                        return;
                    }
                }

                toccCollectFormData(currentStep);
                
                if (toccCurrentStep < 3) {
                    toccCurrentStep++;
                    toccUpdateSteps();
                }
            }

            function toccRegFormPrev(event, currentStep) {
                event.preventDefault();
                toccCollectFormData(currentStep);
                
                if (toccCurrentStep > 1) {
                    toccCurrentStep--;
                    toccUpdateSteps();
                }
            }

            function toccRegFormSubmit(event) {
                event.preventDefault();
                
                const form = document.getElementById('step3-form');
                toccCollectFormData(3);

                // Determine selected payment method
                const selectedPayment = document.querySelector('#<?php echo esc_attr($widget_id); ?> .payment-option.selected');
                const paymentMethod = selectedPayment.textContent.includes('Direct Debit') ? 'direct_debit' : 'card';
                toccFormData.step3.payment_method = paymentMethod;

                document.getElementById('tocc-overlay').classList.add('active');

                if (paymentMethod === 'card') {
                    // Handle Stripe payment
                    toccProcessStripePayment();
                } else {
                    // Handle Direct Debit
                    toccRegisterUser();
                }
            }

            function toccProcessStripePayment() {
                const stripeKey = '<?php echo esc_js($stripe_key); ?>';
                
                if (!stripeKey || stripeKey.trim() === '') {
                    document.getElementById('tocc-overlay').classList.remove('active');
                    alert('Stripe is not configured. Please add your Stripe publishable key in the widget settings or WordPress admin.');
                    console.error('Stripe publishable key is missing');
                    return;
                }
                
                // Check if secret key was mistakenly used
                if (stripeKey.startsWith('sk_')) {
                    document.getElementById('tocc-overlay').classList.remove('active');
                    alert('❌ ERROR: You are using your Secret Key instead of your Publishable Key!\n\nSecret keys start with "sk_" and must NEVER be used in frontend code.\n\nPlease update your Stripe Settings with your Publishable Key (starts with "pk_")');
                    console.error('Secret key detected instead of publishable key:', stripeKey.substring(0, 20) + '...');
                    return;
                }
                
                const stripe = Stripe(stripeKey);
                
                if (!stripe) {
                    document.getElementById('tocc-overlay').classList.remove('active');
                    alert('Stripe initialization failed. Please check your publishable key.');
                    return;
                }

                // Create payment intent via server
                fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'tocc_create_payment_intent',
                        nonce: '<?php echo wp_create_nonce('tocc_payment_nonce'); ?>',
                        data: JSON.stringify(toccFormData),
                        amount: 73200 // Amount in cents
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network error: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Payment Intent Response:', data);
                    
                    if (!data.success) {
                        throw new Error(data.data?.message || 'Payment setup failed');
                    }
                    
                    if (!data.data?.client_secret) {
                        throw new Error('Server error: No client_secret received');
                    }

                    // Use Stripe Payment Element
                    const elements = stripe.elements({
                        clientSecret: data.data.client_secret
                    });

                    const paymentElement = elements.create('payment');
                    
                    // Show payment form in overlay
                    const overlay = document.getElementById('tocc-overlay');
                    const content = overlay.querySelector('.overlay-content');
                    
                    content.innerHTML = `
                        <h3 class="overlay-title">Complete Payment</h3>
                        <div id="payment-element-container"></div>
                        <button type="button" class="btn btn-primary" id="submit-payment" style="margin-top: 20px; width: 100%;">
                            PAY £732.00
                        </button>
                    `;

                    paymentElement.mount('#payment-element-container');

                    // Handle payment submission
                    document.getElementById('submit-payment').addEventListener('click', async () => {
                        const submitBtn = document.getElementById('submit-payment');
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Processing...';

                        const { error } = await stripe.confirmPayment({
                            elements,
                            confirmParams: {
                                return_url: window.location.href,
                            },
                        });

                        if (error) {
                            content.innerHTML = `
                                <h3 class="overlay-title">Payment Failed</h3>
                                <p class="overlay-text" style="color: #d32f2f;">${error.message}</p>
                                <button type="button" class="btn btn-primary" onclick="location.reload()">Try Again</button>
                            `;
                        }
                    });
                })
                .catch(error => {
                    console.error('Payment Error:', error);
                    document.getElementById('tocc-overlay').classList.remove('active');
                    alert('Payment Error: ' + error.message);
                });
            }

            function toccRegisterUser() {
                // Send user registration to server
                fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'tocc_register_user',
                        nonce: '<?php echo wp_create_nonce('tocc_register_nonce'); ?>',
                        data: JSON.stringify(toccFormData)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tocc-overlay').classList.remove('active');
                    
                    if (data.success) {
                        const redirectUrl = '<?php echo esc_js($redirect_url); ?>';
                        window.location.href = redirectUrl;
                    } else {
                        alert('Error: ' + (data.message || 'Registration failed'));
                    }
                })
                .catch(error => {
                    document.getElementById('tocc-overlay').classList.remove('active');
                    alert('Error submitting registration: ' + error);
                });
            }

            function toccCollectFormData(stepNumber) {
                const form = document.getElementById(`step${stepNumber}-form`);
                const formData = new FormData(form);
                
                for (let [key, value] of formData.entries()) {
                    toccFormData[`step${stepNumber}`][key] = value;
                }
            }

            function toccValidatePassword(password) {
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[-!@#$%^&*_+=`|(){}[\]:;"'<>,.?/]/.test(password);
                const isLongEnough = password.length >= 8;
                
                return hasUppercase && hasLowercase && (hasNumber || hasSpecial) && isLongEnough;
            }

            function toccSelectPayment(event, type) {
                event.currentTarget.parentElement.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('selected');
                });
                event.currentTarget.classList.add('selected');
            }

            toccUpdateSteps();
        </script>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var widgetId = 'tocc-reg-' + view.getID();
        #>
        <div class="tocc-registration-widget" id="{{ widgetId }}">
            <p style="text-align: center; color: #666; padding: 40px;">
                Registration form will be displayed here on the frontend
            </p>
        </div>
        <?php
    }
}
