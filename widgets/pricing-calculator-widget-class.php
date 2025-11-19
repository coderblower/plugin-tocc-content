<?php
/**
 * Pricing Calculator Widget for Elementor
 * Display an interactive membership cost calculator with employee range selector
 * 
 * Features:
 * - Dropdown selector for employee ranges
 * - Dynamic pricing calculation
 * - VAT calculation (20%)
 * - Direct Debit discount (10%)
 * - Cost breakdown display
 * - Fully customizable pricing
 * - Responsive design
 */

namespace ElementorTOCCPricingCalculator;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) exit;

class Pricing_Calculator_Widget extends Widget_Base {

    public function get_name() {
        return 'tocc_pricing_calculator';
    }

    public function get_title() {
        return 'Pricing Calculator';
    }

    public function get_icon() {
        return 'eicon-calculator';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        // Section: Pricing Data
        $this->start_controls_section(
            'pricing_section',
            [
                'label' => 'Pricing Data',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'calculator_title',
            [
                'label' => 'Calculator Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'Cost breakdown',
                'placeholder' => 'Enter calculator title',
            ]
        );

        $this->add_control(
            'vat_percentage',
            [
                'label' => 'VAT Percentage',
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 0,
                'max' => 100,
                'description' => 'VAT percentage to calculate',
            ]
        );

        $this->add_control(
            'direct_debit_discount',
            [
                'label' => 'Direct Debit Discount (%)',
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 0,
                'max' => 100,
                'description' => 'Discount percentage for Direct Debit',
            ]
        );

        // Repeater for pricing tiers
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tier_name',
            [
                'label' => 'Tier Name',
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'e.g., 1-2 employees',
                'default' => '1-2 employees',
            ]
        );

        $repeater->add_control(
            'tier_key',
            [
                'label' => 'Tier Key (unique)',
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'e.g., 1-2',
                'default' => '1-2',
            ]
        );

        $repeater->add_control(
            'annual_cost',
            [
                'label' => 'Annual Cost (£)',
                'type' => Controls_Manager::NUMBER,
                'default' => 560,
                'min' => 0,
                'step' => 10,
            ]
        );

        $repeater->add_control(
            'registration_fee',
            [
                'label' => 'Registration Fee (£)',
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'min' => 0,
                'step' => 10,
            ]
        );

        $this->add_control(
            'pricing_tiers',
            [
                'label' => 'Pricing Tiers',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tier_name' => '1-2 employees',
                        'tier_key' => '1-2',
                        'annual_cost' => 560,
                        'registration_fee' => 50,
                    ],
                    [
                        'tier_name' => '3-12 employees',
                        'tier_key' => '3-12',
                        'annual_cost' => 720,
                        'registration_fee' => 50,
                    ],
                    [
                        'tier_name' => '13-50 employees',
                        'tier_key' => '13-50',
                        'annual_cost' => 960,
                        'registration_fee' => 50,
                    ],
                    [
                        'tier_name' => '51-100 employees',
                        'tier_key' => '51-100',
                        'annual_cost' => 1440,
                        'registration_fee' => 100,
                    ],
                ],
                'title_field' => '{{{ tier_name }}}',
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
            'title_color',
            [
                'label' => 'Title Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a3a52',
                'selectors' => [
                    '{{WRAPPER}} .pricing-calc-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dropdown_bg_color',
            [
                'label' => 'Dropdown Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pricing-calc-dropdown' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dropdown_text_color',
            [
                'label' => 'Dropdown Text Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a3a52',
                'selectors' => [
                    '{{WRAPPER}} .pricing-calc-dropdown' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'breakdown_bg_color',
            [
                'label' => 'Breakdown Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pricing-calc-breakdown' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'total_row_bg_color',
            [
                'label' => 'Total Row Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#d1dce5',
                'selectors' => [
                    '{{WRAPPER}} .pricing-calc-total-row' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'discount_row_bg_color',
            [
                'label' => 'Discount Row Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a3a52',
                'selectors' => [
                    '{{WRAPPER}} .pricing-calc-discount-row' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'discount_row_text_color',
            [
                'label' => 'Discount Row Text Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .pricing-calc-discount-row .pricing-calc-label,
                    {{WRAPPER}} .pricing-calc-discount-row .pricing-calc-value' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'pricing-calc-' . $this->get_id();
        
        // Build pricing data array
        $pricing_data = [];
        foreach ($settings['pricing_tiers'] as $tier) {
            $pricing_data[$tier['tier_key']] = [
                'annual' => (int)$tier['annual_cost'],
                'registration' => (int)$tier['registration_fee'],
            ];
        }
        
        $vat = (float)$settings['vat_percentage'];
        $discount = (float)$settings['direct_debit_discount'];
        ?>

        <div class="pricing-calculator-widget" id="<?php echo esc_attr($widget_id); ?>">
            <div class="pricing-calc-container">
                <div class="pricing-calc-dropdown-section">
                    <div class="pricing-calc-dropdown-wrapper">
                        <select class="pricing-calc-dropdown" id="<?php echo esc_attr($widget_id); ?>-range">
                            <?php foreach ($settings['pricing_tiers'] as $tier) : ?>
                                <option value="<?php echo esc_attr($tier['tier_key']); ?>">
                                    <?php echo esc_html($tier['tier_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pricing-calc-dropdown-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="pricing-calc-cost-section">
                    <h2 class="pricing-calc-section-title"><?php echo esc_html($settings['calculator_title']); ?></h2>
                    <div class="pricing-calc-breakdown">
                        <div class="pricing-calc-row">
                            <span class="pricing-calc-label">Annual cost</span>
                            <span class="pricing-calc-value" id="<?php echo esc_attr($widget_id); ?>-annual">£560.00</span>
                        </div>
                        <div class="pricing-calc-row">
                            <span class="pricing-calc-label">Registration fees</span>
                            <span class="pricing-calc-value" id="<?php echo esc_attr($widget_id); ?>-registration">£50.00</span>
                        </div>
                        <div class="pricing-calc-row">
                            <span class="pricing-calc-label">VAT (<?php echo esc_html($vat); ?>%)</span>
                            <span class="pricing-calc-value" id="<?php echo esc_attr($widget_id); ?>-vat">£122.00</span>
                        </div>
                        <div class="pricing-calc-row pricing-calc-total-row">
                            <span class="pricing-calc-label">Total cost</span>
                            <span class="pricing-calc-value" id="<?php echo esc_attr($widget_id); ?>-total">£732.00</span>
                        </div>
                        <div class="pricing-calc-row pricing-calc-discount-row">
                            <span class="pricing-calc-label"><?php echo esc_html($discount); ?>% Direct Debit Discount</span>
                            <span class="pricing-calc-value" id="<?php echo esc_attr($widget_id); ?>-discounted">£666.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                #<?php echo esc_attr($widget_id); ?> {
                    padding: 60px 40px;
                    background: #f5f7fa;
                }

                #<?php echo esc_attr($widget_id); ?> * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                .pricing-calc-container {
                    max-width: 800px;
                    margin: 0 auto;
                    display: flex;
                    gap: 60px;
                    align-items: flex-start;
                }

                .pricing-calc-dropdown-section {
                    flex: 0 0 330px;
                }

                .pricing-calc-dropdown-wrapper {
                    position: relative;
                }

                .pricing-calc-dropdown {
                    width: 100%;
                    padding: 14px 40px 14px 18px;
                    font-size: 1rem;
                    color: #1a3a52;
                    background: white;
                    border: 1px solid #d0d5dd;
                    border-radius: 8px;
                    cursor: pointer;
                    appearance: none;
                    font-weight: 500;
                    transition: all 0.2s;
                    font-family: inherit;
                }

                .pricing-calc-dropdown:hover {
                    border-color: #1a3a52;
                }

                .pricing-calc-dropdown:focus {
                    outline: none;
                    border-color: #1a3a52;
                    box-shadow: 0 0 0 3px rgba(26, 58, 82, 0.1);
                }

                .pricing-calc-dropdown-icon {
                    position: absolute;
                    right: 16px;
                    top: 50%;
                    transform: translateY(-50%);
                    pointer-events: none;
                    color: #667085;
                }

                .pricing-calc-cost-section {
                    flex: 1;
                }

                .pricing-calc-section-title {
                    color: #1a3a52;
                    font-size: 1.25rem;
                    font-weight: 600;
                    margin-bottom: 20px;
                }

                .pricing-calc-breakdown {
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                }

                .pricing-calc-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 18px 24px;
                    border-bottom: 1px solid #e5e7eb;
                }

                .pricing-calc-row:last-child {
                    border-bottom: none;
                }

                .pricing-calc-label {
                    color: #1a3a52;
                    font-size: 1rem;
                }

                .pricing-calc-value {
                    color: #1a3a52;
                    font-weight: 600;
                    font-size: 1rem;
                }

                .pricing-calc-total-row {
                    background: #d1dce5;
                    font-weight: 600;
                }

                .pricing-calc-total-row .pricing-calc-label,
                .pricing-calc-total-row .pricing-calc-value {
                    font-weight: 700;
                    font-size: 1.05rem;
                }

                .pricing-calc-discount-row {
                    background: #1a3a52;
                    color: white;
                }

                .pricing-calc-discount-row .pricing-calc-label,
                .pricing-calc-discount-row .pricing-calc-value {
                    color: white;
                    font-weight: 700;
                    font-size: 1.05rem;
                }

                @media (max-width: 768px) {
                    #<?php echo esc_attr($widget_id); ?> {
                        padding: 30px 20px;
                    }

                    .pricing-calc-container {
                        flex-direction: column;
                        gap: 30px;
                    }

                    .pricing-calc-dropdown-section {
                        flex: 1;
                        width: 100%;
                    }
                }
            </style>

            <script>
                (function() {
                    const widgetId = '<?php echo esc_js($widget_id); ?>';
                    const dropdown = document.getElementById(widgetId + '-range');
                    const annualEl = document.getElementById(widgetId + '-annual');
                    const registrationEl = document.getElementById(widgetId + '-registration');
                    const vatEl = document.getElementById(widgetId + '-vat');
                    const totalEl = document.getElementById(widgetId + '-total');
                    const discountedEl = document.getElementById(widgetId + '-discounted');

                    const pricing = <?php echo json_encode($pricing_data); ?>;
                    const vatPercentage = <?php echo json_encode($vat); ?>;
                    const discountPercentage = <?php echo json_encode($discount); ?>;

                    if (!dropdown || !pricing) return;

                    function formatCurrency(amount) {
                        return '£' + parseFloat(amount).toFixed(2);
                    }

                    function updatePricing() {
                        const selected = dropdown.value;
                        const prices = pricing[selected];
                        
                        if (!prices) return;
                        
                        const annual = prices.annual;
                        const registration = prices.registration;
                        const subtotal = annual + registration;
                        const vat = subtotal * (vatPercentage / 100);
                        const total = subtotal + vat;
                        const discounted = total * (1 - discountPercentage / 100);

                        annualEl.textContent = formatCurrency(annual);
                        registrationEl.textContent = formatCurrency(registration);
                        vatEl.textContent = formatCurrency(vat);
                        totalEl.textContent = formatCurrency(total);
                        discountedEl.textContent = formatCurrency(discounted);
                    }

                    dropdown.addEventListener('change', updatePricing);
                    
                    // Initial update
                    setTimeout(updatePricing, 100);
                })();
            </script>
        </div>
        <?php
    }
}

// Register the widget
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Pricing_Calculator_Widget());
?>
