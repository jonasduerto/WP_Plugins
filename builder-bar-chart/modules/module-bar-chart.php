<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Module Name: Bar Chart
 * Description: Display Bar Chart content
 */

class TB_Bar_Chart_Module extends Themify_Builder_Component_Module {

    function __construct() {
        parent::__construct(array(
            'name' => __('Bar Chart', 'builder-bar-chart'),
            'slug' => 'bar-chart'
        ));
    }

    function get_assets() {
        $instance = Builder_Bar_Chart::get_instance();
        return array(
            'selector' => '.module-bar-chart',
            'css' => themify_enque($instance->url . 'assets/style.css'),
            'js' => themify_enque($instance->url . 'assets/script.js'),
            'ver' => $instance->version
        );
    }

    public function get_options() {
        $options = array(
            array(
                'id' => 'mod_title_bar_chart',
                'type' => 'text',
                'label' => __('Module Title', 'builder-bar-chart'),
                'class' => 'large',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'mod_height_bar_chart',
                'type' => 'text',
                'label' => __('Chart Height', 'builder-bar-chart'),
                'class' => 'xsmall',
                'unit' => array(
                    'id' => 'unit_w',
                    'selected' => '%',
                    'options' => array(
                        array('id' => 'pixel_unit_w', 'value' => 'px'),
                    )
                ),
                'value' => 300,
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'content_bar_chart',
                'type' => 'builder',
                'options' => array(
                    array(
                        'id' => 'bar_chart_label',
                        'type' => 'text',
                        'label' => __('Bar Label', 'builder-bar-chart'),
                        'class' => 'large',
                        'render_callback' => array(
                            'repeater' => 'content_bar_chart',
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'bar_chart_percentage',
                        'type' => 'text',
                        'label' => __('Bar Height (%)', 'builder-bar-chart'),
                        'class' => 'large',
                        'render_callback' => array(
                            'repeater' => 'content_bar_chart',
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'bar_chart_number',
                        'type' => 'text',
                        'label' => __('Bar Number/Text', 'builder-bar-chart'),
                        'class' => 'large',
                        'render_callback' => array(
                            'repeater' => 'content_bar_chart',
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'bar_chart_color',
                        'type' => 'text',
                        'colorpicker' => true,
                        'label' => __('Bar color', 'builder-bar-chart'),
                        'class' => 'small',
                        'render_callback' => array(
                            'repeater' => 'content_bar_chart',
                            'binding' => 'live'
                        )
                    )
                ),
                'new_row_text' => __('Add New Bar', 'builder-bar-chart'),
                'render_callback' => array(
                    'binding' => 'live',
                    'control_type' => 'repeater'
                )
            ),
            array(
                'id' => 'label_direction_chart',
                'type' => 'select',
                'label' => __('Label Direction', 'builder-bar-chart'),
                'options' => array(
                    'horizontal' => __('Horizontal', 'builder-bar-chart'),
                    'vertical' => __('Vertical', 'builder-bar-chart'),
                ),
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
            ,
            // Additional CSS
            array(
                'type' => 'separator',
                'meta' => array('html' => '<hr/>')
            ),
            array(
                'id' => 'css_bar_chart',
                'type' => 'text',
                'label' => __('Additional CSS Class', 'builder-bar-chart'),
                'class' => 'large exclude-from-reset-field',
                'help' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-bar-chart')),
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
        );

        return $options;
    }

    public function get_default_settings() {
        return array(
            'mod_height_bar_chart' => 300,
            'content_bar_chart' => array(array(
                    'bar_chart_label' => esc_html__('Label', 'builder-bar-chart'),
                    'bar_chart_percentage' => 80,
                    'bar_chart_number' => '80%',
                    'bar_chart_color' => '#5433db'
                ))
        );
    }

    public function get_styling() {
        return array(
            //bacground
            self::get_seperator('image_bacground', __('Background', 'themify'), false),
            self::get_color('.module-bar-chart', 'background_color', __('Background Color', 'themify'), 'background-color'),
            // Font
            self::get_seperator('font', __('Font', 'themify')),
            self::get_font_family('.module-bar-chart'),
            self::get_color(array('.module-bar-chart', '.module-bar-chart h1', '.module-bar-chart h2', '.module-bar-chart h3', '.module-bar-chart h4', '.module-bar-chart h5', '.module-bar-chart h6'), 'font_color', __('Font Color', 'themify')),
            self::get_font_size('.module-bar-chart'),
            self::get_line_height('.module-bar-chart'),
            self::get_text_align('.module-bar-chart'),
            self::get_text_decoration('.module-bar-chart a'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-bar-chart'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-bar-chart'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-bar-chart')
        );
    }

    protected function _visual_template() {
        $module_args = self::get_module_args();
        ?>
        <div class="module module-<?php echo $this->slug; ?> {{ data.css_bar_chart }} label-direction-{{ data.label_direction_chart }}">

            <# if( data.mod_title_bar_chart ) { #>
            <?php echo $module_args['before_title']; ?>
            {{{ data.mod_title_bar_chart }}}
            <?php echo $module_args['after_title']; ?>
            <# }
            if( data.content_bar_chart ) { #>
            <?php do_action('themify_builder_before_template_content_render'); ?>

            <div class="module-<?php echo $this->slug; ?>">
                <ul class="bar-chart-content bc-chart" <# data.mod_height_bar_chart && print( 'style="height:' + data.mod_height_bar_chart + 'px"' ) #>>
                    <# _.each( data.content_bar_chart, function( item ) { #>
                    <li>
                        <span class="bc-bar" data-height="{{ item.bar_chart_percentage }}" <# item.bar_chart_color && print( 'style="background-color:' + themifybuilderapp.Utils.toRGBA( item.bar_chart_color ) + '"' ) #> title="{{ item.bar_chart_label }}">
                              <span class="bc-value">{{{ item.bar_chart_number }}}</span>
                        </span>
                    </li>
                    <# } ); #>
                </ul>  
            </div>
            <?php do_action('themify_builder_after_template_content_render'); ?>
            <# } #>
        </div>
        <?php
    }

}

///////////////////////////////////////
// Module Options
///////////////////////////////////////
Themify_Builder_Model::register_module('TB_Bar_Chart_Module');
