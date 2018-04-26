<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Bar Chart
 * 
 * Access original fields: $mod_settings
 * @author Themify
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):
    $fields_default = array(
        'mod_title_bar_chart' => '',
        'mod_height_bar_chart' => '',
        'content_bar_chart' => array(),
        'label_direction_chart' => 'horizontal',
        'animation_effect' => '',
        'css_bar_chart' => ''
    );

    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
    $animation_effect = self::parse_animation_effect($fields_args['animation_effect'], $fields_args);

    $container_class = implode(' ', apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $mod_name, $module_ID, 'label-direction-' . $fields_args['label_direction_chart'], $fields_args['css_bar_chart'], $animation_effect
                    ), $mod_name, $module_ID, $fields_args)
    );

    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class
            ), $fields_args, $mod_name, $module_ID);
    ?>
    <!-- module bar-chart -->
    <div <?php echo self::get_element_attributes($container_props); ?>>

        <?php if ($fields_args['mod_title_bar_chart'] !== '') : ?>
            <?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title_bar_chart'], $fields_args) . $fields_args['after_title']; ?>
        <?php endif; ?>

        <?php do_action('themify_builder_before_template_content_render'); ?>

        <div class="module-<?php echo $mod_name; ?>">
            <?php if (!empty($fields_args['content_bar_chart'])): ?>
                <ul class="bar-chart-content bc-chart" <?php echo (!empty($fields_args['mod_height_bar_chart']) ? 'style="height: ' . $fields_args['mod_height_bar_chart'] . 'px; "' : '' ); ?>>
                    <?php
                    foreach ($fields_args['content_bar_chart'] as $bar):
                        $bar = wp_parse_args($bar, array(
                            'bar_chart_label' => '',
                            'bar_chart_percentage' => 0,
                            'bar_chart_number' => '',
                            'bar_chart_color' => ''
                        ));
                        ?>
                        <li>
                            <span class="bc-bar" data-height="<?php echo $bar['bar_chart_percentage']; ?>" style="background-color: <?php echo Themify_Builder_Stylesheet::get_rgba_color($bar['bar_chart_color']); ?>" title="<?php echo $bar['bar_chart_label']; ?>">
                                <span class="bc-value"><?php echo $bar['bar_chart_number']; ?></span>
                            </span>
                        </li>
                    <?php endforeach ?>
                </ul>  
            <?php endif; ?>
        </div>
        <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module bar-chart -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>