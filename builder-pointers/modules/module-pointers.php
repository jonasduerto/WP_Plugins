<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Pointers
 */
class TB_Pointers_Module extends Themify_Builder_Component_Module {
	public function __construct() {
		parent::__construct(array(
			'name' => __('Pointers', 'builder-pointers'),
			'slug' => 'pointers'
		));
	}

	public function get_assets() {
		$instance = Builder_Pointers::get_instance();
		return array(
			'selector'=>'.module-pointers',
			'css'=>themify_enque($instance->url.'assets/style.css'),
			'js'=>themify_enque($instance->url.'assets/scripts.js'),
			'ver'=>$instance->version,
			'external'=>Themify_Builder_Model::localize_js('BuilderPointers', apply_filters( 'builder_pointers_script_vars', array(
				'trigger' => 'hover',
			) ))
		);
	}

	public function get_options() {
		return array(
			array(
				'id' => 'mod_title',
				'type' => 'text',
				'label' => __('Module Title', 'builder-pointers'),
				'class' => 'large',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'image_url',
				'type' => 'image',
				'label' => __('Image URL', 'builder-pointers'),
				'class' => 'xlarge',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'title_image',
				'type' => 'text',
				'label' => __('Image Alt', 'builder-pointers'),
				'class' => 'fullwidth',
				'after' => '<small>' . __( 'Optional: Image alt is the image "alt" attribute. Primarily used for SEO describing the image.', 'builder-pointers' ) . '</small>',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'image_dimension',
				'type' => 'multi',
				'label' => __( 'Image Dimension', 'builder-pointers' ),
				'fields' => array(
					array(
						'id' => 'image_width',
						'type' => 'text',
						'label' => __('Width', 'builder-pointers'),
						'class' => 'medium',
						'help' => 'px',
						'value' => 300,
						'render_callback' => array(
							'binding' => 'live'
						)
					),
					array(
						'id' => 'image_height',
						'type' => 'text',
						'label' => __('Height', 'builder-pointers'),
						'class' => 'medium',
						'help' => 'px',
						'value' => 200,
						'render_callback' => array(
							'binding' => 'live'
						)
					),
				)
			),
			array(
				'type' => 'pointers'
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'css_class',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-pointers'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-pointers') ),
				'render_callback' => array(
					'binding' => 'live'
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'image_url' => 'https://themify.me/demo/themes/themes/wp-content/uploads/addon-samples/pointers-ipad-image.jpg',
			'image_width' => 400,
			'image_height' => 330
		);
	}

	public function get_styling() {
		return array(
                        //bacground
                        self::get_seperator('image_bacground', __('Background', 'themify'), false),
                        self::get_color('.module-pointers', 'background_color', __('Background Color', 'themify'), 'background-color'),
                        // Padding
                        self::get_seperator('padding', __('Padding', 'themify')),
                        self::get_padding('.module-pointers'),
                        // Margin
                        self::get_seperator('margin', __('Margin', 'themify')),
                        self::get_margin('.module-pointers'),
                        // Border
                        self::get_seperator('border', __('Border', 'themify')),
                        self::get_border('.module-pointers')
		);
	}

	protected function _visual_template() {
		$module_args = self::get_module_args();?>
		<#
		var blob_default = {
			title: '', 
			direction: 'bottom',
			pointer_color: '', 
			tooltip_background: '', 
			tooltip_color: '',
			left: '',
			top: '',
			link: '',
			auto_visible: 'no',
			pointer_hide: 'no'
		};
		#>
		<div class="module module-<?php echo $this->slug ?> {{ data.css_class }}">
			<# if( data.mod_title ) { #>
				<?php echo $module_args['before_title']; ?>
				{{{ data.mod_title }}}
				<?php echo $module_args['after_title']; ?>
			<# } #>

			<?php do_action( 'themify_builder_before_template_content_render' ); ?>
			
			<div class="showcase-image">
				<img src="{{ data.image_url }}" width="{{ data.image_width }}" height="{{ data.image_height }}" alt="{{ data.title_image }}" />
				
				<# _.each( data.blobs_showcase, function( blob, index ) { 
                                        if(!blob){
                                            return true;
					}
					_.defaults( blob, blob_default );
					if ( _.isUndefined( blob['left'] ) || '' === blob['left'] ) return;
					var pointerHide = blob.pointer_hide && blob.pointer_hide === 'yes' ? 'tooltipster-fade' : '';
					var pointerColor = blob.pointer_color ? 'background-color: ' + themifybuilderapp.Utils.toRGBA( blob.pointer_color ) + ';' : '';
					var direction = blob.direction ? blob.direction : 'top';
					var style = blob.tooltip_background ? 'background-color: ' + themifybuilderapp.Utils.toRGBA( blob.tooltip_background ) + ';' : '';
					style += blob.tooltip_color ? 'color: ' + themifybuilderapp.Utils.toRGBA( blob.tooltip_color ) + ';' : '';
					if( style ) {
                                            print( '<style type="text/css">body .tooltip-' + data.cid + '-' + index + ' { ' + style + ' }</style>' );
					} #>
					<div class="tb-blob blob-{{ index }} {{ pointerHide }}" style="top: {{ blob.top }}%; left: {{ blob.left }}%;" data-direction="{{ direction }}" data-theme="tooltipster-default tooltip-{{ data.cid }}-{{ index }}" data-visible="{{ blob.auto_visible }}" aria-describedby="blob-tooltip-{{ data.cid }}-{{ index }}">

						<# if( blob.title ) { #>
							<span class="tb-blob-tooltip" id="blob-tooltip-{{ data.cid }}-{{ index }}" style="display: none; visibility: hidden;" role="tooltip">{{{ blob.title }}}</span>
						<# }

						if( blob.link ) { #>
							<a href="{{ blob.link }}" >
						<# } #>

							<div class="tb-blob-icon" style="{{ pointerColor }}">
								<span style="{{ pointerColor }}"></span>
							</div>

						<# if( blob.link ) { #>
							</a>
						<# } #>

					</div>

				<# } ); #>
			</div>

			<?php do_action( 'themify_builder_after_template_content_render' ); ?>
		</div>
	<?php
	}
}

function themify_builder_field_pointers( $field, $module_name ) {
	echo '<div id="pointers">',
            '<p class="description">'. __( 'Click on the image to add tooltips (click tooltip points to edit again).', 'builder-pointers' ) .'</p>',
            '<div id="pointers-preview"><div><div class="loading"><i class="fa fa-gear fa-spin"></i></div></div></div>';

	themify_builder_module_settings_field( array(
		array(
			'id' => 'blobs_showcase',
			'type' => 'builder',
			'options' => array(
				array(
					'id' => 'title',
					'type' => 'wp_editor',
					'label' => '',
					'class' => 'large',
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
				array(
					'id' => 'direction',
					'type' => 'select',
					'label' => __('Tooltip Direction', 'builder-pointers'),
					'options' => array(
						'bottom' => __('Bottom', 'builder-pointers'),
						'top' => __('Top', 'builder-pointers'),
						'left' => __('Left', 'builder-pointers'),
						'right' => __('Right', 'builder-pointers'),
					),
					'default' => 'bottom',
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
				array(
					'id' => 'pointer_hide',
					'type' => 'select',
					'label' => __('Hide Pointer', 'builder-pointers'),
					'options' => array(
						'no' => __('No', 'builder-pointers'),
						'yes' => __('Yes', 'builder-pointers'),
					),
					'default' => 'no',
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
				array(
					'id' => 'auto_visible',
					'type' => 'select',
					'label' => __('Always Visible', 'builder-pointers'),
					'options' => array(
						'no' => __('No', 'builder-pointers'),
						'yes' => __('Yes', 'builder-pointers'),
					),
					'default' => 'no',
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
				array(
					'id' => 'pointer_color',
					'type' => 'text',
					'colorpicker' => true,
					'class' => 'large',
					'label' => __('Pointer Color', 'builder-pointers'),
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
				array(
					'id' => 'tooltip_background',
					'type' => 'text',
					'colorpicker' => true,
					'class' => 'large',
					'label' => __('Tooltip Background', 'builder-pointers'),
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
				array(
					'id' => 'tooltip_color',
					'type' => 'text',
					'colorpicker' => true,
					'class' => 'large',
					'label' => __('Tooltip Text Color', 'builder-pointers'),
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
				array(
					'id' => 'open',
					'type' => 'select',
					'label' => __('Open link in', 'builder-pointers'),
					'options' => array(
						'blank' => __('New Window', 'builder-pointers'),
						'' => __('Same Window', 'builder-pointers'),
						'lightbox' => __('Lightbox', 'builder-pointers'),
					),
					'default' => 'blank'
				),
				array(
					'id' => 'link',
					'type' => 'text',
					'label' => __('Link To', 'builder-pointers'),
					'class' => 'large'
				),
				array(
					'id' => 'left',
					'type' => 'text',
					'label' => __('Left', 'builder-pointers'),
					'class' => 'large',
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
				array(
					'id' => 'top',
					'type' => 'text',
					'label' => __('Top', 'builder-pointers'),
					'class' => 'large',
					'render_callback' => array(
						'binding' => 'live',
						'repeater' => 'blobs_showcase'
					)
				),
			),
			'render_callback' => array(
				'binding' => 'live',
				'control_type' => 'repeater'
			)
		),
	), $module_name );
	echo '</div>';
}

Themify_Builder_Model::register_module( 'TB_Pointers_Module' );