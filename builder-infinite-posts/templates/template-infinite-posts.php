<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Infinite Posts
 * 
 * Access original fields: $mod_settings
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):
    $fields_default = array(
        'mod_title' => '',
        'post_type_post' => 'post',
        'type_query_post' => 'category',
        'category_post' => '',
        'query_slug_post' => '',
        'post_per_page_post' => '',
        'offset_post' => '',
        'order_post' => 'desc',
        'orderby_post' => 'date',
        'background_style' => 'builder-parallax-scrolling',
        'pagination' => 'infinite-scroll',
        'layout' => 'parallax',
        'post_layout' => 'grid-1',
        'masonry' => 'enabled',
        'gutter' => 'default',
		'permalink' => 'default',
		'hide_post_image' => '',
        'image_size' => '',
        'img_width' => '',
        'img_height' => '',
        'read_more_text' => __('Read More', 'builder-infinite-posts'),
        'color_button' => 'red',
        'row_height' => 'height-default',
        'overlay_color' => '000000_0.30',
        'text_color' => 'ffffff',
        'animation_effect' => '',
        'display_content' => 'excerpt',
        'hide_post_title' => 'no',
        'hide_post_date' => 'yes',
        'read_more_size' => 'small',
        'unlink_image' => 'yes',
        'unlink_post_title' => 'no',
        'hide_post_meta' => 'yes',
        'buttons_style' => 'colored',
        'hide_read_more_button' => 'no',
        'css_post' => ''
    );

    if (isset($mod_settings['category_post'])) {
        $mod_settings['category_post'] = self::get_param_value($mod_settings['category_post']);
    }
    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
    $animation_effect = self::parse_animation_effect($fields_args['animation_effect'], $fields_args);

    $container_class = array('module', 'module-' . $mod_name, $module_ID, $fields_args['css_post'], 'pagination-' . $fields_args['pagination'], 'layout-' . $fields_args['layout']);
    if ($fields_args['layout'] === 'parallax') {
        $container_class[] = $fields_args['row_height'];
    } elseif ($fields_args['layout'] === 'grid' || $fields_args['layout'] === 'overlay') {
        $container_class[] = $fields_args['post_layout'];
        // disable masonry when using grid-1
        if ($fields_args['layout'] !== 'grid-1') {
            $container_class[] = 'gutter-' . $fields_args['gutter'];
            $container_class[] = 'masonry-' . $fields_args['masonry'];
        }
    }
    $container_class = implode(' ', apply_filters('themify_builder_module_classes', $container_class, $mod_name, $module_ID, $fields_args));

    global $wp, $post;
    /**
     * Do not use the global $paged variable, some pages (404 for example) don't support this
     * which breaks the pagination; the $_GET[tb_infinite] is used instead
     */
    $paged = 1;
    if (!empty($_GET['tb_infinite'])) {
        $paged = $_GET['tb_infinite'];
    }

// The Query

    $limit = $fields_args['post_per_page_post'];
    $type_query_post = $fields_args['type_query_post'];
    $terms = isset($fields_args["{$type_query_post}_post"]) ? $fields_args["{$type_query_post}_post"] : $fields_args['category_post'];
// deal with how category fields are saved
    $terms = preg_replace('/\|[multiple|single]*$/', '', $terms);

    $temp_terms = explode(',', $terms);
    $new_terms = array();
    $is_string = false;
    foreach ($temp_terms as $t) {
        if (!is_numeric($t)) {
            $is_string = true;
        }
        if ('' !== $t) {
            array_push($new_terms, trim($t));
        }
    }

    $args = array(
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'order' => $fields_args['order_post'],
        'orderby' => $fields_args['orderby_post'],
        'suppress_filters' => false,
        'paged' => $paged,
        'post_type' => $fields_args['post_type_post']
    );

    if (!empty($new_terms) && !in_array('0', $new_terms) && 'post_slug' !== $type_query_post) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => $type_query_post,
                'field' => $is_string ? 'slug' : 'id',
                'terms' => $new_terms,
                'operator' => ( '-' === substr($terms, 0, 1) ) ? 'NOT IN' : 'IN'
            )
        );
    }

    if (!empty($fields_args['query_slug_post']) && 'post_slug' == $type_query_post) {
        $args['post__in'] = self::parse_slug_to_ids($fields_args['query_slug_post'], $args['post_type']);
    }

// add offset posts
    if ($fields_args['offset_post'] !== '') {
        if (empty($limit)) {
            $limit = get_option('posts_per_page');
        }
        $args['offset'] = ( ( $paged - 1 ) * $limit ) + $fields_args['offset_post'];
    }

    $query = new WP_Query($args);
    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class,
        'data-url' => add_query_arg('tb_infinite', 0, remove_query_arg('tb_infinite')),
        'data-current-page' => $paged
            ), $fields_args, $mod_name, $module_ID);
    ?>

    <div <?php echo self::get_element_attributes($container_props); ?>>

        <?php if ($fields_args['mod_title'] !== ''): ?>
            <?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title'], $fields_args) . $fields_args['after_title']; ?>
        <?php endif; ?>

        <div class="builder-infinite-posts-wrap clearfix">

            <?php
            $template = self::locate_template("infinite-posts-{$fields_args['post_type_post']}.php");
            if ("infinite-posts-{$fields_args['post_type_post']}.php" === $template) {
                // use default template for Post post type to render
                $template = self::locate_template("infinite-posts-post.php");
            }
            include( $template );
            ?>
        </div><!-- .builder-infinite-posts-wrap -->

        <?php if ($fields_args['pagination'] === 'infinite-scroll' || $fields_args['pagination'] === 'links') : ?>

            <?php echo TB_Infinite_Posts_Module::get_pagenav('', '', $query, $fields_args['offset_post']); ?>

        <?php elseif ($fields_args['pagination'] === 'load-more') : ?>

            <?php
            $total_pages = $query->max_num_pages;
            $current_page = $paged;
            if ($total_pages > $current_page) :
                ?>
                <div class="infinite-posts-load-more-wrap">
                    <a class="ui builder_button rounded glossy white infinite-posts-load-more" href="<?php echo add_query_arg('tb_infinite', $paged + 1) ?>">
                        <i class="fa fa-cog fa-spin"></i>
                        <?php _e('Load More', 'builder-infinite-posts'); ?>
                    </a>
                </div><!-- .infinite-posts-load-more-wrap -->
            <?php endif; ?>

        <?php endif; ?>

    </div>
<?php endif; ?>
<?php TFCache::end_cache(); ?>