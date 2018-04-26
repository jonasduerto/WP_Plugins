<?php

/*
  Plugin Name:  Builder Tiles
  Plugin URI:   http://themify.me/addons/tiles
  Version:      1.3.5
  Author:       Themify
  Description:  A Builder addon to make flippable Tiles like Windows 8 Metro layouts. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
  Text Domain:  builder-tiles
  Domain Path:  /languages
 */

defined('ABSPATH') or die('-1');

if (!class_exists('Builder_Tiles')) {
	
	class Builder_Tiles {

		public $url;
		private $dir;
		public $version;

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @return	A single instance of this class.
		 */
		public static function get_instance() {
			static $instance = null;
			if ($instance === null) {
				$instance = new self;
			}
			return $instance;
		}

		private function __construct() {
			$this->constants();
			include( $this->dir . 'includes/admin.php' );
			add_action('init', array($this, 'i18n'), 5);
			add_action('themify_builder_setup_modules', array($this, 'register_module'));
			if(is_admin()){
				
				add_action('init', array($this, 'updater'));
				add_action('themify_do_metaboxes', array($this, 'themify_do_metaboxes'));
			}
			else{
				if(!Themify_Builder_Model::is_front_builder_activate()){
			  
					add_action('wp_head', array($this, 'dynamic_css'));
				}
				else{
					add_action('themify_builder_frontend_enqueue', array($this, 'enque_scripts'), 15);
				}
				add_filter('themify_builder_script_vars', array($this, 'themify_builder_script_vars'));
				add_filter('themify_main_script_vars', array($this, 'minify_vars'), 10, 1);
			}
		  
		   
		}

		public function constants() {
			$data = get_file_data(__FILE__, array('Version'));
			$this->version = $data[0];
			$this->url = defined('BUILDER_TILES_URL') ? BUILDER_TILES_URL : trailingslashit(plugin_dir_url(__FILE__));
			$this->dir = defined('BUILDER_TILES_DIR') ? BUILDER_TILES_DIR : trailingslashit(plugin_dir_path(__FILE__));
		}

		public function i18n() {
			load_plugin_textdomain('builder-tiles', false, '/languages');
		}

		/**
		 * Load animate.css library when Tiles module is used in the page
		 *
		 * @since 1.1.3
		 */
		public function themify_builder_script_vars($vars) {
			$vars['animationInviewSelectors'][] = '.module.module-tile';
			return $vars;
		}

		public function enque_scripts() {
		   wp_enqueue_script( 'themify-builder-tiles-admin', themify_enque($this->url . 'assets/admin.js'), array( 'jquery' ), $this->version, true );
		}

		public function register_module() {    
			//temp code for compatibility  builder new version with old version of addon to avoid the fatal error, can be removed after updating(2017.07.20)
			if (class_exists('Themify_Builder_Component_Module')) {
		  
				Themify_Builder_Model::register_directory('templates', $this->dir . 'templates');
				Themify_Builder_Model::register_directory('modules', $this->dir . 'modules');
			}
		}

		public function get_tile_sizes() {
			return apply_filters('builder_tiles_sizes', array(
				'square-large' => array('label' => __('Square Large', 'builder-tiles'), 'width' => 480, 'height' => 480, 'mobile_width' => 280, 'mobile_height' => 280, 'image' => $this->url . 'assets/size-sl.png'),
				'square-small' => array('label' => __('Square Small', 'builder-tiles'), 'width' => 240, 'height' => 240, 'mobile_width' => 140, 'mobile_height' => 140, 'image' => $this->url . 'assets/size-ss.png'),
				'landscape' => array('label' => __('Landscape', 'builder-tiles'), 'width' => 480, 'height' => 240, 'mobile_width' => 280, 'mobile_height' => 140, 'image' => $this->url . 'assets/size-l.png'),
				'portrait' => array('label' => __('Portrait', 'builder-tiles'), 'width' => 240, 'height' => 480, 'mobile_width' => 140, 'mobile_height' => 280, 'image' => $this->url . 'assets/size-p.png'),
					));
		}

		public function dynamic_css($echo=true) {
			$css = '';
			foreach ($this->get_tile_sizes() as $key => $size) {
				$css .= sprintf('
			.module-tile.size-%1$s,
			.module-tile.size-%1$s .tile-background img,
			.module-tile.size-%1$s .map-container {
				width: %2$spx;
				height: %3$spx;
				max-width: 100%%;
			}', $key, $size['width'], $size['height'], $size['mobile_width'], $size['mobile_height']
				);
			}

			$gutter = (int) $this->get_option('gutter');
			if ($gutter) {
				$css .= '.module-tile .tile-flip-box-wrap { padding: ' . $gutter . 'px; } .tiles-wrap { width: calc( 100% + ' . $gutter * 2 . 'px ); margin-left: -' . $gutter . 'px; }';
			}
			$css = sprintf('<style type="text/css">%s</style>', $css); 
			if($echo===false){
				return $css;
			}
			echo $css;
		}

		public function themify_do_metaboxes($panels) {
			$options = array(
				array(
					"name" => "builder_tiles_fluid_tiles",
					"title" => __('Fluid Tiles', 'builder-tiles'),
					"description" => __("If enabled, tiles will display fluid in % width (eg. small tile will be 25% width)", 'builder-tiles'),
					"type" => "dropdown",
					"meta" => array(
						array("value" => "", "name" => __('Default', 'builder-tiles'), "selected" => true),
						array("value" => 'yes', 'name' => __('Enable', 'themify')),
						array("value" => 'no', 'name' => __('Disable', 'themify'))
					)
				),
				array(
					"name" => "builder_tiles_fluid_tiles_base_size",
					"title" => __('Base Tile Size', 'builder-tiles'),
					"description" => __("Only works if Fluid Tiles is enabled to control how many tiles will be rendered in a row according to the tile size.", 'builder-tiles'),
					"type" => "dropdown",
					"meta" => array(
						array("value" => "", "name" => __('Default', 'builder-tiles'), "selected" => true),
						array("value" => '16', 'name' => __('16%', 'themify')),
						array("value" => '20', 'name' => __('20%', 'themify')),
						array("value" => '25', 'name' => __('25%', 'themify')),
						array("value" => '30', 'name' => __('30%', 'themify'))
					)
				),
				array(
					"name" => "builder_tiles_gutter",
					"title" => __('Tile Spacing', 'builder-tiles'),
					"description" => "",
					"type" => "textbox",
					"meta" => array("size" => "small"),
					'after' => ' px'
				),
			);
			$panels[] = array(
				'name' => __('Builder Tiles', 'builder-tiles'),
				'id' => 'builder-tiles',
				'options' => $options,
				'pages' => 'page'
			);

			return $panels;
		}

		public function get_option( $name ) {
			$options = wp_parse_args( get_option( 'builder_tiles', array() ), $this->get_defaults() );
			$value = isset( $options[ $name ] ) ? $options[ $name ] : null;
			if ( is_page() ) {
				if ( $single_value = get_post_meta( get_the_id(), "builder_tiles_{$name}", true ) ) {
					$value = $single_value;
				}
			}

			return $value;
		}

		function get_defaults() {
			return array(
				'fluid_tiles' => 1,
				'gutter' => 0,
				'fluid_tiles_base_size' => 16,
			);
		}

		public function updater() {
			if (class_exists('Themify_Builder_Updater')) {
				if (!function_exists('get_plugin_data')){
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}
				$plugin_basename = plugin_basename(__FILE__);
				$plugin_data = get_plugin_data(trailingslashit(plugin_dir_path(__FILE__)) . basename($plugin_basename));
				new Themify_Builder_Updater(array(
					'name' => trim(dirname($plugin_basename), '/'),
					'nicename' => $plugin_data['Name'],
					'update_type' => 'addon',
						), $this->version, trim($plugin_basename, '/'));
			}
		}

		public function minify_vars($vars) {
			$vars['minify']['js']['themify.widegallery'] = themify_enque($this->url . 'assets/themify.widegallery.js', true);
			return $vars;
		}

	}
	add_action('themify_builder_before_init',array('Builder_Tiles','get_instance'));
}