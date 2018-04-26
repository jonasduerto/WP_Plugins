<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Module Name: Button Pro
 */
class TB_Button_Module extends Themify_Builder_Component_Module {
	public function __construct() {
		parent::__construct(array(
			'name' => __('Button Pro', 'builder-button'),
			'slug' => 'button'
		));
	}

	public function get_assets() {
		$instance = Builder_Button::get_instance();
		return array(
			'selector'=>'.module-button',
			'css'=>$instance->url.'assets/style.min.css',
			'js'=>themify_enque($instance->url.'assets/scripts.js'),
			'ver'=>$instance->version
		);
	}

	public function get_options() {
                $colors = Themify_Builder_Model::get_colors();
                $colors[] = array('img' => 'transparent', 'value' => 'transparent', 'label' => __('Transparent', 'themify'));
		return array(
			array(
				'id' => 'link_label',
				'type' => 'text',
				'label' => __('Button Text', 'builder-button'),
				'class' => 'fullwidth',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'type_button',
				'type' => 'radio',
				'label' => __('Type', 'builder-button'),
				'options' => array(
					'external' => __('External Link', 'builder-button'),
					'row_scroll' => __('Scroll to next row (it will scroll to the next Builder row)', 'builder-button'),
					'modules_reveal' => __('Show more/less (it will hide/show all modules after)', 'builder-button'),
					'modal' => __('Text modal (it will open text content in a lightbox)', 'builder-button'),
				),
				'default' => 'external',
				'option_js' => true,
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'link_button',
				'type' => 'text',
				'label' => __('Link', 'builder-button'),
				'class' => 'fullwidth',
				'wrap_with_class' => 'tb-group-element tb-group-element-external',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'param_button',
				'type' => 'checkbox',
				'label' => false,
				'pushed' => 'pushed',
				'options' => array(
					array( 'name' => 'lightbox', 'value' => __('Open link in lightbox', 'builder-button') ),
					array( 'name' => 'newtab', 'value' => __('Open link in new tab', 'builder-button') )
				),
				'new_line' => false,
				'default' => 'regular',
				'option_js' => true,
				'wrap_with_class' => 'tb-group-element tb-group-element-external',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'lightbox_size',
				'type' => 'multi',
				'label' => __('Lightbox Dimension', 'builder-button'),
				'fields' => array(
					array(
						'id' => 'lightbox_width',
						'type' => 'text',
						'label' => __( 'Width', 'builder-button' ),
						'value' => '',
						'render_callback' => array(
							'binding' => 'live'
						)
					),
					array(
						'id' => 'lightbox_size_unit_width',
						'type' => 'select',
						'label' => __( 'Units', 'builder-button' ),
						'options' => array(
							'pixels' => __('px', 'builder-button'),
							'percents' => __('%', 'builder-button')
						),
						'default' => 'pixels',
						'render_callback' => array(
							'binding' => 'live'
						)
					),
					array(
						'id' => 'lightbox_height',
						'type' => 'text',
						'label' => __( 'Height', 'builder-button' ),
						'value' => '',
						'render_callback' => array(
							'binding' => 'live'
						)
					),
					array(
						'id' => 'lightbox_size_unit_height',
						'type' => 'select',
						'label' => __( 'Units', 'builder-button' ),
						'options' => array(
							'pixels' => __('px', 'builder-button'),
							'percents' => __('%', 'builder-button')
						),
						'default' => 'pixels',
						'render_callback' => array(
							'binding' => 'live'
						)
					)
				),
				'option_js' => false,
				'wrap_with_class' => 'tb-group-element tb-group-element-lightbox tb-group-element-modal'
			),
			array(
				'id' => 'content_modal_button',
				'type' => 'wp_editor',
				'class' => 'fullwidth',
				'wrap_with_class' => 'tb-group-element tb-group-element-modal',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' 		=> 'modules_reveal_behavior_button',
				'label'		=> __('After click', 'builder-button'),
				'type' 		=> 'select',
				'default'	=> '',
				'options' => array(
					'toggle' => __('Toggle the less button', 'builder-button'),
					'hide' => __('Hide the button', 'builder-button'),
				),
				'binding' => array(
					'toggle' => array(
						'show' => array( 'show_less_label_button' )
					),
					'hide' => array(
						'hide' => array( 'show_less_label_button' )
					)
				),
				'wrap_with_class' => 'tb-group-element tb-group-element-modules_reveal',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'show_less_label_button',
				'type' => 'text',
				'label' => __('Less button text', 'builder-button'),
				'class' => 'fullwidth',
				'wrap_with_class' => 'tb-group-element tb-group-element-modules_reveal',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'icon_button',
				'type' => 'icon',
				'label' => __('Button Icon', 'builder-button'),
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'color_button',
				'type' => 'layout',
                                'mode'=>'sprite',
                                'class'=>'tb-colors',
				'label' => __('Button Color', 'builder-button'),
				'options' => $colors,
				'wrap_with_class' => 'fullwidth',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
                        array(
				'id' => 'color_button_hover',
				'type' => 'layout',
                                'mode'=>'sprite',
                                'class'=>'tb-colors',
				'label' => __('Button Hover Color', 'builder-button'),
				'options' => $colors,
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'appearance_button',
				'type' => 'checkbox',
				'label' => __('Appearance', 'builder-button'),
				'default' => array(
					'rounded', 
					'gradient'
				),
				'options' => Themify_Builder_Model::get_appearance(),
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_button',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-button'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-button') ),
				'render_callback' => array(
					'binding' => 'live'
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'link_label' => esc_html__( 'Button Text', 'builder-button' ),
			'link_button' => 'https://themify.me',
			'color_button' => 'blue',
			'color_button_hover' => 'blue'
		);
	}


	public function get_styling() {
		return array(
                        //bacground
                        self::get_seperator('image_bacground', __('Background', 'themify'), false),
                        self::get_color('.module-button a', 'background_color', __('Background Color', 'themify'), 'background-color'),
			// Font
                        self::get_seperator('font', __('Font', 'themify')),
                        self::get_font_family('.module-button a'),
                        self::get_color('.module-button a', 'font_color', __('Font Color', 'themify')),
                        self::get_font_size('.module-button a'),
                        self::get_line_height('.module-button a'),
                        self::get_text_align('.module-button a'),
                        self::get_text_decoration('.module-bar-chart a'),
			// Padding
                        self::get_seperator('padding', __('Padding', 'themify')),
                        self::get_padding('.module-button a'),
                        // Margin
                        self::get_seperator('margin', __('Margin', 'themify')),
                        self::get_margin('.module-button a'),
                        // Border
                        self::get_seperator('border', __('Border', 'themify')),
                        self::get_border('.module-button a')
		);
	}

	protected function _visual_template() {
        ?>
		<div class="module module-<?php echo $this->slug; ?> {{ data.add_css_button }}">
			<?php do_action( 'themify_builder_before_template_content_render' ); ?>
			
			<a class="ui builder_button {{ data.color_button }} <# data.appearance_button && print( data.appearance_button.replace( /\|/g, ' ' ) ) #>">
				<# if( data.icon_button ) { #>
					<i class="fa {{ data.icon_button }}"></i>
				<# } #>
				<span>{{{ data.link_label }}}</span>
			</a>

			<?php do_action( 'themify_builder_after_template_content_render' ); ?>
		</div>
	<?php
	}
}

Themify_Builder_Model::register_module( 'TB_Button_Module' );
