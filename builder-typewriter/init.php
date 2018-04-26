<?php
/*
Plugin Name:  Builder Typewriter
Plugin URI:   http://themify.me/addons/builder-typewriter
Version:      1.0.7
Description:  This Builder addon allows you to create a module with typewriter effect. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Author:       Themify
Author URI:   http://themify.me/
Text Domain:  builder-typewriter
Domain Path:  /languages
*/

/* Exit if accessed directly */
defined( 'ABSPATH' ) or die( '-1' );


class Builder_Typewriter {
	
	public $version;
	public $url;
	private $dir;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
            if ( version_compare( PHP_VERSION, '5.3.0' ) < 0 ) {
                add_action( 'admin_notices', array($this,'builder_typewriter_admin_notice' ));
                return;
            }
            static $instance = null;
            if($instance===null){
                $instance = new self;
            }
            return $instance;
	}

	/**
	 * Constructor
	 *
	 * @access	private
	 * @return	void
	 */
	private function __construct() {
		$this->constants();

		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );
		add_action( 'themify_builder_setup_modules', array( $this, 'register_module' ) );
		if(is_admin()){
                    add_action( 'themify_builder_admin_enqueue', array( $this, 'admin_enqueue' ), 15 );
                    add_action( 'init', array( $this, 'updater' ) );
                }
                add_filter('themify_main_script_vars',array( $this, 'minify_vars' ),10,1);
	}

	private function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	public function i18n() {
		load_plugin_textdomain( 'builder-typewriter', false, '/languages' );
	}

	public function admin_enqueue() {
		wp_enqueue_script( 'themify-builder-typewriter-admin-scripts', themify_enque($this->url . 'assets/admin-scripts.js'), array( 'jquery' ), $this->version );
	}

	public function register_module() {
		 //temp code for compatibility  builder new version with old version of addon to avoid the fatal error, can be removed after updating(2017.07.20)
                if(class_exists('Themify_Builder_Component_Module')){
                    Themify_Builder_Model::register_directory( 'templates', $this->dir . 'templates' );
                    Themify_Builder_Model::register_directory( 'modules', $this->dir . 'modules' );
                }
	}

	public function updater() {
		if( class_exists( 'Themify_Builder_Updater' ) ) {
			if ( ! function_exists( 'get_plugin_data') ){
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                        }
			$plugin_basename = plugin_basename( __FILE__ );
			$plugin_data = get_plugin_data( trailingslashit( plugin_dir_path( __FILE__ ) ) . basename( $plugin_basename ) );
			new Themify_Builder_Updater( array(
				'name' => trim( dirname( $plugin_basename ), '/' ),
				'nicename' => $plugin_data['Name'],
				'update_type' => 'addon',
			), $this->version, trim( $plugin_basename, '/' ) );
		}
	}
        
        public function minify_vars($vars){
            $vars['minify']['js']['jquery.typer.themify'] = themify_enque($this->url . 'assets/jquery.typer.themify.js',true);
            return $vars;
        }
        
        public function builder_typewriter_admin_notice() {
        ?>
            <div class="error">
                    <p><?php _e( 'This addon requires PHP 5.3 or higher.', 'builder-typewriter' ); ?></p>
            </div>
        <?php
                deactivate_plugins( plugin_basename( __FILE__ ) );
        }
}

Builder_Typewriter::get_instance();