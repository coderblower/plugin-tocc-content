<?php
/**
 * Login Register Widget for Elementor
 * Display side-by-side login and registration forms with header
 * 
 * Features:
 * - Dual panel layout (login + register)
 * - Logo and tagline header
 * - Badge text
 * - Customizable form labels and placeholders
 * - Password toggle functionality
 * - Forgot password link
 * - Responsive design
 * - AJAX form submission ready
 */

namespace ElementorTOCCLoginRegister;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) exit;

class Login_Register_Widget extends Widget_Base {

    public function get_name() {
        return 'tocc_login_register';
    }

    public function get_title() {
        return 'Login Register';
    }

    public function get_icon() {
        return 'eicon-user-circle';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['login', 'register', 'authentication', 'form', 'account', 'user', 'signin', 'signup'];
    }

    protected function register_controls() {
        // Section: Header
        $this->start_controls_section(
            'header_section',
            [
                'label' => 'Header',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo_text',
            [
                'label' => 'Logo Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'LONDON CHAMBER',
                'placeholder' => 'Enter logo text',
            ]
        );

        $this->add_control(
            'tagline_text',
            [
                'label' => 'Tagline',
                'type' => Controls_Manager::TEXT,
                'default' => 'COMMERCE AND INDUSTRY',
                'placeholder' => 'Enter tagline',
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => 'Badge Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'CONNECT. CHAMPION. SUPPORT',
                'placeholder' => 'Enter badge text',
            ]
        );

        $this->end_controls_section();

        // Section: Login Form
        $this->start_controls_section(
            'login_section',
            [
                'label' => 'Login Form',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'login_title',
            [
                'label' => 'Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'Login',
                'placeholder' => 'Enter login title',
            ]
        );

        $this->add_control(
            'login_description',
            [
                'label' => 'Description',
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'With an LCCI online account you can book events and access your benefits. If you are an existing member or belong to our Community Network, please log in below.',
                'placeholder' => 'Enter login description',
            ]
        );

        $this->add_control(
            'login_email_label',
            [
                'label' => 'Email Label',
                'type' => Controls_Manager::TEXT,
                'default' => 'Email address',
            ]
        );

        $this->add_control(
            'login_email_placeholder',
            [
                'label' => 'Email Placeholder',
                'type' => Controls_Manager::TEXT,
                'default' => 'Email address',
            ]
        );

        $this->add_control(
            'login_password_label',
            [
                'label' => 'Password Label',
                'type' => Controls_Manager::TEXT,
                'default' => 'Password',
            ]
        );

        $this->add_control(
            'login_password_placeholder',
            [
                'label' => 'Password Placeholder',
                'type' => Controls_Manager::TEXT,
                'default' => 'Password',
            ]
        );

        $this->add_control(
            'forgot_password_text',
            [
                'label' => 'Forgot Password Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'Forgot password? Click here to reset.',
            ]
        );

        $this->add_control(
            'forgot_password_url',
            [
                'label' => 'Forgot Password URL',
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com/reset-password',
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'login_button_text',
            [
                'label' => 'Button Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'Login',
            ]
        );

        $this->end_controls_section();

        // Section: Register Form
        $this->start_controls_section(
            'register_section',
            [
                'label' => 'Register Form',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'register_title',
            [
                'label' => 'Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'New? Create an account',
                'placeholder' => 'Enter register title',
            ]
        );

        $this->add_control(
            'register_description',
            [
                'label' => 'Description',
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'If you are a non-member, you will need to register to the London Chamber Community Network to be able to book events.',
                'placeholder' => 'Enter register description',
            ]
        );

        $this->add_control(
            'register_note',
            [
                'label' => 'Important Note',
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Please register with your business email address.',
                'placeholder' => 'Enter important note',
            ]
        );

        $this->add_control(
            'register_email_label',
            [
                'label' => 'Email Label',
                'type' => Controls_Manager::TEXT,
                'default' => 'Email address',
            ]
        );

        $this->add_control(
            'register_email_placeholder',
            [
                'label' => 'Email Placeholder',
                'type' => Controls_Manager::TEXT,
                'default' => 'Email address',
            ]
        );

        $this->add_control(
            'register_button_text',
            [
                'label' => 'Button Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'Register',
            ]
        );

        $this->end_controls_section();

        // Section: Style
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Style',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'logo_color',
            [
                'label' => 'Logo Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6600',
                'selectors' => [
                    '{{WRAPPER}} .login-reg-logo' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => 'Badge Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6600',
                'selectors' => [
                    '{{WRAPPER}} .login-reg-badge' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'section_title_color',
            [
                'label' => 'Section Title Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .login-reg-section h2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'section_border_color',
            [
                'label' => 'Section Title Border Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6600',
                'selectors' => [
                    '{{WRAPPER}} .login-reg-section h2' => 'border-left-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => 'Button Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6600',
                'selectors' => [
                    '{{WRAPPER}} .login-reg-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => 'Button Hover Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#e55a00',
                'selectors' => [
                    '{{WRAPPER}} .login-reg-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => 'Link Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6600',
                'selectors' => [
                    '{{WRAPPER}} .login-reg-forgot-link' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'register_bg_color',
            [
                'label' => 'Register Section Background',
                'type' => Controls_Manager::COLOR,
                'default' => '#f9f9f9',
                'selectors' => [
                    '{{WRAPPER}} .login-reg-register-section' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'login-reg-' . $this->get_id();
        
        $forgot_url = !empty($settings['forgot_password_url']['url']) 
            ? $settings['forgot_password_url']['url'] 
            : '#';
        ?>

        <div class="login-register-widget" id="<?php echo esc_attr($widget_id); ?>">
            <header class="login-reg-header">
                <div class="login-reg-logo"><?php echo esc_html($settings['logo_text']); ?></div>
                <div class="login-reg-tagline"><?php echo esc_html($settings['tagline_text']); ?></div>
                <div class="login-reg-badge"><?php echo esc_html($settings['badge_text']); ?></div>
            </header>

            <div class="login-reg-container">
                <!-- Login Section -->
                <div class="login-reg-section login-reg-login-section">
                    <h2><?php echo esc_html($settings['login_title']); ?></h2>
                    <p><?php echo esc_html($settings['login_description']); ?></p>
                    
                    <form class="login-reg-form" id="<?php echo esc_attr($widget_id); ?>-login">
                        <div class="login-reg-form-group">
                            <label for="<?php echo esc_attr($widget_id); ?>-email">
                                <?php echo esc_html($settings['login_email_label']); ?>
                            </label>
                            <input 
                                type="email" 
                                id="<?php echo esc_attr($widget_id); ?>-email" 
                                placeholder="<?php echo esc_attr($settings['login_email_placeholder']); ?>"
                                required
                            >
                        </div>

                        <div class="login-reg-form-group">
                            <label for="<?php echo esc_attr($widget_id); ?>-password">
                                <?php echo esc_html($settings['login_password_label']); ?>
                            </label>
                            <div class="login-reg-password-toggle">
                                <input 
                                    type="password" 
                                    id="<?php echo esc_attr($widget_id); ?>-password" 
                                    placeholder="<?php echo esc_attr($settings['login_password_placeholder']); ?>"
                                    required
                                >
                                <span class="login-reg-eye-icon" data-toggle="<?php echo esc_attr($widget_id); ?>-password">👁</span>
                            </div>
                        </div>

                        <a href="<?php echo esc_url($forgot_url); ?>" class="login-reg-forgot-link">
                            <?php echo esc_html($settings['forgot_password_text']); ?>
                        </a>

                        <button type="submit" class="login-reg-button">
                            <?php echo esc_html($settings['login_button_text']); ?>
                        </button>
                    </form>
                </div>

                <!-- Register Section -->
                <div class="login-reg-section login-reg-register-section">
                    <h2><?php echo esc_html($settings['register_title']); ?></h2>
                    <p><?php echo esc_html($settings['register_description']); ?></p>
                    <?php if (!empty($settings['register_note'])) : ?>
                        <p><strong><?php echo esc_html($settings['register_note']); ?></strong></p>
                    <?php endif; ?>
                    
                    <form class="login-reg-form" id="<?php echo esc_attr($widget_id); ?>-register">
                        <div class="login-reg-form-group">
                            <label for="<?php echo esc_attr($widget_id); ?>-register-email">
                                <?php echo esc_html($settings['register_email_label']); ?>
                            </label>
                            <input 
                                type="email" 
                                id="<?php echo esc_attr($widget_id); ?>-register-email" 
                                placeholder="<?php echo esc_attr($settings['register_email_placeholder']); ?>"
                                required
                            >
                        </div>

                        <button type="submit" class="login-reg-button">
                            <?php echo esc_html($settings['register_button_text']); ?>
                        </button>
                    </form>
                </div>
            </div>

            <style>
                #<?php echo esc_attr($widget_id); ?> * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                #<?php echo esc_attr($widget_id); ?> {
                    background-color: #f5f5f5;
                }

                .login-reg-header {
                    background-color: white;
                    padding: 20px 40px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }

                .login-reg-logo {
                    font-weight: 600;
                    font-size: 18px;
                    color: #ff6600;
                }

                .login-reg-tagline {
                    font-size: 12px;
                    color: #666;
                    margin-top: 4px;
                }

                .login-reg-badge {
                    display: inline-block;
                    background-color: #ff6600;
                    color: white;
                    padding: 8px 12px;
                    font-size: 11px;
                    font-weight: 600;
                    border-radius: 4px;
                    margin-top: 10px;
                    letter-spacing: 0.5px;
                }

                .login-reg-container {
                    display: flex;
                    gap: 40px;
                    max-width: 1200px;
                    margin: 60px auto;
                    padding: 0 20px;
                }

                .login-reg-section {
                    flex: 1;
                    background: white;
                    padding: 60px;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                }

                .login-reg-section h2 {
                    font-size: 28px;
                    font-weight: 600;
                    margin-bottom: 20px;
                    color: #1a1a1a;
                    border-left: 4px solid #ff6600;
                    padding-left: 20px;
                }

                .login-reg-section p {
                    color: #666;
                    line-height: 1.6;
                    margin-bottom: 30px;
                    font-size: 15px;
                }

                .login-reg-form-group {
                    margin-bottom: 24px;
                }

                .login-reg-form-group label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: 500;
                    font-size: 14px;
                    color: #1a1a1a;
                }

                .login-reg-form-group input {
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #ddd;
                    border-radius: 24px;
                    font-size: 14px;
                    transition: border-color 0.2s;
                    font-family: inherit;
                }

                .login-reg-form-group input:focus {
                    outline: none;
                    border-color: #ff6600;
                    box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
                }

                .login-reg-form-group input::placeholder {
                    color: #999;
                }

                .login-reg-password-toggle {
                    position: relative;
                }

                .login-reg-eye-icon {
                    position: absolute;
                    right: 16px;
                    top: 50%;
                    transform: translateY(-50%);
                    cursor: pointer;
                    color: #999;
                    font-size: 18px;
                    user-select: none;
                }

                .login-reg-forgot-link {
                    display: inline-block;
                    margin-top: 12px;
                    color: #ff6600;
                    text-decoration: none;
                    font-size: 14px;
                    font-weight: 600;
                }

                .login-reg-forgot-link:hover {
                    text-decoration: underline;
                }

                .login-reg-button {
                    width: 100%;
                    padding: 14px;
                    background-color: #ff6600;
                    color: white;
                    border: none;
                    border-radius: 24px;
                    font-size: 14px;
                    font-weight: 600;
                    cursor: pointer;
                    margin-top: 24px;
                    transition: background-color 0.2s;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-family: inherit;
                }

                .login-reg-button:hover {
                    background-color: #e55a00;
                }

                .login-reg-button:active {
                    background-color: #cc5000;
                }

                .login-reg-register-section {
                    background-color: #f9f9f9;
                }

                @media (max-width: 768px) {
                    .login-reg-container {
                        flex-direction: column;
                        gap: 20px;
                        margin: 30px auto;
                    }

                    .login-reg-section {
                        padding: 40px;
                    }

                    .login-reg-section h2 {
                        font-size: 24px;
                    }

                    .login-reg-header {
                        padding: 15px 20px;
                    }
                }
            </style>

            <script>
                (function() {
                    const widgetId = '<?php echo esc_js($widget_id); ?>';
                    const eyeIcons = document.querySelectorAll('[data-toggle="' + widgetId + '-password"]');
                    const passwordInput = document.getElementById(widgetId + '-password');

                    if (eyeIcons.length > 0 && passwordInput) {
                        eyeIcons.forEach(icon => {
                            icon.addEventListener('click', function() {
                                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                                passwordInput.setAttribute('type', type);
                                this.textContent = type === 'password' ? '👁' : '👁‍🗨';
                            });
                        });
                    }

                    // Prevent form submission for demo (can be integrated with real forms)
                    const forms = document.querySelectorAll('#' + widgetId + '-login, #' + widgetId + '-register');
                    forms.forEach(form => {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            console.log('Form submitted:', this.id);
                            // Add your form handling here
                        });
                    });
                })();
            </script>
        </div>
        <?php
    }
}

// Register the widget
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Login_Register_Widget());
?>
