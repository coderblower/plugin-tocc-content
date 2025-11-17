<?php
/**
 * Stats Section Widget Class
 * Save as: /widgets/stats-section-widget-class.php
 */

namespace ElementorStatsSection;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit;

class Stats_Section_Widget extends Widget_Base {

    public function get_name() {
        return 'stats_section';
    }

    public function get_title() {
        return 'Stats Section';
    }

    public function get_icon() {
        return 'eicon-counter';
    }

    public function get_categories() {
        return ['tabbed-widgets', 'general'];
    }

    public function get_keywords() {
        return ['stats', 'statistics', 'counter', 'numbers', 'facts', 'metrics', 'achievements', 'data'];
    }

    protected function register_controls() {
        
        // Stats Cards Section
        $this->start_controls_section(
            'stats_section',
            [
                'label' => 'Stats Cards',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'stat_number',
            [
                'label' => 'Stat Number',
                'type' => Controls_Manager::TEXT,
                'default' => '11,000+',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'stat_description',
            [
                'label' => 'Description',
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'business network in the community.',
                'rows' => 3,
            ]
        );

        $this->add_control(
            'stats',
            [
                'label' => 'Stats Items',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'stat_number' => '11,000+',
                        'stat_description' => 'business network in the LCCI community.',
                    ],
                    [
                        'stat_number' => '200+',
                        'stat_description' => 'events each year.',
                    ],
                    [
                        'stat_number' => '140+ years',
                        'stat_description' => 'of actively lobbying for businesses in London.',
                    ],
                ],
                'title_field' => '{{{ stat_number }}}',
            ]
        );

        $this->end_controls_section();

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_title',
            [
                'label' => 'Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'London Chamber of Commerce and Industry',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'content_description',
            [
                'label' => 'Description',
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>From innovative SMEs to global corporations, becoming a member provides businesses with a wealth of opportunities.</p>',
                'show_label' => true,
            ]
        );

        $this->add_control(
            'show_button',
            [
                'label' => 'Show Button',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => 'Button Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'Join Membership',
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => 'Button Link',
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => 'Button Icon',
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Layout Settings Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => 'Layout Settings',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->add_responsive_control(
            'stats_gap',
            [
                'label' => 'Stats Gap',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
            ]
        );

        $this->add_responsive_control(
            'stats_bottom_spacing',
            [
                'label' => 'Stats Bottom Spacing',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
            ]
        );

        $this->add_responsive_control(
            'section_padding',
            [
                'label' => 'Section Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '60',
                    'right' => '40',
                    'bottom' => '60',
                    'left' => '40',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .stats-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'stat_card_padding',
            [
                'label' => 'Stat Card Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '50',
                    'right' => '40',
                    'bottom' => '50',
                    'left' => '40',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .stat-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_alignment',
            [
                'label' => 'Content Alignment',
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => 'Left',
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Center',
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => 'Right',
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .content-section' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_max_width',
            [
                'label' => 'Content Max Width',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1400,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .content-section' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Background
        $this->start_controls_section(
            'style_background_section',
            [
                'label' => 'Background',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'section_background',
                'label' => 'Background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .stats-section',
                'fields_options' => [
                    'background' => [
                        'default' => 'gradient',
                    ],
                    'color' => [
                        'default' => '#1a2b4a',
                    ],
                    'color_b' => [
                        'default' => '#2d4a6f',
                    ],
                    'gradient_angle' => [
                        'default' => [
                            'unit' => 'deg',
                            'size' => 135,
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Stats Cards
        $this->start_controls_section(
            'style_stats_section',
            [
                'label' => 'Stats Cards',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label' => 'Card Background',
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.95)',
                'selectors' => [
                    '{{WRAPPER}} .stat-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => 'Border Radius',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .stat-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'stat_number_color',
            [
                'label' => 'Number Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a2b4a',
                'selectors' => [
                    '{{WRAPPER}} .stat-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stat_number_typography',
                'label' => 'Number Typography',
                'selector' => '{{WRAPPER}} .stat-number',
            ]
        );

        $this->add_control(
            'stat_description_color',
            [
                'label' => 'Description Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .stat-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'stat_description_typography',
                'label' => 'Description Typography',
                'selector' => '{{WRAPPER}} .stat-description',
            ]
        );

        $this->end_controls_section();

        // Style - Content
        $this->start_controls_section(
            'style_content_section',
            [
                'label' => 'Content',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_title_color',
            [
                'label' => 'Title Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .content-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_title_typography',
                'label' => 'Title Typography',
                'selector' => '{{WRAPPER}} .content-title',
            ]
        );

        $this->add_control(
            'content_description_color',
            [
                'label' => 'Description Color',
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.95)',
                'selectors' => [
                    '{{WRAPPER}} .content-description' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .content-description p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_description_typography',
                'label' => 'Description Typography',
                'selector' => '{{WRAPPER}} .content-description, {{WRAPPER}} .content-description p',
            ]
        );

        $this->end_controls_section();

        // Style - Button
        $this->start_controls_section(
            'style_button_section',
            [
                'label' => 'Button',
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => 'Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b35',
                'selectors' => [
                    '{{WRAPPER}} .cta-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background',
            [
                'label' => 'Hover Background',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff8555',
                'selectors' => [
                    '{{WRAPPER}} .cta-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => 'Text Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .cta-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .cta-button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => 'Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '18',
                    'right' => '40',
                    'bottom' => '18',
                    'left' => '40',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cta-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => 'Border Radius',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cta-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'stats-' . $this->get_id();
        
        $stats = isset($settings['stats']) && is_array($settings['stats']) ? $settings['stats'] : [];
        $columns = $settings['columns'];
        $stats_gap = isset($settings['stats_gap']) ? $settings['stats_gap'] : ['unit' => 'px', 'size' => 30];
        $stats_bottom_spacing = isset($settings['stats_bottom_spacing']) ? $settings['stats_bottom_spacing'] : ['unit' => 'px', 'size' => 50];
        
        ?>
        <section class="stats-section" id="<?php echo esc_attr($widget_id); ?>">
            <style>
                #<?php echo esc_attr($widget_id); ?> .stats-container {
                    max-width: 1400px;
                    margin: 0 auto;
                }

                #<?php echo esc_attr($widget_id); ?> .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr);
                    gap: <?php echo esc_attr($stats_gap['size'] . $stats_gap['unit']); ?>;
                    margin-bottom: <?php echo esc_attr($stats_bottom_spacing['size'] . $stats_bottom_spacing['unit']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .stat-card {
                    text-align: center;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                #<?php echo esc_attr($widget_id); ?> .stat-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
                }

                #<?php echo esc_attr($widget_id); ?> .stat-number {
                    font-size: 56px;
                    font-weight: 700;
                    margin-bottom: 20px;
                    line-height: 1;
                }

                #<?php echo esc_attr($widget_id); ?> .stat-description {
                    font-size: 18px;
                    line-height: 1.6;
                }

                #<?php echo esc_attr($widget_id); ?> .content-section {
                    margin: 0 auto;
                }

                #<?php echo esc_attr($widget_id); ?> .content-title {
                    font-size: 38px;
                    font-weight: 600;
                    margin-bottom: 30px;
                    line-height: 1.3;
                }

                #<?php echo esc_attr($widget_id); ?> .content-description {
                    font-size: 19px;
                    line-height: 1.8;
                    margin-bottom: 40px;
                }

                #<?php echo esc_attr($widget_id); ?> .content-description p {
                    margin-bottom: 15px;
                }

                #<?php echo esc_attr($widget_id); ?> .content-description p:last-child {
                    margin-bottom: 0;
                }

                #<?php echo esc_attr($widget_id); ?> .cta-button {
                    display: inline-flex;
                    align-items: center;
                    gap: 12px;
                    border: none;
                    cursor: pointer;
                    text-decoration: none;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
                }

                #<?php echo esc_attr($widget_id); ?> .cta-button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 25px rgba(255, 107, 53, 0.4);
                }

                #<?php echo esc_attr($widget_id); ?> .button-icon {
                    font-size: 20px;
                }

                @media (max-width: 1024px) {
                    #<?php echo esc_attr($widget_id); ?> .stats-grid {
                        grid-template-columns: repeat(<?php echo esc_attr($settings['columns_tablet'] ?? '2'); ?>, 1fr);
                    }
                }

                @media (max-width: 768px) {
                    #<?php echo esc_attr($widget_id); ?> .stats-grid {
                        grid-template-columns: repeat(<?php echo esc_attr($settings['columns_mobile'] ?? '1'); ?>, 1fr);
                    }
                }
            </style>

            <div class="stats-container">
                <?php if (!empty($stats)) : ?>
                    <div class="stats-grid">
                        <?php foreach ($stats as $stat) : ?>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo esc_html($stat['stat_number']); ?></div>
                                <div class="stat-description"><?php echo esc_html($stat['stat_description']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="content-section">
                    <?php if (!empty($settings['content_title'])) : ?>
                        <h2 class="content-title"><?php echo esc_html($settings['content_title']); ?></h2>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['content_description'])) : ?>
                        <div class="content-description">
                            <?php echo wp_kses_post($settings['content_description']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_button'] === 'yes' && !empty($settings['button_text'])) : ?>
                        <a href="<?php echo esc_url($settings['button_link']['url']); ?>" 
                           class="cta-button"
                           <?php echo !empty($settings['button_link']['is_external']) ? 'target="_blank"' : ''; ?>
                           <?php echo !empty($settings['button_link']['nofollow']) ? 'rel="nofollow"' : ''; ?>>
                            <?php if (!empty($settings['button_icon']['value'])) : ?>
                                <span class="button-icon">
                                    <?php \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            <?php endif; ?>
                            <span><?php echo esc_html($settings['button_text']); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }
}