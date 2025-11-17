<?php
/**
 * Vertical Tabs Widget Class
 * Save as: /widgets/vertical-tabs-widget-class.php
 */

namespace ElementorVerticalTabs;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) exit;

class Vertical_Tabs_Widget extends Widget_Base {

    public function get_name() {
        return 'vertical_tabs_content';
    }

    public function get_title() {
        return 'Vertical Tabs';
    }

    public function get_icon() {
        return 'eicon-v-align-top';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['tabs', 'vertical', 'sidebar', 'accordion', 'content', 'tabbed', 'navigation', 'menu'];
    }

    protected function register_controls() {
        
        // Header Section
        $this->start_controls_section(
            'header_section',
            [
                'label' => 'Header',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => 'Section Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'What can we do to help?',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_header',
            [
                'label' => 'Show Header',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Tabs Section
        $this->start_controls_section(
            'tabs_section',
            [
                'label' => 'Tabs',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => 'Tab Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'Tab Title',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'content_title',
            [
                'label' => 'Content Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'Content Title',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'content_description',
            [
                'label' => 'Content Description',
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>Enter your content description here. You can format it with bold, italic, lists, and more.</p>',
                'show_label' => true,
            ]
        );

        $repeater->add_control(
            'link_text',
            [
                'label' => 'Link Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'Learn more',
            ]
        );

        $repeater->add_control(
            'link_url',
            [
                'label' => 'Link URL',
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'placeholder' => 'https://your-link.com',
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => 'Tab Items',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tab_title' => 'Become a Member',
                        'content_title' => 'Become a Member',
                        'content_description' => '<p>Joining gives your entire company access to a range of exclusive business services, from networking events to bespoke advice and workspaces.</p>',
                        'link_text' => 'Membership Overview',
                    ],
                    [
                        'tab_title' => 'Events and Networking',
                        'content_title' => 'Events and Networking',
                        'content_description' => '<p>Our calendar is packed with networking events, seminars, and workshops designed to help you connect with key decision-makers.</p>',
                        'link_text' => 'View Events Calendar',
                    ],
                    [
                        'tab_title' => 'Policy & Campaigning',
                        'content_title' => 'Policy & Campaigning',
                        'content_description' => '<p>We work closely with government and regulatory bodies to ensure your business voice is heard.</p>',
                        'link_text' => 'Our Policy Work',
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
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
            'tabs_width',
            [
                'label' => 'Tabs Sidebar Width',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 200,
                        'max' => 600,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 35,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vtabs-tabs-section' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vtabs-content-section' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => 'Content Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '50',
                    'right' => '60',
                    'bottom' => '50',
                    'left' => '60',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vtabs-content-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .vtabs-content-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_item_padding',
            [
                'label' => 'Tab Item Padding',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '25',
                    'right' => '40',
                    'bottom' => '25',
                    'left' => '40',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vtabs-tab-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mobile_behavior',
            [
                'label' => 'Mobile Behavior',
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => 'Inline (Content Below Tab)',
                    'accordion' => 'Accordion Style',
                ],
                'description' => 'How tabs behave on mobile devices',
            ]
        );

        $this->end_controls_section();

        // Style Section - Colors
        $this->start_controls_section(
            'style_colors_section',
            [
                'label' => 'Colors',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => 'Primary Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b35',
            ]
        );

        $this->add_control(
            'tabs_bg_color',
            [
                'label' => 'Tabs Background',
                'type' => Controls_Manager::COLOR,
                'default' => '#f0f0f0',
                'selectors' => [
                    '{{WRAPPER}} .vtabs-tabs-section' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label' => 'Content Background',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .vtabs-content-section' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_text_color',
            [
                'label' => 'Tab Text Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#888888',
                'selectors' => [
                    '{{WRAPPER}} .vtabs-tab-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_text_color',
            [
                'label' => 'Active Tab Text Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a3e',
                'selectors' => [
                    '{{WRAPPER}} .vtabs-tab-item.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_bg',
            [
                'label' => 'Tab Hover Background',
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8e8',
                'selectors' => [
                    '{{WRAPPER}} .vtabs-tab-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography Section
        $this->start_controls_section(
            'typography_section',
            [
                'label' => 'Typography',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'label' => 'Section Title',
                'selector' => '{{WRAPPER}} .vtabs-section-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'label' => 'Tab Items',
                'selector' => '{{WRAPPER}} .vtabs-tab-item',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_title_typography',
                'label' => 'Content Title',
                'selector' => '{{WRAPPER}} .vtabs-content-item h2',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_text_typography',
                'label' => 'Content Text',
                'selector' => '{{WRAPPER}} .vtabs-content-item p',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'vtabs-' . $this->get_id();
        
        $tabs = isset($settings['tabs']) && is_array($settings['tabs']) ? $settings['tabs'] : [];
        $mobile_behavior = $settings['mobile_behavior'];
        
        ?>
        <div class="vertical-tabs-widget" id="<?php echo esc_attr($widget_id); ?>">
            <style>
                #<?php echo esc_attr($widget_id); ?> * {
                    box-sizing: border-box;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-container {
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-section-title {
                    padding: 40px;
                    font-size: 32px;
                    font-weight: 600;
                    color: #1a1a3e;
                    background: #f9f9f9;
                    border-bottom: 1px solid #e0e0e0;
                    margin: 0;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-wrapper {
                    display: flex;
                    min-height: 500px;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-tabs-section {
                    border-right: 1px solid #d0d0d0;
                    max-height: 600px;
                    overflow-y: auto;
                    overflow-x: hidden;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-tabs-section::-webkit-scrollbar {
                    width: 8px;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-tabs-section::-webkit-scrollbar-track {
                    background: #e0e0e0;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-tabs-section::-webkit-scrollbar-thumb {
                    background: <?php echo esc_attr($settings['primary_color']); ?>;
                    border-radius: 4px;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-tabs-section::-webkit-scrollbar-thumb:hover {
                    background: <?php echo esc_attr($settings['primary_color']); ?>;
                    opacity: 0.8;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-tab-item {
                    font-size: 18px;
                    font-weight: 500;
                    cursor: pointer;
                    border-bottom: 1px solid #e0e0e0;
                    transition: all 0.3s ease;
                    position: relative;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-tab-item.active {
                    background: <?php echo esc_attr($settings['content_bg_color']); ?>;
                    border-left: 4px solid <?php echo esc_attr($settings['primary_color']); ?>;
                    font-weight: 600;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-tab-item.active::after {
                    content: '';
                    position: absolute;
                    right: 0;
                    top: 0;
                    bottom: 0;
                    width: 1px;
                    background: <?php echo esc_attr($settings['content_bg_color']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-content-section {
                    position: relative;
                    overflow: hidden;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-content-item {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    opacity: 0;
                    transform: translateX(30px);
                    transition: all 0.5s ease;
                    pointer-events: none;
                    padding: 50px 60px;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-content-item.active {
                    opacity: 1;
                    transform: translateX(0);
                    pointer-events: auto;
                    position: relative;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-content-item h2 {
                    font-size: 28px;
                    font-weight: 600;
                    color: #1a1a3e;
                    margin-bottom: 25px;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-content-item p {
                    font-size: 17px;
                    line-height: 1.8;
                    color: #333;
                    margin-bottom: 30px;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-learn-more {
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
                    color: #1a1a3e;
                    font-size: 18px;
                    font-weight: 600;
                    text-decoration: none;
                    transition: all 0.3s ease;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-learn-more:hover {
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    gap: 15px;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-learn-more::after {
                    content: '→';
                    font-size: 22px;
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                }

                #<?php echo esc_attr($widget_id); ?> .vtabs-mobile-inline-content {
                    display: none;
                }

                @media (max-width: 768px) {
                    #<?php echo esc_attr($widget_id); ?> .vtabs-wrapper {
                        display: block;
                    }

                    #<?php echo esc_attr($widget_id); ?> .vtabs-tabs-section {
                        width: 100% !important;
                        border-right: none;
                        max-height: none;
                        overflow-y: visible;
                    }

                    #<?php echo esc_attr($widget_id); ?> .vtabs-content-section {
                        display: none;
                    }

                    #<?php echo esc_attr($widget_id); ?> .vtabs-tab-item {
                        padding: 20px 25px !important;
                        font-size: 16px;
                    }

                    #<?php echo esc_attr($widget_id); ?> .vtabs-tab-item.active {
                        border-left-width: 4px;
                    }

                    #<?php echo esc_attr($widget_id); ?> .vtabs-mobile-inline-content {
                        display: block;
                        padding: 25px;
                        background: white;
                        border-bottom: 1px solid #e0e0e0;
                        animation: slideDown 0.3s ease;
                    }

                    @keyframes slideDown {
                        from {
                            opacity: 0;
                            transform: translateY(-10px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    #<?php echo esc_attr($widget_id); ?> .vtabs-mobile-inline-content h2 {
                        font-size: 22px;
                        font-weight: 600;
                        color: #1a1a3e;
                        margin-bottom: 20px;
                    }

                    #<?php echo esc_attr($widget_id); ?> .vtabs-mobile-inline-content p {
                        font-size: 15px;
                        line-height: 1.7;
                        color: #333;
                        margin-bottom: 25px;
                    }

                    #<?php echo esc_attr($widget_id); ?> .vtabs-section-title {
                        padding: 25px;
                        font-size: 22px;
                    }
                }
            </style>

            <div class="vtabs-container">
                <?php if ($settings['show_header'] === 'yes' && !empty($settings['section_title'])) : ?>
                    <h2 class="vtabs-section-title"><?php echo esc_html($settings['section_title']); ?></h2>
                <?php endif; ?>
                
                <div class="vtabs-wrapper">
                    <!-- Tabs Section -->
                    <div class="vtabs-tabs-section">
                        <?php foreach ($tabs as $index => $tab) : ?>
                            <div class="vtabs-tab-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                                 data-tab-index="<?php echo esc_attr($index); ?>">
                                <?php echo esc_html($tab['tab_title']); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Content Section (Desktop/Tablet) -->
                    <div class="vtabs-content-section">
                        <?php foreach ($tabs as $index => $tab) : ?>
                            <div class="vtabs-content-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                                 data-content-index="<?php echo esc_attr($index); ?>">
                                <?php if (!empty($tab['content_title'])) : ?>
                                    <h2><?php echo esc_html($tab['content_title']); ?></h2>
                                <?php endif; ?>
                                
                                <?php if (!empty($tab['content_description'])) : ?>
                                    <div class="vtabs-description">
                                        <?php echo wp_kses_post($tab['content_description']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($tab['link_text'])) : ?>
                                    <a href="<?php echo esc_url($tab['link_url']['url']); ?>" 
                                       class="vtabs-learn-more"
                                       <?php echo !empty($tab['link_url']['is_external']) ? 'target="_blank"' : ''; ?>
                                       <?php echo !empty($tab['link_url']['nofollow']) ? 'rel="nofollow"' : ''; ?>>
                                        <?php echo esc_html($tab['link_text']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    const widget = document.getElementById('<?php echo esc_js($widget_id); ?>');
                    if (!widget) return;
                    
                    const tabItems = widget.querySelectorAll('.vtabs-tab-item');
                    const contentItems = widget.querySelectorAll('.vtabs-content-item');
                    let isMobile = window.innerWidth <= 768;

                    function handleTabClick(e) {
                        const clickedTab = e.currentTarget;
                        const tabIndex = clickedTab.getAttribute('data-tab-index');
                        
                        isMobile = window.innerWidth <= 768;

                        if (isMobile) {
                            // Remove all existing mobile inline contents
                            widget.querySelectorAll('.vtabs-mobile-inline-content').forEach(el => el.remove());
                            
                            // Remove active class from all tabs
                            tabItems.forEach(t => t.classList.remove('active'));
                            
                            // Add active to clicked tab
                            clickedTab.classList.add('active');
                            
                            // Get tab data
                            const tabs = <?php echo json_encode($tabs); ?>;
                            const tabData = tabs[tabIndex];
                            
                            // Create content element
                            const contentDiv = document.createElement('div');
                            contentDiv.className = 'vtabs-mobile-inline-content';
                            
                            let contentHTML = '';
                            if (tabData.content_title) {
                                contentHTML += '<h2>' + tabData.content_title + '</h2>';
                            }
                            if (tabData.content_description) {
                                contentHTML += '<div class="vtabs-description">' + tabData.content_description + '</div>';
                            }
                            if (tabData.link_text) {
                                const linkUrl = tabData.link_url.url || '#';
                                const target = tabData.link_url.is_external ? ' target="_blank"' : '';
                                const nofollow = tabData.link_url.nofollow ? ' rel="nofollow"' : '';
                                contentHTML += '<a href="' + linkUrl + '" class="vtabs-learn-more"' + target + nofollow + '>' + tabData.link_text + '</a>';
                            }
                            
                            contentDiv.innerHTML = contentHTML;
                            
                            // Insert right after the clicked tab
                            clickedTab.insertAdjacentElement('afterend', contentDiv);
                            
                        } else {
                            // Desktop: Show in content section
                            tabItems.forEach(t => t.classList.remove('active'));
                            contentItems.forEach(c => c.classList.remove('active'));
                            
                            clickedTab.classList.add('active');
                            
                            const targetContent = widget.querySelector(`[data-content-index="${tabIndex}"]`);
                            if (targetContent) {
                                targetContent.classList.add('active');
                            }
                        }
                    }

                    // Attach click handlers
                    tabItems.forEach(tab => {
                        tab.addEventListener('click', handleTabClick);
                    });

                    // Handle window resize
                    let previousWidth = window.innerWidth;
                    
                    window.addEventListener('resize', () => {
                        const currentWidth = window.innerWidth;
                        
                        if ((previousWidth > 768 && currentWidth <= 768) || (previousWidth <= 768 && currentWidth > 768)) {
                            widget.querySelectorAll('.vtabs-mobile-inline-content').forEach(el => el.remove());
                            
                            const activeTab = widget.querySelector('.vtabs-tab-item.active');
                            if (activeTab) {
                                handleTabClick({ currentTarget: activeTab });
                            }
                        }
                        
                        previousWidth = currentWidth;
                    });

                    // Initialize on load
                    window.addEventListener('load', () => {
                        isMobile = window.innerWidth <= 768;
                        if (isMobile) {
                            const activeTab = widget.querySelector('.vtabs-tab-item.active');
                            if (activeTab) {
                                handleTabClick({ currentTarget: activeTab });
                            }
                        }
                    });
                })();
            </script>
        </div>
        <?php
    }
}