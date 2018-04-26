<?php
/* Exit if accessed directly */
defined('ABSPATH') or die('-1');

/**
 * Module Name: Typewriter
 * Description: Display Typewriter content
 */
class TB_Typewriter extends Themify_Builder_Component_Module {

    public function __construct() {
        parent::__construct(
                array(
                    'name' => __('Typewriter', 'builder-typewriter'),
                    'slug' => 'typewriter'
                )
        );
    }

    public function get_assets() {
        $instance = Builder_Typewriter::get_instance();
        return array(
            'selector' => '[data-typer-targets]',
            'css' => themify_enque($instance->url . 'assets/style.css'),
            'js' => themify_enque($instance->url . 'assets/frontend-scripts.js'),
            'ver' => $instance->version,
            'external' => Themify_Builder_Model::localize_js('tb_typewriter_vars', array(
                'url' => $instance->url
                    )
            )
        );
    }

    public function get_title($module) {
        return isset($module['mod_settings']['mod_title_typewriter']) ? wp_trim_words($module['mod_settings']['mod_title_typewriter'], 100) : '';
    }

    public function get_options() {
        return array(
            array(
                'id' => 'mod_title_typewriter',
                'type' => 'text',
                'label' => __('Module Title', 'builder-typewriter'),
                'class' => 'large',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            // Typewriter
            array(
                'id' => 'separator_typewriter',
                'type' => 'separator',
                'meta' => array('html' => '<hr /><h4>' . __('Typewriter', 'builder-typewriter') . '</h4>'),
            ),
            array(
                'id' => 'builder_typewriter_tag',
                'type' => 'select',
                'label' => __('Text Tag', 'builder-typewriter'),
                'options' => array(
                    'p' => __('p', 'builder-typewriter'),
                    'h1' => __('h1', 'builder-typewriter'),
                    'h2' => __('h2', 'builder-typewriter'),
                    'h3' => __('h3', 'builder-typewriter'),
                    'h4' => __('h4', 'builder-typewriter'),
                    'h5' => __('h5', 'builder-typewriter'),
                    'h6' => __('h6', 'builder-typewriter'),
                ),
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'builder_typewriter_text_before',
                'type' => 'text',
                'label' => __('Before Text', 'builder-typewriter'),
                'class' => 'fullwidth',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'builder_typewriter_text_after',
                'type' => 'text',
                'label' => __('After Text', 'builder-typewriter'),
                'class' => 'fullwidth',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'builder_typewriter_terms',
                'type' => 'builder',
                'options' => array(
                    array(
                        'id' => 'builder_typewriter_term',
                        'type' => 'text',
                        'label' => __('Text', 'builder-typewriter'),
                        'class' => 'large',
                        'render_callback' => array(
                            'binding' => 'live',
                            'repeater' => 'builder_typewriter_terms'
                        )
                    )
                ),
                'new_row_text' => __('Add New Text', 'builder-typewriter'),
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            // Effects
            array(
                'id' => 'separator_effect',
                'type' => 'separator',
                'meta' => array('html' => '<hr /><h4>' . __('Effects', 'builder-typewriter') . '</h4>'),
            ),
            array(
                'id' => 'builder_typewriter_highlight_speed',
                'type' => 'select',
                'label' => __('Highlight Speed', 'builder-typewriter'),
				'default' => 'Normal',
				'help' => __('Select "None" to disable highlight', 'builder-typewriter'),
                'options' => array(
                    '50' => __('Normal', 'builder-typewriter'),
                    '100' => __('Slow', 'builder-typewriter'),
                    '25' => __('Fast', 'builder-typewriter'),
                    '0' => __('None', 'builder-typewriter'),
                ),
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'builder_typewriter_type_speed',
                'type' => 'select',
                'label' => __('Type Speed', 'builder-typewriter'),
                'default' => 'Normal',
                'options' => array(
                    '60' => __('Normal', 'builder-typewriter'),
                    '120' => __('Slow', 'builder-typewriter'),
                    '35' => __('Fast', 'builder-typewriter'),
                ),
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'builder_typewriter_clear_delay',
                'type' => 'text',
                'label' => __('Clear Delay', 'builder-typewriter'),
                'class' => 'small',
                'after' => __('second(s)', 'builder-typewriter'),
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'builder_typewriter_type_delay',
                'type' => 'text',
                'label' => __('Type Delay', 'builder-typewriter'),
                'class' => 'small',
                'after' => __('second(s)', 'builder-typewriter'),
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'builder_typewriter_typer_interval',
                'type' => 'text',
                'label' => __('Highlight Delay', 'builder-typewriter'),
                'class' => 'small',
                'after' => __('second(s)', 'builder-typewriter'),
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
                'id' => 'add_css_text',
                'type' => 'text',
                'label' => __('Additional CSS Class', 'builder-typewriter'),
                'class' => 'large exclude-from-reset-field',
                'help' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-typewriter')),
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
        );
    }

    public function get_default_settings() {
        return array(
            'builder_typewriter_tag' => 'h3',
            'builder_typewriter_text_before' => esc_html__('This is', 'builder-typewriter'),
            'builder_typewriter_text_after' => esc_html__('addon', 'builder-typewriter'),
            'builder_typewriter_terms' => array(array(
                    'builder_typewriter_term' => esc_html__('Typewriter', 'builder-typewriter')
                )
            ),
            'span_background_color' => 'ffff00_1'
        );
    }

    public function get_styling() {
        $general = array(
            //bacground
            self::get_seperator('image_bacground', __('Background', 'themify'), false),
            self::get_image('.module-typewriter'),
            self::get_color('.module-typewriter', 'background_color', __('Background Color', 'themify'), 'background-color'),
            self::get_repeat('.module-typewriter'),
            // Font
            self::get_seperator('font', __('Font', 'themify')),
            self::get_font_family(array('.module-typewriter', '.module-typewriter p', '.module-typewriter h1', '.module-typewriter h2', '.module-typewriter h3:not(.module-title)', '.module-typewriter h4', '.module-typewriter h5', '.module-typewriter h6')),
            self::get_color(array('.module-typewriter', '.module-typewriter p', '.module-typewriter h1', '.module-typewriter h2', '.module-typewriter h3:not(.module-title)', '.module-typewriter h4', '.module-typewriter h5', '.module-typewriter h6'), 'font_color', __('Font Color', 'themify')),
            self::get_font_size(array('.module-typewriter', '.module-typewriter p', '.module-typewriter h1', '.module-typewriter h2', '.module-typewriter h3:not(.module-title)', '.module-typewriter h4', '.module-typewriter h5', '.module-typewriter h6')),
            self::get_line_height(array('.module-typewriter', '.module-typewriter p', '.module-typewriter h1', '.module-typewriter h2', '.module-typewriter h3:not(.module-title)', '.module-typewriter h4', '.module-typewriter h5', '.module-typewriter h6')),
            self::get_text_align('.module-typewriter'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-typewriter'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-typewriter'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-typewriter')
        );

        $typewriter = array(
            //bacground
            self::get_seperator('image_background_span', __('Background', 'themify'), false),
            self::get_color(' .typewriter-main-tag .typewriter-span span', 'span_background_color', __('Background Color(Highlighted)', 'themify'), 'background-color'),
            // Font
            self::get_seperator('font_span', __('Font', 'themify')),
            self::get_color(' .typewriter-main-tag .typewriter-span span', 'span_font_color', __('Font Color(Highlighted)', 'themify')),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding(' .typewriter-main-tag .typewriter-span span', 'span_padding'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border(' .typewriter-main-tag .typewriter-span span', 'span_border')
        );

        return array(
            array(
                'type' => 'tabs',
                'id' => 'module-styling',
                'tabs' => array(
                    'general' => array(
                        'label' => __('General', 'builder-typewriter'),
                        'fields' => $general
                    ),
                    'typewriter' => array(
                        'label' => __('Typewriter', 'builder-typewriter'),
                        'fields' => $typewriter
                    )
                )
            ),
        );
    }

    protected function _visual_template() {
        $module_args = self::get_module_args();
        ?>

        <#
        _.defaults( data, {
        builder_typewriter_highlight_speed: 50,
        builder_typewriter_type_speed: 60,
        builder_typewriter_clear_delay: 1.5,
        builder_typewriter_type_delay: 0.2,
        builder_typewriter_typer_interval: 1.5
        });

        var typewriterTerms = { targets: [] };
        if( data.builder_typewriter_terms ) {
        data.builder_typewriter_terms.forEach( function( el ) {
        typewriterTerms.targets.push( el.builder_typewriter_term );
        } );
        }

        typewriterTerms = JSON.stringify( typewriterTerms );
        #>

        <div class="module module-<?php echo $this->slug; ?> {{ data.add_css_text }}">
            <# if( data.mod_title_typewriter ) { #>
            <?php echo $module_args['before_title']; ?>
            {{{ data.mod_title_typewriter }}}
            <?php echo $module_args['after_title']; ?>
            <# } #>

            <?php do_action('themify_builder_before_template_content_render') ?>

            <{{{ data.builder_typewriter_tag }}} class="typewriter-main-tag">
            {{{ data.builder_typewriter_text_before }}} 
            <span class="typewriter-span"
                  data-typer-targets='{{ typewriterTerms }}'
                  data-typer-highlight-speed="{{ data.builder_typewriter_highlight_speed }}"
                  data-typer-type-speed="{{ data.builder_typewriter_type_speed }}"
                  data-typer-clear-delay="{{ data.builder_typewriter_clear_delay }}"
                  data-typer-type-delay="{{ data.builder_typewriter_type_delay }}"
                  data-typer-interval="{{ data.builder_typewriter_typer_interval }}"></span>
            {{{ data.builder_typewriter_text_after }}}
            </{{{ data.builder_typewriter_tag }}}>

            <?php do_action('themify_builder_after_template_content_render') ?>
        </div>
        <?php
    }

}

Themify_Builder_Model::register_module('TB_Typewriter');
