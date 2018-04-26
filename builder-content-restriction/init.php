<?php

/*
  Plugin Name:  Builder Content Restriction
  Plugin URI:   http://themify.me/addons/builder-content-restriction
  Version:      1.0.7
  Description:  Restrict modules and rows for specific user roles. With this addon enabled, you will see the restriction checkboxes in Row and Module option lightbox. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
  Author:       Themify
  Author URI:   http://themify.me/
  Text Domain:  builder-content-restriction
  Domain Path:  /languages

 */

class Builder_Content_Restriction {

    private $version;

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

    /**
     * Constructor
     *
     * @access 	private
     * @return 	void
     */
    private function __construct() {
        $this->constants();
        add_filter('themify_builder_row_fields_options', array($this, 'add_module_user_role_option'), 10, 1);
        add_filter('themify_builder_module_settings_fields', array($this, 'add_module_user_role_option'), 10, 2);
        if (is_admin()) {
            add_action('init', array($this, 'updater'));
            //ajax
            add_filter('themify_builder_module_classes', array($this, 'module_classes'), 10, 4);
            add_filter('themify_builder_row_classes', array($this, 'row_classes'), 10, 3);
        } elseif (class_exists('Themify_Builder_Component_Module') && !Themify_Builder_Model::is_front_builder_activate() && !Themify_Builder::$frontedit_active) {
            // Front end control
            add_filter('themify_builder_module_display', array($this, 'control_module_output'), 10, 3);
            add_filter('themify_builder_row_display', array($this, 'control_row_output'), 10, 2);
        } else {
            add_action('themify_builder_frontend_enqueue', array($this, 'assets'), 10, 1);
        }
    }

    function constants() {
        $data = get_file_data(__FILE__, array('Version'));
        $this->version = $data[0];
    }

    /**
     * Append user role option to modules.
     * 
     * @param	array $options
     * @param	array $module_name
     * @access 	public
     * @return 	array
     */
    function add_module_user_role_option($options, $module = null) {
        // In case of modules we can use checkbox option type because they support that type.
        static $roles = array();
        $component = isset($module) ? $module->slug : 'row';
        $user_role_option = array(
            'id' => $component . '_user_role',
            'type' => 'checkbox',
            'label' => __('User Role', 'builder-content-restriction'),
            'options' => array(),
            'before' => '<small>' . __('Check the user role(s) you want to show this content. Default is visible to everyone.', 'builder-content-restriction') . '</small>'
        );
        if (empty($roles)) {
            $roles[] = array('name' => '_cr_logged_in', 'value' => __('Logged in users', 'builder-content-restriction'));
            $roles[] = array('name' => '_cr_logged_out', 'value' => __('Logged out users', 'builder-content-restriction'));
            foreach ($this->get_roles() as $role => $details) {
                $name = translate_user_role(strtolower($details['name']));
                $roles[] = array('name' => $role, 'value' => $name);
            }
        }
        $user_role_option['options'] = $roles;
        $options[] = $user_role_option;
        return $options;
    }

    /**
     * Get roles
     * @access 	private
     * @return 	array Return array of all registered roles.
     */
    public function get_roles() {
        if (!function_exists('get_editable_roles')) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }
        $roles = get_editable_roles();
        return $roles;
    }

    /**
     * Get current roles
     * @access 	private
     * @return 	array Array with curent user roles.
     */
    public function get_current_user_roles() {
        $current_user_roles = array();
        $current_user = wp_get_current_user();
        $current_user_roles = $current_user->roles;

        return $current_user_roles;
    }

    /**
     * Control front end display of modules.
     * @access 	public
     * @return 	array
     */
    function control_module_output($display, $mod, $builder_id) {
        // Check weather the front end editor is active
        if (isset($mod['mod_settings'])) {
            $mod_settings = $mod['mod_settings'];
            $module_name = $mod['mod_name'];
            $role_settings_key = $module_name . '_user_role';

            // Check weather role restriction option exists for a given module or not.
            if (!array_key_exists($role_settings_key, $mod_settings)) {
                return $display;
            }

            // Check weather role restriction was set by user or not.
            if ('|' !== $mod_settings[$role_settings_key]) {
                $allowed_roles = explode('|', $mod_settings[$role_settings_key]);

                if (( in_array('_cr_logged_in', $allowed_roles) && is_user_logged_in() ) || ( in_array('_cr_logged_out', $allowed_roles) && !is_user_logged_in() )) {
                    return $display;
                }
                // Compare current user roles with allowed ones. If there is a match allow module appear on front end.
                $comparision = array_intersect($allowed_roles, $this->get_current_user_roles());
                // If there is no match it means that for current user module shouldn't be shown. 
                if (empty($comparision)) {
                    return false;
                }
            }
        }
        return $display;
    }

    /**
     * Control front end display of row module.
     * @access 	public
     * @return 	array
     */
    function control_row_output($display, $row) {

        // Stop work if settings for row is empty ie. wasn't set. 
        if (isset($row['styling'])) {
            $mod_settings = $row['styling'];
            // Check weather role restriction was set or not.
            if (!empty($mod_settings['row_user_role'])) {
                $mod['mod_name'] = 'row';
                $mod['mod_settings'] = $mod_settings;
                return $this->control_module_output($display, $mod, '');
            }
        }
        return $display;
    }

    public function module_classes($classes, $mod_name = null, $module_ID = null, $args = null) {
        if (isset($args["{$mod_name}_user_role"])) {
            $roles = array_filter(explode('|', $args["{$mod_name}_user_role"]));
            if (!empty($roles)) {
                $classes[] = 'has-restriction';
            }
        }
        return $classes;
    }

    public function row_classes($classes, $row, $builder_id) {
        if (!empty($row['styling']['row_user_role'])) {
            $classes[] = 'has-restriction';
        }
        return $classes;
    }

    public function assets() {
        wp_enqueue_style('builder-content-restriction', themify_enque(plugins_url('assets/style.css', __FILE__), null, $this->version));
    }

    public function updater() {
        if (class_exists('Themify_Builder_Updater')) {
            if (!function_exists('get_plugin_data')) {
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

}

add_action('themify_builder_setup_modules', array('Builder_Content_Restriction', 'get_instance'));
