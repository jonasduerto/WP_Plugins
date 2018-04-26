<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Pointers
 * 
 * Access original fields: $mod_settings
 * @author Themify
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):
    $fields_default = array(
        'mod_title' => '',
        'image_url' => '',
        'blobs_showcase' => array(),
        'title_image' => '',
        'image_width' => '',
        'image_height' => '',
        'css_class' => '',
        'animation_effect' => ''
    );
    $blob_default = array(
        'title' => '',
        'direction' => 'bottom',
        'pointer_color' => '',
        'tooltip_background' => '',
        'tooltip_color' => '',
        'left' => '',
        'top' => '',
        'link' => '',
        'auto_visible' => 'no',
        'pointer_hide' => 'no'
    );

    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
    $animation_effect = self::parse_animation_effect($fields_args['animation_effect'], $fields_args);

    $container_class = implode(' ', apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $mod_name, $module_ID, $fields_args['css_class'], $animation_effect
                    ), $mod_name, $module_ID, $fields_args)
    );

    $image = themify_do_img($fields_args['image_url'], $fields_args['image_width'], $fields_args['image_height']);

    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class
            ), $fields_args, $mod_name, $module_ID);
    ?>
    <!-- module pointers -->
    <div <?php echo self::get_element_attributes($container_props); ?>>

        <?php if ($fields_args['mod_title'] !== ''): ?>
            <?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title'], $fields_args) . $fields_args['after_title']; ?>
        <?php endif; ?>

        <?php do_action('themify_builder_before_template_content_render'); ?>

        <div class="showcase-image">
            <img src="<?php echo $image['url']; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" alt="<?php esc_attr_e($fields_args['title_image']); ?>" />

            <?php
            foreach ($fields_args['blobs_showcase'] as $key => $blob) :
                $blob = wp_parse_args($blob, $blob_default);
                if ($blob['left'] === '') {
                    continue;
                }
                $pointer_hide = isset($blob['pointer_hide']) && $blob['pointer_hide'] === 'yes';
                $pointer_color = '' !== $blob['pointer_color'] ? 'background-color: ' . Themify_Builder_Stylesheet::get_rgba_color($blob['pointer_color']) . ';' : '';

                $style = '' !== $blob['tooltip_background'] ? 'background-color: ' . Themify_Builder_Stylesheet::get_rgba_color($blob['tooltip_background']) . ';' : '';
                $style .= '' !== $blob['tooltip_color'] ? 'color: ' . Themify_Builder_Stylesheet::get_rgba_color($blob['tooltip_color']) . ';' : '';
                $k = $module_ID . '-' . $key;
                if ('' !== $style):
                    ?>
                    <style type="text/css">body .tooltip-<?php echo $k ?>{<?php echo $style ?> }</style>
                <?php endif; ?>
                <div class="tb-blob blob-<?php echo $key; ?><?php echo $pointer_hide ? ' tooltipster-fade' : '' ?>" style="top: <?php echo $blob['top']; ?>%; left: <?php echo $blob['left']; ?>%;" data-direction="<?php echo $blob['direction']; ?>" data-theme="tooltipster-default <?php echo 'tooltip-' . $k ?>" data-visible="<?php echo $blob['auto_visible']; ?>" aria-describedby="<?php echo 'blob-tooltip-' . $k; ?>">

                    <?php if ('' !== $blob['title']) : ?>
                        <span class="tb-blob-tooltip" id="<?php echo 'blob-tooltip-' . $k; ?>" style="display: none; visibility: hidden;" role="tooltip"><?php echo apply_filters('themify_builder_module_content', $blob['title']); ?></span>
                    <?php endif; ?>

                    <?php if ('' != $blob['link']) : ?>
                        <a href="<?php echo $blob['link']; ?>"
                           <?php if (isset($blob['open']) && $blob['open'] === 'lightbox'): ?> class="themify_lightbox"<?php endif; ?>
                           <?php if (isset($blob['open']) && $blob['open'] === 'blank'): ?> target="_blank"<?php endif; ?> 
                           >
                           <?php endif; ?>

                        <div class="tb-blob-icon" style="<?php echo $pointer_color; ?>">
                            <span style="<?php echo $pointer_color; ?>"></span>
                        </div>

                        <?php if ('' !== $blob['link']) : ?></a><?php endif; ?>

                </div>

            <?php endforeach; ?>
        </div>

        <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module pointers -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>