<?php
/**
 * Card Slider Widget for Elementor
 * Display cards in a responsive horizontal scrollable format with navigation
 * 
 * Features:
 * - Fully responsive card grid
 * - Smooth horizontal scrolling
 * - Navigation buttons
 * - Progress bar
 * - Customizable cards with badge support
 * - Elementor controls for all settings
 */

namespace ElementorTOCCCardSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) exit;

class Card_Slider_Widget extends Widget_Base {

    public function get_name() {
        return 'tocc_card_slider';
    }

    public function get_title() {
        return 'Card Slider';
    }

    public function get_icon() {
        return 'eicon-carousel';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['card', 'slider', 'carousel', 'scrollable', 'horizontal', 'benefits', 'features'];
    }

    protected function register_controls() {
        // Section: Content
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => 'Section Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'Membership Benefits at a Glance',
                'placeholder' => 'Enter section title',
            ]
        );

        // Repeater for cards
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'card_title',
            [
                'label' => 'Card Title',
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Enter card title',
                'default' => 'Card Title',
            ]
        );

        $repeater->add_control(
            'card_description',
            [
                'label' => 'Card Description',
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => 'Enter card description',
                'default' => 'Enter your card description here',
            ]
        );

        $repeater->add_control(
            'card_badge',
            [
                'label' => 'Badge Text',
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'e.g., Membership',
                'default' => 'Membership',
            ]
        );

        $this->add_control(
            'cards',
            [
                'label' => 'Cards',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'card_title' => 'Business Advice',
                        'card_description' => 'We offer free, practical advice to support businesses across a range of areas.',
                        'card_badge' => 'Membership',
                    ],
                    [
                        'card_title' => 'Exclusive Member Offers',
                        'card_description' => 'Enjoy access to a number of exclusive offers and discounts on business support services, leisure and marketing benefits.',
                        'card_badge' => 'Membership',
                    ],
                    [
                        'card_title' => 'Procurement Hub',
                        'card_description' => 'Navigate the procurement process, search for new opportunities and get practical advice to help your business create winning submissions.',
                        'card_badge' => 'Membership',
                    ],
                    [
                        'card_title' => 'AA',
                        'card_description' => 'Save 67% on your business breakdown and fleet costs with LCCI membership. Access exclusive rates on roadside assistance, fuel assist and car rental with the AA.',
                        'card_badge' => 'Membership',
                    ],
                    [
                        'card_title' => 'LCCI Logo',
                        'card_description' => 'Feature the \'proud to be a LCCI member\' logo on your website and other marketing materials.',
                        'card_badge' => 'Membership',
                    ],
                    [
                        'card_title' => 'International Trade Training Courses',
                        'card_description' => 'The latest tutorials in doing business abroad through our regular webinars and member events.',
                        'card_badge' => 'Membership',
                    ],
                ],
                'title_field' => '{{{ card_title }}}',
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
                    '{{WRAPPER}} .card-slider-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_size',
            [
                'label' => 'Title Size',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .card-slider-title' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => 'Card Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .card-slider-card' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label' => 'Card Border Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a3a52',
                'selectors' => [
                    '{{WRAPPER}} .card-slider-card' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_title_color',
            [
                'label' => 'Card Title Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a3a52',
                'selectors' => [
                    '{{WRAPPER}} .card-slider-card h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => 'Badge Background Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#1a3a52',
                'selectors' => [
                    '{{WRAPPER}} .card-slider-badge' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => 'Badge Text Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .card-slider-badge' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_color',
            [
                'label' => 'Progress Bar Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b35',
                'selectors' => [
                    '{{WRAPPER}} .card-slider-progress-bar' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'card-slider-' . $this->get_id();
        ?>

        <div class="card-slider-widget" id="<?php echo esc_attr($widget_id); ?>">
            <h1 class="card-slider-title"><?php echo esc_html($settings['section_title']); ?></h1>

            <div class="card-slider-wrapper">
                <div class="card-slider-container" id="<?php echo esc_attr($widget_id); ?>-container">
                    <div class="card-slider-grid">
                        <?php foreach ($settings['cards'] as $card) : ?>
                            <div class="card-slider-card">
                                <div class="card-slider-badge"><?php echo esc_html($card['card_badge']); ?></div>
                                <h3><?php echo esc_html($card['card_title']); ?></h3>
                                <div class="card-slider-description"><?php echo wp_kses_post($card['card_description']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card-slider-controls">
                    <div class="card-slider-progress-container">
                        <div class="card-slider-progress-bar" id="<?php echo esc_attr($widget_id); ?>-progress"></div>
                    </div>
                    <div class="card-slider-nav-buttons">
                        <button class="card-slider-nav-btn" id="<?php echo esc_attr($widget_id); ?>-prev" aria-label="Previous">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button class="card-slider-nav-btn" id="<?php echo esc_attr($widget_id); ?>-next" aria-label="Next">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <style>
                #<?php echo esc_attr($widget_id); ?> {
                    padding: 60px 40px;
                }

                #<?php echo esc_attr($widget_id); ?> * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                .card-slider-title {
                    color: #1a3a52;
                    font-size: 2.5rem;
                    margin-bottom: 60px;
                    font-weight: 600;
                }

                .card-slider-wrapper {
                    position: relative;
                    padding-bottom: 80px;
                }

                .card-slider-container {
                    overflow-x: auto;
                    overflow-y: hidden;
                    scroll-behavior: smooth;
                    scrollbar-width: none;
                    -ms-overflow-style: none;
                }

                .card-slider-container::-webkit-scrollbar {
                    display: none;
                }

                .card-slider-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 320px);
                    grid-auto-flow: row;
                    grid-auto-rows: auto;
                    gap: 24px;
                    padding: 15px 0;
                    width: auto;
                    align-items: start;
                }

                .card-slider-card:nth-child(odd) {
                    grid-column: 1;
                }

                .card-slider-card:nth-child(even) {
                    grid-column: 2;
                    margin-top: 15px;
                }

                .card-slider-card {
                    background: #f8f9fa;
                    border: 2px solid #1a3a52;
                    border-radius: 8px;
                    padding: 0;
                    min-height: 240px;
                    width: 320px;
                    display: flex;
                    flex-direction: column;
                    position: relative;
                    height: auto;
                }

                .card-slider-badge {
                    background: #1a3a52;
                    color: white;
                    padding: 8px 16px;
                    border-radius: 0;
                    font-size: 0.8rem;
                    font-weight: 600;
                    width: fit-content;
                    position: absolute;
                    top: 0;
                    right: 0;
                    letter-spacing: 0.3px;
                }

                .card-slider-card h3 {
                    color: #1a3a52;
                    font-size: 1.35rem;
                    margin-bottom: 0;
                    margin-top: 0;
                    line-height: 1.3;
                    font-weight: 700;
                    padding: 32px 28px 0 28px;
                    padding-top: 50px;
                }

                .card-slider-description {
                    color: #344054;
                    line-height: 1.6;
                    font-size: 1rem;
                    flex-grow: 1;
                    padding: 14px 28px 32px 28px;
                }

                .card-slider-description p {
                    margin: 0;
                    color: #344054;
                    line-height: 1.6;
                    font-size: 1rem;
                }

                .card-slider-description p:not(:last-child) {
                    margin-bottom: 12px;
                }

                .card-slider-controls {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 30px;
                    margin-top: 50px;
                    padding: 0 20px;
                }

                .card-slider-progress-container {
                    flex: 1;
                    height: 6px;
                    background: #e5e7eb;
                    border-radius: 3px;
                    overflow: hidden;
                }

                .card-slider-progress-bar {
                    height: 100%;
                    background: #ff6b35;
                    width: 50%;
                    transition: width 0.2s ease;
                }

                .card-slider-nav-buttons {
                    display: flex;
                    gap: 15px;
                    flex-shrink: 0;
                }

                .card-slider-nav-btn {
                    width: 48px;
                    height: 48px;
                    border-radius: 50%;
                    border: 2px solid #d0d5dd;
                    background: white;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    padding: 0;
                    flex-shrink: 0;
                }

                .card-slider-nav-btn:hover {
                    background: #1a3a52;
                    border-color: #1a3a52;
                }

                .card-slider-nav-btn:hover svg {
                    stroke: white;
                }

                .card-slider-nav-btn:disabled {
                    opacity: 0.4;
                    cursor: not-allowed;
                }

                .card-slider-nav-btn:disabled:hover {
                    background: white;
                    border-color: #d0d5dd;
                }

                .card-slider-nav-btn:disabled:hover svg {
                    stroke: #1a3a52;
                }

                .card-slider-nav-btn svg {
                    width: 22px;
                    height: 22px;
                    stroke: #1a3a52;
                    transition: stroke 0.3s ease;
                }

                @media (max-width: 768px) {
                    #<?php echo esc_attr($widget_id); ?> {
                        padding: 40px 20px;
                    }

                    .card-slider-title {
                        font-size: 2rem;
                        margin-bottom: 40px;
                    }

                    .card-slider-grid {
                        grid-template-columns: repeat(2, 280px);
                        gap: 20px;
                    }

                    .card-slider-card {
                        width: 280px;
                        min-height: 220px;
                    }

                    .card-slider-card h3 {
                        font-size: 1.15rem;
                        padding: 28px 24px 0 24px;
                        padding-top: 45px;
                    }

                    .card-slider-description {
                        padding: 12px 24px 28px 24px;
                        font-size: 0.9rem;
                    }

                    .card-slider-controls {
                        gap: 20px;
                        margin-top: 40px;
                    }

                    .card-slider-nav-btn {
                        width: 44px;
                        height: 44px;
                    }

                    .card-slider-nav-btn svg {
                        width: 20px;
                        height: 20px;
                    }
                }
            </style>

            <script>
                (function() {
                    const containerId = '<?php echo esc_js($widget_id); ?>';
                    const container = document.getElementById(containerId + '-container');
                    const progressBar = document.getElementById(containerId + '-progress');
                    const prevBtn = document.getElementById(containerId + '-prev');
                    const nextBtn = document.getElementById(containerId + '-next');

                    if (!container || !progressBar || !prevBtn || !nextBtn) return;

                    function updateProgress() {
                        const scrollPercentage = (container.scrollLeft / (container.scrollWidth - container.clientWidth)) * 100;
                        progressBar.style.width = scrollPercentage + '%';
                        
                        prevBtn.disabled = container.scrollLeft === 0;
                        nextBtn.disabled = container.scrollLeft >= container.scrollWidth - container.clientWidth - 1;
                    }

                    function scrollCards(direction) {
                        const scrollAmount = 350;
                        container.scrollBy({
                            left: direction === 'next' ? scrollAmount : -scrollAmount,
                            behavior: 'smooth'
                        });
                    }

                    prevBtn.addEventListener('click', () => scrollCards('prev'));
                    nextBtn.addEventListener('click', () => scrollCards('next'));
                    container.addEventListener('scroll', updateProgress);

                    // Initial update
                    setTimeout(updateProgress, 100);
                })();
            </script>
        </div>
        <?php
    }
}

// Register the widget
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Card_Slider_Widget());
?>