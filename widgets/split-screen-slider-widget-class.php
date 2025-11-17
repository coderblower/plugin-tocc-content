<?php
/**
 * Split-Screen Slider Widget Class
 * Save as: /widgets/split-screen-slider-widget-class.php
 */

namespace ElementorSplitScreenSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) exit;

class Split_Screen_Slider_Widget extends Widget_Base {

    public function get_name() {
        return 'split_screen_slider';
    }

    public function get_title() {
        return 'Split Screen Slider';
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['tabbed-widgets'];
    }

    public function get_keywords() {
        return ['slider', 'split', 'diagonal', 'carousel', 'slideshow'];
    }

    protected function register_controls() {
        
        // Slides Section
        $this->start_controls_section(
            'slides_section',
            [
                'label' => 'Slides',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slide_label_line1',
            [
                'label' => 'Label Line 1',
                'type' => Controls_Manager::TEXT,
                'default' => 'ECONOMIC SURVEY',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'slide_label_line2',
            [
                'label' => 'Label Line 2',
                'type' => Controls_Manager::TEXT,
                'default' => 'July – September 2025',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'slide_content',
            [
                'label' => 'Content',
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<p>Your slide content goes here. This is where you can add your main message or description.</p>',
            ]
        );

        $repeater->add_control(
            'cta_text',
            [
                'label' => 'Button Text',
                'type' => Controls_Manager::TEXT,
                'default' => 'Read Full Report',
            ]
        );

        $repeater->add_control(
            'cta_link',
            [
                'label' => 'Button Link',
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $repeater->add_control(
            'slide_image',
            [
                'label' => 'Image',
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=800&fit=crop',
                ],
            ]
        );

        $repeater->add_control(
            'content_bg_color',
            [
                'label' => 'Content Background',
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'description' => 'Leave empty to use global gradient',
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => 'Slides',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'slide_label_line1' => 'ECONOMIC SURVEY',
                        'slide_label_line2' => 'July – September 2025',
                        'slide_content' => '<p>Cautious optimism remains among London businesses, though confidence has softened compared to previous quarters.</p>',
                        'cta_text' => 'Read Full Report',
                        'slide_image' => ['url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=800&fit=crop'],
                    ],
                    [
                        'slide_label_line1' => 'ATA CARNETS',
                        'slide_label_line2' => 'Training Course',
                        'slide_content' => '<p>This course is ideal for companies and sole traders who are looking to ease their temporary export/import procedures.</p>',
                        'cta_text' => 'Book Now',
                        'slide_image' => ['url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1200&h=800&fit=crop'],
                    ],
                ],
                'title_field' => '{{{ slide_label_line1 }}}',
            ]
        );

        $this->end_controls_section();

        // Layout Settings
        $this->start_controls_section(
            'layout_section',
            [
                'label' => 'Layout Settings',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'diagonal_angle',
            [
                'label' => 'Diagonal Separator Angle',
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'deg' => [
                        'min' => -15,
                        'max' => 15,
                        'step' => 0.5,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => -5,
                ],
                'description' => 'Adjust the skew angle of the diagonal separator',
            ]
        );

        $this->add_control(
            'separator_width',
            [
                'label' => 'Separator Width',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->add_control(
            'show_separator',
            [
                'label' => 'Show Diagonal Separator',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'content_clip_offset',
            [
                'label' => 'Content Clip Offset',
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
                'description' => 'Diagonal cut offset on content area',
            ]
        );

        $this->add_control(
            'image_clip_offset',
            [
                'label' => 'Image Clip Offset',
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
                'description' => 'Diagonal cut offset on image area',
            ]
        );

        $this->add_responsive_control(
            'slider_height',
            [
                'label' => 'Slider Height',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['vh', 'px'],
                'range' => [
                    'vh' => [
                        'min' => 30,
                        'max' => 100,
                        'step' => 5,
                    ],
                    'px' => [
                        'min' => 300,
                        'max' => 1200,
                        'step' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 100,
                ],
            ]
        );

        $this->end_controls_section();

        // Autoplay Settings
        $this->start_controls_section(
            'autoplay_section',
            [
                'label' => 'Autoplay & Animation',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => 'Autoplay',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Yes',
                'label_off' => 'No',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'slide_duration',
            [
                'label' => 'Slide Duration (ms)',
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'min' => 1000,
                'max' => 20000,
                'step' => 500,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => 'Transition Speed (ms)',
                'type' => Controls_Manager::NUMBER,
                'default' => 800,
                'min' => 200,
                'max' => 2000,
                'step' => 100,
            ]
        );

        $this->end_controls_section();

        // Controls Settings
        $this->start_controls_section(
            'controls_section',
            [
                'label' => 'Controls',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_play_pause',
            [
                'label' => 'Show Play/Pause Button',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_progress_bar',
            [
                'label' => 'Show Progress Bar',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_indicators',
            [
                'label' => 'Show Slide Indicators',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_nav_arrows',
            [
                'label' => 'Show Navigation Arrows',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Show',
                'label_off' => 'Hide',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'prev_icon',
            [
                'label' => 'Previous Icon',
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_nav_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'next_icon',
            [
                'label' => 'Next Icon',
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_nav_arrows' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Colors
        $this->start_controls_section(
            'colors_section',
            [
                'label' => 'Colors',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_bg_gradient_start',
            [
                'label' => 'Content Background Start',
                'type' => Controls_Manager::COLOR,
                'default' => '#1e3c72',
            ]
        );

        $this->add_control(
            'content_bg_gradient_end',
            [
                'label' => 'Content Background End',
                'type' => Controls_Manager::COLOR,
                'default' => '#2a5298',
            ]
        );

        $this->add_control(
            'content_text_color',
            [
                'label' => 'Content Text Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'separator_color',
            [
                'label' => 'Separator Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => 'Button Background',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b35',
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => 'Button Hover Background',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff8555',
            ]
        );

        $this->add_control(
            'label_bg_color',
            [
                'label' => 'Label Background',
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(139, 69, 139, 0.9)',
            ]
        );

        $this->add_control(
            'controls_color',
            [
                'label' => 'Controls Color',
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b35',
            ]
        );

        $this->end_controls_section();

        // Style - Typography
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
                'name' => 'content_typography',
                'label' => 'Content Typography',
                'selector' => '{{WRAPPER}} .sss-slide-text p',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => 'Button Typography',
                'selector' => '{{WRAPPER}} .sss-cta-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => 'Label Typography',
                'selector' => '{{WRAPPER}} .sss-slide-label',
            ]
        );

        $this->end_controls_section();

        // Style - Controls Position
        $this->start_controls_section(
            'controls_position_section',
            [
                'label' => 'Controls Position',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'controls_bottom',
            [
                'label' => 'Bottom Position',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
            ]
        );

        $this->add_responsive_control(
            'controls_left',
            [
                'label' => 'Left Position',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
            ]
        );

        $this->add_responsive_control(
            'label_top',
            [
                'label' => 'Label Top Position',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
            ]
        );

        $this->add_responsive_control(
            'label_right',
            [
                'label' => 'Label Right Position',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'sss-' . $this->get_id();
        
        $slides = isset($settings['slides']) && is_array($settings['slides']) ? $settings['slides'] : [];
        
        if (empty($slides)) {
            echo '<div style="text-align: center; padding: 60px 20px;">No slides added yet.</div>';
            return;
        }

        // Get all settings with defaults
        $diagonal_angle = $settings['diagonal_angle']['size'] ?? -5;
        $separator_width = $settings['separator_width']['size'] ?? 10;
        $show_separator = $settings['show_separator'] === 'yes';
        $content_clip = $settings['content_clip_offset']['size'] ?? 50;
        $image_clip = $settings['image_clip_offset']['size'] ?? 50;
        $slider_height = $settings['slider_height']['size'] . $settings['slider_height']['unit'];
        
        $autoplay = $settings['autoplay'] === 'yes';
        $slide_duration = $settings['slide_duration'] ?? 5000;
        $transition_speed = $settings['transition_speed'] ?? 800;
        
        $show_play_pause = $settings['show_play_pause'] === 'yes';
        $show_progress = $settings['show_progress_bar'] === 'yes';
        $show_indicators = $settings['show_indicators'] === 'yes';
        $show_arrows = $settings['show_nav_arrows'] === 'yes';
        
        ?>
        <div class="split-screen-slider" id="<?php echo esc_attr($widget_id); ?>">
            <style>
                #<?php echo esc_attr($widget_id); ?> {
                    position: relative;
                    width: 100%;
                    height: <?php echo esc_attr($slider_height); ?>;
                    overflow: hidden;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    display: flex;
                    flex-direction: row;
                    opacity: 0;
                    transition: opacity <?php echo esc_attr($transition_speed); ?>ms ease-in-out;
                    pointer-events: none;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide.active {
                    opacity: 1;
                    pointer-events: auto;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide-content {
                    flex: 1;
                    display: flex;
                    align-items: center;
                    padding: 80px;
                    background: linear-gradient(135deg, <?php echo esc_attr($settings['content_bg_gradient_start']); ?> 0%, <?php echo esc_attr($settings['content_bg_gradient_end']); ?> 100%);
                    color: <?php echo esc_attr($settings['content_text_color']); ?>;
                    position: relative;
                    z-index: 2;
                    clip-path: polygon(0 0, 100% 0, calc(100% - <?php echo esc_attr($content_clip); ?>px) 100%, 0 100%);
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide-content.custom-bg {
                    background: var(--custom-bg) !important;
                }
                
                <?php if ($show_separator) : ?>
                #<?php echo esc_attr($widget_id); ?> .sss-diagonal-separator {
                    width: <?php echo esc_attr($separator_width); ?>px;
                    background: <?php echo esc_attr($settings['separator_color']); ?>;
                    position: absolute;
                    height: 100%;
                    z-index: 3;
                    left: 50%;
                    transform: translateX(-<?php echo esc_attr($separator_width / 2); ?>px) skewX(<?php echo esc_attr($diagonal_angle); ?>deg);
                    flex-shrink: 0;
                }
                <?php endif; ?>
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide-text {
                    max-width: 700px;
                    animation: slideInLeft <?php echo esc_attr($transition_speed); ?>ms ease-out;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide.active .sss-slide-text {
                    animation: slideInLeft <?php echo esc_attr($transition_speed); ?>ms ease-out;
                }
                
                @keyframes slideInLeft {
                    from {
                        opacity: 0;
                        transform: translateX(-50px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide-text p {
                    font-size: 18px;
                    line-height: 1.8;
                    margin-bottom: 35px;
                    color: rgba(255, 255, 255, 0.95);
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-cta-button {
                    background: <?php echo esc_attr($settings['button_bg_color']); ?>;
                    color: white;
                    border: none;
                    padding: 16px 40px;
                    font-size: 16px;
                    font-weight: 600;
                    border-radius: 50px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    text-decoration: none;
                    display: inline-block;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-cta-button:hover {
                    background: <?php echo esc_attr($settings['button_hover_color']); ?>;
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide-image {
                    flex: 1;
                    position: relative;
                    overflow: hidden;
                    clip-path: polygon(<?php echo esc_attr($image_clip); ?>px 0, 100% 0, 100% 100%, 0 100%);
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    animation: zoomIn <?php echo esc_attr($transition_speed); ?>ms ease-out;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide.active .sss-slide-image img {
                    animation: zoomIn <?php echo esc_attr($transition_speed); ?>ms ease-out;
                }
                
                @keyframes zoomIn {
                    from {
                        opacity: 0;
                        transform: scale(1.1);
                    }
                    to {
                        opacity: 1;
                        transform: scale(1);
                    }
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide-label {
                    position: absolute;
                    top: <?php echo esc_attr($settings['label_top']['size'] . $settings['label_top']['unit']); ?>;
                    right: <?php echo esc_attr($settings['label_right']['size'] . $settings['label_right']['unit']); ?>;
                    background: <?php echo esc_attr($settings['label_bg_color']); ?>;
                    color: white;
                    padding: 15px 30px;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 14px;
                    letter-spacing: 1px;
                    z-index: 10;
                    line-height: 1.6;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-controls {
                    position: absolute;
                    bottom: <?php echo esc_attr($settings['controls_bottom']['size'] . $settings['controls_bottom']['unit']); ?>;
                    left: <?php echo esc_attr($settings['controls_left']['size'] . $settings['controls_left']['unit']); ?>;
                    display: flex;
                    align-items: center;
                    gap: 25px;
                    z-index: 100;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-play-pause-btn {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    background: <?php echo esc_attr($settings['controls_color']); ?>;
                    border: none;
                    color: white;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    font-size: 18px;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-play-pause-btn:hover {
                    transform: scale(1.1);
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-progress-bar-container {
                    width: 280px;
                    height: 6px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 3px;
                    overflow: hidden;
                    position: relative;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-progress-bar {
                    height: 100%;
                    background: white;
                    border-radius: 3px;
                    width: 0%;
                    transition: width 0.1s linear;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-slide-indicators {
                    display: flex;
                    gap: 12px;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-indicator {
                    width: 35px;
                    height: 6px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 3px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-indicator.active {
                    background: white;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-indicator:hover {
                    background: rgba(255, 255, 255, 0.6);
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-nav-buttons {
                    display: flex;
                    gap: 15px;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-nav-btn {
                    width: 45px;
                    height: 45px;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.2);
                    border: 2px solid white;
                    color: white;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    font-size: 20px;
                }
                
                #<?php echo esc_attr($widget_id); ?> .sss-nav-btn:hover {
                    background: white;
                    color: #1e3c72;
                    transform: scale(1.1);
                }
                
                @media (max-width: 1024px) {
                    #<?php echo esc_attr($widget_id); ?> .sss-slide {
                        flex-direction: column;
                    }
                    
                    #<?php echo esc_attr($widget_id); ?> .sss-slide-content {
                        padding: 40px;
                        clip-path: polygon(0 0, 100% 0, 100% calc(100% - 30px), 0 100%);
                    }
                    
                    <?php if ($show_separator) : ?>
                    #<?php echo esc_attr($widget_id); ?> .sss-diagonal-separator {
                        width: 100%;
                        height: <?php echo esc_attr($separator_width); ?>px;
                        left: 0;
                        top: 50%;
                        transform: translateY(-<?php echo esc_attr($separator_width / 2); ?>px) skewY(<?php echo esc_attr($diagonal_angle / 2); ?>deg);
                    }
                    <?php endif; ?>
                    
                    #<?php echo esc_attr($widget_id); ?> .sss-slide-image {
                        clip-path: polygon(0 30px, 100% 0, 100% 100%, 0 100%);
                    }
                    
                    #<?php echo esc_attr($widget_id); ?> .sss-slide-text {
                        max-width: 100%;
                    }
                    
                    #<?php echo esc_attr($widget_id); ?> .sss-controls {
                        bottom: 40px;
                        left: 40px;
                    }
                    
                    #<?php echo esc_attr($widget_id); ?> .sss-slide-label {
                        top: 50px;
                        right: 40px;
                    }
                }
                
                @media (max-width: 768px) {
                    #<?php echo esc_attr($widget_id); ?> .sss-slide-content {
                        padding: 30px 20px;
                    }
                    
                    #<?php echo esc_attr($widget_id); ?> .sss-controls {
                        bottom: 20px;
                        left: 20px;
                        flex-wrap: wrap;
                        gap: 15px;
                    }
                    
                    #<?php echo esc_attr($widget_id); ?> .sss-progress-bar-container {
                        width: 100%;
                        order: 10;
                        flex-basis: 100%;
                    }
                    
                    #<?php echo esc_attr($widget_id); ?> .sss-slide-label {
                        top: 20px;
                        right: 20px;
                        font-size: 12px;
                        padding: 10px 20px;
                    }
                }
            </style>

            <div class="sss-slider-container">
                <?php foreach ($slides as $index => $slide) : 
                    $custom_bg = !empty($slide['content_bg_color']) ? $slide['content_bg_color'] : '';
                ?>
                    <div class="sss-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo esc_attr($index); ?>">
                        <div class="sss-slide-content <?php echo !empty($custom_bg) ? 'custom-bg' : ''; ?>" 
                             <?php if (!empty($custom_bg)) : ?>style="--custom-bg: <?php echo esc_attr($custom_bg); ?>"<?php endif; ?>>
                            <div class="sss-slide-text">
                                <?php echo wp_kses_post($slide['slide_content']); ?>
                                <?php if (!empty($slide['cta_text'])) : ?>
                                    <a href="<?php echo esc_url($slide['cta_link']['url']); ?>" 
                                       class="sss-cta-button"
                                       <?php if (!empty($slide['cta_link']['is_external'])) echo 'target="_blank"'; ?>
                                       <?php if (!empty($slide['cta_link']['nofollow'])) echo 'rel="nofollow"'; ?>>
                                        <?php echo esc_html($slide['cta_text']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($show_separator) : ?>
                            <div class="sss-diagonal-separator"></div>
                        <?php endif; ?>
                        
                        <div class="sss-slide-image">
                            <?php if (!empty($slide['slide_label_line1']) || !empty($slide['slide_label_line2'])) : ?>
                                <div class="sss-slide-label">
                                    <?php if (!empty($slide['slide_label_line1'])) : ?>
                                        <?php echo esc_html($slide['slide_label_line1']); ?><br>
                                    <?php endif; ?>
                                    <?php if (!empty($slide['slide_label_line2'])) : ?>
                                        <?php echo esc_html($slide['slide_label_line2']); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <img src="<?php echo esc_url($slide['slide_image']['url']); ?>" 
                                 alt="<?php echo esc_attr($slide['slide_label_line1']); ?>">
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="sss-controls">
                    <?php if ($show_play_pause) : ?>
                        <button class="sss-play-pause-btn" id="<?php echo esc_attr($widget_id); ?>-play-pause">
                            <?php echo $autoplay ? '❚❚' : '▶'; ?>
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($show_progress) : ?>
                        <div class="sss-progress-bar-container">
                            <div class="sss-progress-bar" id="<?php echo esc_attr($widget_id); ?>-progress"></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($show_indicators) : ?>
                        <div class="sss-slide-indicators" id="<?php echo esc_attr($widget_id); ?>-indicators">
                            <?php foreach ($slides as $index => $slide) : ?>
                                <div class="sss-indicator <?php echo $index === 0 ? 'active' : ''; ?>" 
                                     data-indicator-index="<?php echo esc_attr($index); ?>"></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($show_arrows) : ?>
                        <div class="sss-nav-buttons">
                            <button class="sss-nav-btn" id="<?php echo esc_attr($widget_id); ?>-prev">
                                <?php \Elementor\Icons_Manager::render_icon($settings['prev_icon'], ['aria-hidden' => 'true']); ?>
                            </button>
                            <button class="sss-nav-btn" id="<?php echo esc_attr($widget_id); ?>-next">
                                <?php \Elementor\Icons_Manager::render_icon($settings['next_icon'], ['aria-hidden' => 'true']); ?>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <script>
                (function() {
                    const widget = document.getElementById('<?php echo esc_js($widget_id); ?>');
                    if (!widget) return;
                    
                    class SplitScreenSlider {
                        constructor(container) {
                            this.container = container;
                            this.slides = container.querySelectorAll('.sss-slide');
                            this.indicators = container.querySelectorAll('.sss-indicator');
                            this.progressBar = container.querySelector('.sss-progress-bar');
                            this.playPauseBtn = container.querySelector('.sss-play-pause-btn');
                            this.prevBtn = container.querySelector('#<?php echo esc_js($widget_id); ?>-prev');
                            this.nextBtn = container.querySelector('#<?php echo esc_js($widget_id); ?>-next');
                            
                            this.currentSlide = 0;
                            this.isPlaying = <?php echo $autoplay ? 'true' : 'false'; ?>;
                            this.slideDuration = <?php echo esc_js($slide_duration); ?>;
                            this.progressInterval = null;
                            this.slideTimeout = null;
                            this.progress = 0;

                            this.init();
                        }

                        init() {
                            this.attachEventListeners();
                            if (this.isPlaying) {
                                this.startAutoPlay();
                            }
                        }

                        attachEventListeners() {
                            if (this.playPauseBtn) {
                                this.playPauseBtn.addEventListener('click', () => this.togglePlayPause());
                            }
                            
                            if (this.prevBtn) {
                                this.prevBtn.addEventListener('click', () => this.previousSlide());
                            }
                            
                            if (this.nextBtn) {
                                this.nextBtn.addEventListener('click', () => this.nextSlide());
                            }
                            
                            this.indicators.forEach((indicator, index) => {
                                indicator.addEventListener('click', () => this.goToSlide(index));
                            });
                        }

                        goToSlide(index) {
                            this.slides[this.currentSlide].classList.remove('active');
                            if (this.indicators.length > 0) {
                                this.indicators[this.currentSlide].classList.remove('active');
                            }

                            this.currentSlide = index;
                            
                            this.slides[this.currentSlide].classList.add('active');
                            if (this.indicators.length > 0) {
                                this.indicators[this.currentSlide].classList.add('active');
                            }

                            this.resetProgress();
                            if (this.isPlaying) {
                                this.startAutoPlay();
                            }
                        }

                        nextSlide() {
                            const next = (this.currentSlide + 1) % this.slides.length;
                            this.goToSlide(next);
                        }

                        previousSlide() {
                            const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
                            this.goToSlide(prev);
                        }

                        startAutoPlay() {
                            this.resetProgress();
                            this.updateProgress();
                            
                            this.slideTimeout = setTimeout(() => {
                                this.nextSlide();
                            }, this.slideDuration);
                        }

                        updateProgress() {
                            if (!this.progressBar) return;
                            
                            const step = 100 / (this.slideDuration / 50);
                            this.progress = 0;

                            this.progressInterval = setInterval(() => {
                                this.progress += step;
                                if (this.progress >= 100) {
                                    this.progress = 100;
                                    clearInterval(this.progressInterval);
                                }
                                this.progressBar.style.width = this.progress + '%';
                            }, 50);
                        }

                        resetProgress() {
                            clearInterval(this.progressInterval);
                            clearTimeout(this.slideTimeout);
                            this.progress = 0;
                            if (this.progressBar) {
                                this.progressBar.style.width = '0%';
                            }
                        }

                        togglePlayPause() {
                            this.isPlaying = !this.isPlaying;
                            
                            if (this.isPlaying) {
                                if (this.playPauseBtn) {
                                    this.playPauseBtn.innerHTML = '❚❚';
                                }
                                this.startAutoPlay();
                            } else {
                                if (this.playPauseBtn) {
                                    this.playPauseBtn.innerHTML = '▶';
                                }
                                this.resetProgress();
                            }
                        }
                    }

                    new SplitScreenSlider(widget);
                })();
            </script>
        </div>
        <?php
    }
}