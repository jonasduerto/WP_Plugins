<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Button Pro
 * 
 * Access original fields: $mod_settings
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):
    $fields_default = array(
        'type_button' => 'external',
        'link_label' => '',
        'icon_button' => '',
        'color_button' => '',
        'color_button_hover' => '',
        'appearance_button' => '',
        'link_button' => '#',
        'param_button' => '',
        'content_modal_button' => '',
        'modules_reveal_behavior_button' => 'hide',
        'show_less_label_button' => __('Show less', 'builder-button'),
        'add_css_button' => '',
        'animation_effect' => ''
    );

    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
    $animation_effect = self::parse_animation_effect($fields_args['animation_effect'], $fields_args);
    $param_button = $fields_args['param_button'] !== '' ? array_values(explode('|', $fields_args['param_button'])) : array();

    if (isset($fields_args['appearance_button'])) {
        $fields_args['appearance_button'] = self::get_checkbox_data($fields_args['appearance_button']);
    }
    $container_class = implode(' ', apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $mod_name, $module_ID, 'button-link-' . $fields_args['type_button'], $fields_args['add_css_button'], $animation_effect
                    ), $mod_name, $module_ID, $fields_args)
    );

    $atts = array(
        'class' => ( ( 'default' === $fields_args['color_button'] ) ? '' : 'ui builder_button ' . $fields_args['color_button'] ) . ' ' . $fields_args['appearance_button'],
        'href' => $fields_args['link_button']
    );
    if ('modal' === $fields_args['type_button']) {
        $atts['href'] = '#modal-' . $module_ID;
        $atts['class'] .= ' themify_lightbox';
    } elseif ('row_scroll' === $fields_args['type_button']) {
        $atts['href'] = '#';
        $atts['class'] .= ' scroll-next-row';
    } elseif ('modules_reveal' === $fields_args['type_button']) {
        $atts['href'] = '#';
        $atts['class'] .= ' modules-reveal';
        $atts['data-behavior'] = $fields_args['modules_reveal_behavior_button'];
        $atts['data-label'] = $fields_args['link_label'];
        $atts['data-lesslabel'] = $fields_args['show_less_label_button'];
    } else {
        if (in_array('lightbox', $param_button)) {
            $atts['class'] .= ' themify_lightbox';
        } elseif (in_array('newtab', $param_button)) {
            $atts['target'] = '_blank';
        }
    }
    if ($fields_args['color_button_hover'] !== '') {
        $atts['data-hover'] = $fields_args['color_button_hover'];
        $atts['data-remove'] = $fields_args['color_button'];
    }
    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class
            ), $fields_args, $mod_name, $module_ID);
    ?>
    <!-- module button pro -->
    <div <?php echo self::get_element_attributes($container_props); ?>>

        <?php do_action('themify_builder_before_template_content_render'); ?>

        <a <?php echo self::get_element_attributes($atts); ?>>
            <?php if ('' !== $fields_args['icon_button']) : ?><i class="<?php echo themify_get_icon($fields_args['icon_button']); ?>"></i> <?php endif; ?>
            <span><?php echo $fields_args['link_label']; ?></span>
        </a>

        <?php if ('modal' === $fields_args['type_button']) : ?>
            <div id="modal-<?php echo $module_ID ?>" class="mfp-hide">
                <?php echo apply_filters('themify_builder_module_content', $fields_args['content_modal_button']); ?>
            </div>
        <?php endif; ?>

        <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module button pro -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>