<?php
/**
 * Tabbed USP Widget Class
 * Save as: /widgets/tabbed-usp-widget-class.php
 */

namespace ElementorTabbedUSP;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class Tabbed_USP_Widget extends Widget_Base {

    public function get_name() {
        return 'tabbed_usp_content';
    }

    public function get_title() {
        return 'Tabbed USP Content';
    }

    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['tabs', 'usp', 'features', 'benefits'];
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
            'main_title',
            [
                'label' => 'Main Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'What Makes Us Stand Out',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'cta_text',
            [
                'label' => 'CTA Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'About LCCI',
            ]
        );

        $this->add_control(
            'cta_link',
            [
                'label' => 'CTA Link',
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
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
            'tab_icon',
            [
                'label' => 'Tab Icon',
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $repeater->add_control(
            'tab_title',
            [
                'label' => 'Tab Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'Connect',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'tab_summary',
            [
                'label' => 'Tab Summary',
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Join a community that connects you to opportunities.',
                'rows' => 3,
            ]
        );

        // Content Items Repeater (Nested)
        $content_repeater = new Repeater();

        $content_repeater->add_control(
            'item_icon',
            [
                'label' => 'Icon',
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $content_repeater->add_control(
            'item_title',
            [
                'label' => 'Title',
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $content_repeater->add_control(
            'item_description',
            [
                'label' => 'Description',
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
            ]
        );

        $content_repeater->add_control(
            'item_link_text',
            [
                'label' => 'Link Text',
                'type' => Controls_Manager::TEXT,
            ]
        );

        $content_repeater->add_control(
            'item_link',
            [
                'label' => 'Link URL',
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'content_items',
            [
                'label' => 'Content Items',
                'type' => Controls_Manager::REPEATER,
                'fields' => $content_repeater->get_controls(),
                'default' => [
                    [
                        'item_title' => 'Feature 1',
                        'item_description' => 'Description for feature 1',
                    ],
                ],
                'title_field' => '{{{ item_title }}}',
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
                        'tab_title' => 'Connect',
                        'tab_summary' => 'Join a community that connects you to opportunities.',
                        'content_items' => [
                            [
                                'item_title' => 'Make Business Relationships',
                                'item_description' => 'LCCI is a centre of connectivity for members in the heart of London.',
                                'item_link_text' => 'About membership',
                            ],
                        ],
                    ],
                    [
                        'tab_title' => 'Champion',
                        'tab_summary' => 'Advocating for the London community where it matters.',
                        'content_items' => [
                            [
                                'item_title' => 'Policy and Campaigning',
                                'item_description' => 'We constantly engage with members to understand and then champion their interests.',
                                'item_link_text' => 'Learn more',
                            ],
                        ],
                    ],
                    [
                        'tab_title' => 'Support',
                        'tab_summary' => 'Support for you and the London economy.',
                        'content_items' => [
                            [
                                'item_title' => 'Member Services',
                                'item_description' => 'We deliver dedicated support services to benefit hundreds of businesses every year.',
                                'item_link_text' => 'Membership Overview',
                            ],
                        ],
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();

        // Style Section
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
                'default' => '#FF5722',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => 'Secondary Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a3a52',
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => 'Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#f5f5f5',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'tusp-' . $this->get_id();
        ?>
        <div class="tabbed-usp-widget" id="<?php echo esc_attr($widget_id); ?>">
            <style>
                #<?php echo esc_attr($widget_id); ?> {
                    background: <?php echo esc_attr($settings['background_color']); ?>;
                    padding: 60px 20px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-container {
                    max-width: 1200px;
                    margin: 0 auto;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 40px;
                    flex-wrap: wrap;
                    gap: 20px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-main-title {
                    font-size: 32px;
                    font-weight: 700;
                    color: #1a1a1a;
                    margin: 0;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-cta {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    text-decoration: none;
                    font-weight: 600;
                    transition: gap 0.3s;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-cta:hover {
                    gap: 12px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-cta svg {
                    width: 16px;
                    height: 16px;
                    fill: currentColor;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tabs-wrapper {
                    display: grid;
                    grid-template-columns: 300px 1fr;
                    gap: 30px;
                    margin-top: 30px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tab-list {
                    display: flex;
                    flex-direction: column;
                    gap: 0;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tab-button {
                    background: white;
                    border: none;
                    padding: 24px 20px;
                    text-align: left;
                    cursor: pointer;
                    transition: all 0.3s;
                    border-left: 4px solid transparent;
                    display: flex;
                    align-items: flex-start;
                    gap: 15px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tab-button:hover {
                    background: #fafafa;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tab-button.active {
                    border-left-color: <?php echo esc_attr($settings['primary_color']); ?>;
                    background: white;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tab-icon {
                    width: 40px;
                    height: 40px;
                    flex-shrink: 0;
                    object-fit: contain;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tab-content-wrap {
                    flex: 1;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tab-title {
                    font-size: 20px;
                    font-weight: 700;
                    color: #1a1a1a;
                    margin: 0 0 8px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-tab-summary {
                    font-size: 14px;
                    color: #666;
                    margin: 0;
                    line-height: 1.5;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-content-area {
                    background: white;
                    border-radius: 8px;
                    padding: 40px;
                    min-height: 400px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-content-panel {
                    display: none;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-content-panel.active {
                    display: block;
                    animation: fadeIn 0.3s;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-items-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 30px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item {
                    display: flex;
                    flex-direction: row;
                    align-items: flex-start;
                    gap: 20px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item-icon-wrapper {
                    width: 80px;
                    height: 80px;
                    background: <?php echo esc_attr($settings['secondary_color']); ?>;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 12px;
                    flex-shrink: 0;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item-icon {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    filter: brightness(0) invert(1);
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item-content {
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                    flex: 1;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item-title {
                    font-size: 18px;
                    font-weight: 700;
                    color: #1a1a1a;
                    margin: 0;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item-description {
                    font-size: 14px;
                    color: #666;
                    line-height: 1.6;
                    margin: 0;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item-link {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    color: <?php echo esc_attr($settings['primary_color']); ?>;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 14px;
                    transition: gap 0.3s;
                    width: fit-content;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item-link:hover {
                    gap: 12px;
                }
                #<?php echo esc_attr($widget_id); ?> .tusp-item-link svg {
                    width: 14px;
                    height: 14px;
                    fill: currentColor;
                }

                @media (max-width: 1024px) {
                    #<?php echo esc_attr($widget_id); ?> .tusp-tabs-wrapper {
                        grid-template-columns: 1fr;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-tab-list {
                        flex-direction: row;
                        overflow-x: auto;
                        gap: 10px;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-tab-button {
                        border-left: none;
                        border-bottom: 4px solid transparent;
                        min-width: 200px;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-tab-button.active {
                        border-left: none;
                        border-bottom-color: <?php echo esc_attr($settings['primary_color']); ?>;
                    }
                }

                @media (max-width: 768px) {
                    #<?php echo esc_attr($widget_id); ?> .tusp-item {
                        flex-direction: column;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-item-icon-wrapper {
                        width: 100%;
                        max-width: 100px;
                        margin: 0 auto;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-item-content {
                        text-align: center;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-item-link {
                        justify-content: center;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-content-area {
                        padding: 20px;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-items-grid {
                        grid-template-columns: 1fr;
                    }
                    #<?php echo esc_attr($widget_id); ?> .tusp-main-title {
                        font-size: 24px;
                    }
                }
            </style>

            <div class="tusp-container">
                <div class="tusp-header">
                    <h2 class="tusp-main-title"><?php echo esc_html($settings['main_title']); ?></h2>
                    <?php if (!empty($settings['cta_text'])) : ?>
                        <a href="<?php echo esc_url($settings['cta_link']['url']); ?>" class="tusp-cta">
                            <span><?php echo esc_html($settings['cta_text']); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0.04 0 13.16 11.99">
                                <path d="M13.2 5.999a.986.986 0 00-.254-.619L7.937.28a1.112 1.112 0 00-1.37-.075.91.91 0 00.01 1.313L10.099 5.1H.939a.9.9 0 100 1.8h9.16l-3.522 3.582a.961.961 0 00-.01 1.313 1.1 1.1 0 001.37-.075l5.009-5.1a.847.847 0 00.254-.619z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="tusp-tabs-wrapper">
                    <div class="tusp-tab-list">
                        <?php foreach ($settings['tabs'] as $index => $tab) : ?>
                            <button class="tusp-tab-button <?php echo $index === 0 ? 'active' : ''; ?>" 
                                    data-tab="<?php echo esc_attr($index); ?>">
                                <?php if (!empty($tab['tab_icon']['url'])) : ?>
                                    <img src="<?php echo esc_url($tab['tab_icon']['url']); ?>" 
                                         alt="<?php echo esc_attr($tab['tab_title']); ?>" 
                                         class="tusp-tab-icon">
                                <?php endif; ?>
                                <div class="tusp-tab-content-wrap">
                                    <h3 class="tusp-tab-title"><?php echo esc_html($tab['tab_title']); ?></h3>
                                    <p class="tusp-tab-summary"><?php echo esc_html($tab['tab_summary']); ?></p>
                                </div>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="tusp-content-area">
                        <?php foreach ($settings['tabs'] as $index => $tab) : ?>
                            <div class="tusp-content-panel <?php echo $index === 0 ? 'active' : ''; ?>" 
                                 data-panel="<?php echo esc_attr($index); ?>">
                                <div class="tusp-items-grid">
                                    <?php if (!empty($tab['content_items'])) : ?>
                                        <?php foreach ($tab['content_items'] as $item) : ?>
                                            <div class="tusp-item">
                                                <?php if (!empty($item['item_icon']['url'])) : ?>
                                                    <div class="tusp-item-icon-wrapper">
                                                        <img src="<?php echo esc_url($item['item_icon']['url']); ?>" 
                                                             alt="<?php echo esc_attr($item['item_title']); ?>" 
                                                             class="tusp-item-icon">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="tusp-item-content">
                                                    <h4 class="tusp-item-title"><?php echo esc_html($item['item_title']); ?></h4>
                                                    <?php if (!empty($item['item_description'])) : ?>
                                                        <p class="tusp-item-description"><?php echo esc_html($item['item_description']); ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['item_link_text'])) : ?>
                                                        <a href="<?php echo esc_url($item['item_link']['url']); ?>" class="tusp-item-link">
                                                            <span><?php echo esc_html($item['item_link_text']); ?></span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0.04 0 13.16 11.99">
                                                                <path d="M13.2 5.999a.986.986 0 00-.254-.619L7.937.28a1.112 1.112 0 00-1.37-.075.91.91 0 00.01 1.313L10.099 5.1H.939a.9.9 0 100 1.8h9.16l-3.522 3.582a.961.961 0 00-.01 1.313 1.1 1.1 0 001.37-.075l5.009-5.1a.847.847 0 00.254-.619z"/>
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    const widget = document.getElementById('<?php echo esc_js($widget_id); ?>');
                    if (!widget) return;
                    
                    const tabButtons = widget.querySelectorAll('.tusp-tab-button');
                    const contentPanels = widget.querySelectorAll('.tusp-content-panel');

                    tabButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const tabIndex = this.getAttribute('data-tab');

                            tabButtons.forEach(btn => btn.classList.remove('active'));
                            contentPanels.forEach(panel => panel.classList.remove('active'));

                            this.classList.add('active');
                            widget.querySelector(`[data-panel="${tabIndex}"]`).classList.add('active');
                        });
                    });
                })();
            </script>
        </div>
        <?php
    }
}