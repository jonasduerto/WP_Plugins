<?php

/*
  Plugin Name:  Builder Pointers
  Plugin URI:   http://themify.me/addons/pointers
  Version:      1.1.5
  Author:       Themify
  Description:  Create tooltip-like pointers on any image. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
  Text Domain:  builder-pointers
  Domain Path:  /languages
 */

defined('ABSPATH') or die('-1');

class Builder_Pointers {

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
        if($instance===null){
            $instance = new self;
        }
        return $instance;
    }

    private function __construct() {
        $this->constants();
        $this->i18n();
        add_action('themify_builder_setup_modules', array($this, 'register_module'));
        if (is_admin()) {
            add_action('init', array($this, 'updater'));
            add_action('themify_builder_admin_enqueue', array($this, 'admin_enqueue'), 15);
            add_action('wp_ajax_builder_pointers_get_image', array($this, 'ajax_get_image'));
        }
        else{
            
            add_action('themify_builder_frontend_enqueue', array($this, 'admin_enqueue'));
            add_filter('themify_styles_top_frame', array($this, 'admin_enqueue'), 10, 1);
        }
    }

    public function constants() {
        $data = get_file_data(__FILE__, array('Version'));
        $this->version = $data[0];
        $this->url = trailingslashit(plugin_dir_url(__FILE__));
        $this->dir = trailingslashit(plugin_dir_path(__FILE__));
    }

    public function i18n() {
        load_plugin_textdomain('builder-pointers', false, '/languages');
    }

    public function admin_enqueue($styles=false) {
        //temp code for compatibility  builder new version with old version of addon to avoid the fatal error, can be removed after updating(2017.07.20)
        if(!class_exists('Themify_Builder_Component_Module')){
            return;
        }	
        $url = themify_enque($this->url . 'assets/admin.css');
        if(Themify_Builder_Model::is_front_builder_activate() || !$styles){
            wp_enqueue_script('themify-builder-pointers-admin', themify_enque($this->url . 'assets/admin.js'), array(), $this->version, true);
        }
        if($styles){
            $styles[] = $url;
            return $styles;
        }
        wp_enqueue_style('builder-pointers-admin-css', $url,array(), $this->version);
    }

    public function ajax_get_image() {
        if (!empty($_POST['pointers_image'])) {
            $url = trim($_POST['pointers_image']);
            $width = trim($_POST['pointers_width']);
            $height = trim($_POST['pointers_height']);
            $image = themify_do_img($url, $width, $height);
            echo '<img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="" />';
        }
        die;
    }

    public function register_module() {
        //temp code for compatibility  builder new version with old version of addon to avoid the fatal error, can be removed after updating(2017.07.20)
        if (class_exists('Themify_Builder_Component_Module')) {
            Themify_Builder_Model::register_directory('templates', $this->dir . 'templates');
            Themify_Builder_Model::register_directory('modules', $this->dir . 'modules');
        }
    }

    public function updater() {
        if (class_exists('Themify_Builder_Updater')) {
            if (!function_exists('get_plugin_data'))
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            $plugin_basename = plugin_basename(__FILE__);
            $plugin_data = get_plugin_data(trailingslashit(plugin_dir_path(__FILE__)) . basename($plugin_basename));
            new Themify_Builder_Updater(array(
                'name' => trim(dirname($plugin_basename), '/'),
                'nicename' => $plugin_data['Name'],
                'update_type' => 'addon',
                    ), $this->version, trim($plugin_basename, '/'));
        }
    }

}

add_action('themify_builder_before_init', array('Builder_Pointers', 'get_instance'));
