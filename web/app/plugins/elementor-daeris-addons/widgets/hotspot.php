<?php

namespace ElementorDaerisAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Hotspot extends Widget_Base
{
    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'hotspot';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Hotspot', 'elementor-daeris-addons');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-posts-ticker';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['general'];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['jquery-ui-tooltip', 'elementor-daeris-addons'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementor-daeris-addons'),
            ]
        );

        $this->add_control(
            'image',
            [
                'label'   => __('Hotspot image', 'elementor-daeris-addons'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'pin_label',
            [
                'label'       => __('Label', 'elementor-daeris-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Label', 'plugin-domain'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'pin_text',
            [
                'label'       => __('Text', 'elementor-daeris-addons'),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 6,
                'default'     => __('Description', 'elementor-daeris-addons'),
                'placeholder' => __('Type your description here', 'elementor-daeris-addons'),
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label'   => __('Icon', 'elementor-daeris-addons'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                ],
            ]
        );

        $repeater->add_control(
            'pin_position',
            [
                'label'      => __('Position', 'elementor-daeris-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['%'],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'position:absolute; top: {{TOP}}%; right : {{RIGHT}}%; bottom : {{BOTTOM}}%; left: {{LEFT}}%;'
                ],
            ]
        );

        $this->add_control(
            'list',
            [
                'label'   => __('Pin List', 'elementor-daeris-addons'),
                'type'    => \Elementor\Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'default' => [
                    [
                        'pin_label' => __('Title #1', 'elementor-daeris-addons'),
                    ],
                ],
                'title_field' => '{{{ pin_label }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'elementor-daeris-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => __('Typography', 'elementor-daeris-addons'),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .team-item-text-title',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $image_url = wp_get_attachment_image_url($settings['image']['id'], 'large');

        echo '<div class="hotspot-wrapper">';
        echo '<div class="hotspot-container">';
        echo '<div class="hotspot-image-wrapper">';
        if ($settings['list']) {
            foreach ($settings['list'] as $item) {
                $style_position = '';

                echo '<div class="elementor-repeater-item-' . $item['_id'] . ' hotspot-pin hotspot-pin-' . $item['_id'] . '" data-toggle="popover" data-content=" ' . $item['pin_text'] . ' ">';
                echo '<div class="icon-wrapper">';
                Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']) ;
                echo '</div>';

                echo '<div class="text-wrapper">';
                echo $item['pin_text'] ;
                echo '</div>';

                echo '</div>';
            }
        }
        echo '<img src="' . $image_url . '" alt="" />';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _content_template()
    {
        ?>
<div class="hotspot-wrapper">
    <div class="hotspot-container">
        <div class="hotspot-image-wrapper">
            <# if ( settings.list.length ) { #>
                <# _.each( settings.list, function( item ) { var iconHTML=elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden' : true }, 'i' , 'object' ); #>
                    <div class="elementor-repeater-item-{{ item._id }} hotspot-pin  hotspot-pin-{{ item._id }}" data-toggle="popover" data-content="{{ item.pin_text }}">
                        <div class="icon-wrapper">
                            {{{ iconHTML.value }}}
                        </div>
                        <div class="text-wrapper">
                            {{{ item.pin_text }}}
                        </div>
                    </div>
                    <# }); #>
                        <# } #>
                            <img src="{{ settings.image.url }}" alt="" />
        </div>
    </div>
</div>
<?php
    }
}
