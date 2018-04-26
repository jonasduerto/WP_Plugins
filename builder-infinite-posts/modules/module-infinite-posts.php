<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Infinite Posts
 */

class TB_Infinite_Posts_Module extends Themify_Builder_Component_Module {

    function __construct() {
        parent::__construct(array(
            'name' => __('Infinite Posts', 'builder-infinite-posts'),
            'slug' => 'infinite-posts'
        ));
    }

    function get_assets() {
        $instance = Builder_Infinite_Posts::get_instance();
        return array(
            'selector' => '.module-infinite-posts',
            'css' => themify_enque($instance->url . 'assets/style.css'),
            'js' => themify_enque($instance->url . 'assets/scripts.js'),
            'ver' => $instance->version,
            'external' => Themify_Builder_Model::localize_js('builderInfinitePosts', array(
                'url' => $instance->url,
                'loading_image' => $instance->url . 'assets/loading.gif',
                'bufferPx' => 10,
                'pixelsFromNavToBottom' => 30
            ))
        );
    }

    public function get_options() {
        $is_disabled = Themify_Builder_Model::is_img_php_disabled();
        $image_sizes = !$is_disabled ? themify_get_image_sizes_list(false) : array();
        $colors = Themify_Builder_Model::get_colors();
        $colors[] = array('img' => 'transparent', 'value' => 'transparent', 'label' => __('Transparent', 'themify'));
        $taxonomies = Themify_Builder_Model::get_public_taxonomies();
        $term_options = array();

        foreach ($taxonomies as $key => $label) {
            $term_options[] = array(
                'id' => $key . '_post',
                'label' => $label,
                'type' => 'query_category',
                'options' => array('taxonomy' => $key),
                'wrap_with_class' => 'tb-group-element tb-group-element-' . $key
            );
        }

        /* allow query posts by slug */
        $taxonomies['post_slug'] = __('Slug', 'builder-infinite-posts');

        $options = array(
            array(
                'id' => 'mod_title',
                'type' => 'text',
                'label' => __('Module Title', 'builder-infinite-posts'),
                'class' => 'large',
            ),
            array(
                'id' => 'post_type_post',
                'type' => 'select',
                'label' => __('Post Type', 'builder-infinite-posts'),
                'options' => Themify_Builder_Model::get_public_post_types()
            ),
            array(
                'id' => 'type_query_post',
                'type' => 'radio',
                'label' => __('Query by', 'builder-infinite-posts'),
                'options' => $taxonomies,
                'default' => 'category',
                'option_js' => true
            ),
            array(
                'type' => 'group',
                'fields' => $term_options
            ),
            array(
                'id' => 'query_slug_post',
                'type' => 'text',
                'label' => __('Post Slugs', 'builder-infinite-posts'),
                'class' => 'large',
                'wrap_with_class' => 'tb-group-element tb-group-element-post_slug',
                'help' => '<br/>' . __('Insert post slug. Multiple slug should be separated by comma (,)', 'builder-infinite-posts'),
            ),
            array(
                'id' => 'post_per_page_post',
                'type' => 'text',
                'label' => __('Limit', 'builder-infinite-posts'),
                'class' => 'xsmall',
                'help' => __('number of posts to show', 'builder-infinite-posts'),
            ),
            array(
                'id' => 'offset_post',
                'type' => 'text',
                'label' => __('Offset', 'builder-infinite-posts'),
                'class' => 'xsmall',
                'help' => __('number of post to displace or pass over', 'builder-infinite-posts'),
            ),
            array(
                'id' => 'order_post',
                'type' => 'select',
                'label' => __('Order', 'builder-infinite-posts'),
                'help' => __('Descending = show newer posts first', 'builder-infinite-posts'),
                'options' => array(
                    'desc' => __('Descending', 'builder-infinite-posts'),
                    'asc' => __('Ascending', 'builder-infinite-posts')
                )
            ),
            array(
                'id' => 'orderby_post',
                'type' => 'select',
                'label' => __('Order By', 'builder-infinite-posts'),
                'options' => array(
                    'date' => __('Date', 'builder-infinite-posts'),
                    'id' => __('Id', 'builder-infinite-posts'),
                    'author' => __('Author', 'builder-infinite-posts'),
                    'title' => __('Title', 'builder-infinite-posts'),
                    'name' => __('Name', 'builder-infinite-posts'),
                    'modified' => __('Modified', 'builder-infinite-posts'),
                    'rand' => __('Random', 'builder-infinite-posts'),
                    'comment_count' => __('Comment Count', 'builder-infinite-posts')
                )
            ),
            array(
                'id' => 'layout',
                'type' => 'radio',
                'label' => __('Layout', 'builder-infinite-posts'),
                'options' => array(
                    'parallax' => __('Parallax', 'builder-infinite-posts'),
                    'list' => __('List', 'builder-infinite-posts'),
                    'grid' => __('Grid', 'builder-infinite-posts'),
                    'overlay' => __('Overlay', 'builder-infinite-posts'),
                ),
                'default' => 'parallax',
                'option_js' => true
            ),
            array(
                'id' => 'post_layout',
                'type' => 'layout',
                'mode' => 'sprite',
                'label' => __('Post Layout', 'builder-infinite-posts'),
                'options' => array(
                    array('img' => 'grid2', 'value' => 'grid-2', 'label' => __('Grid 2', 'builder-infinite-posts')),
                    array('img' => 'grid3', 'value' => 'grid-3', 'label' => __('Grid 3', 'builder-infinite-posts')),
                    array('img' => 'grid4', 'value' => 'grid-4', 'label' => __('Grid 4', 'builder-infinite-posts')),
                ),
                'default' => '',
                'wrap_with_class' => 'tb-group-element tb-group-element-grid tb-group-element-overlay'
			),
			array(
				'id' => 'hide_post_image',
				'type' => 'checkbox',
				'label' => __('Post Image', 'themify'),
				'options' => array(
					array( 'name' => 'yes', 'value' => __('hide image', 'themify'))
				),
				'binding' => array(
					'checked' => array(
						'hide' => array('image_size', 'img_width', 'img_height')
					),
					'not_checked' => array(
						'show' => array('image_size', 'img_width', 'img_height')
					)
				),
				'wrap_with_class' => 'tb-group-element tb-group-element-grid tb-group-element-list'
			),
            array(
                'id' => 'image_size',
                'type' => 'select',
                'label' => __('Image Size', 'builder-infinite-posts'),
                'empty' => array(
                    'val' => '',
                    'label' => ''
                ),
                'hide' => !$is_disabled,
                'options' => $image_sizes,
                'wrap_with_class' => 'tb-group-element tb-group-element-grid tb-group-element-list tb-group-element-overlay'
            ),
            array(
                'id' => 'img_width',
                'type' => 'text',
                'label' => __('Image Width', 'builder-infinite-posts'),
                'class' => 'xsmall',
                'wrap_with_class' => 'tb-group-element tb-group-element-list tb-group-element-grid tb-group-element-overlay',
            ),
            array(
                'id' => 'img_height',
                'type' => 'text',
                'label' => __('Image Height', 'builder-infinite-posts'),
                'class' => 'xsmall',
                'wrap_with_class' => 'tb-group-element tb-group-element-list tb-group-element-grid tb-group-element-overlay',
            ),
            array(
                'id' => 'row_height',
                'type' => 'select',
                'label' => __('Post Height', 'builder-infinite-posts'),
                'options' => array(
                    'height-default' => __('Default', 'builder-infinite-posts'),
                    'fullheight' => __('Fullheight', 'builder-infinite-posts'),
                ),
                'wrap_with_class' => 'tb-group-element tb-group-element-parallax'
            ),
            array(
                'id' => 'background_style',
                'type' => 'select',
                'label' => __('Background Style', 'builder-infinite-posts'),
                'options' => array(
                    'builder-parallax-scrolling' => __('Parallax Scrolling', 'builder-infinite-posts'),
                    'fullcover' => __('Full Cover', 'builder-infinite-posts'),
                ),
                'wrap_with_class' => 'tb-group-element tb-group-element-parallax'
            ),
            array(
                'id' => 'overlay_color',
                'type' => 'text',
                'colorpicker' => true,
                'label' => __('Overlay Color', 'builder-infinite-posts'),
                'class' => 'small',
                'wrap_with_class' => 'tb-group-element tb-group-element-parallax',
            ),
            array(
                'id' => 'masonry',
                'type' => 'select',
                'label' => __('Masonry Layout', 'builder-infinite-posts'),
                'options' => array(
                    'enabled' => __('Enabled', 'builder-infinite-posts'),
                    'disabled' => __('Disabled', 'builder-infinite-posts'),
                ),
                'wrap_with_class' => 'tb-group-element tb-group-element-grid tb-group-element-overlay'
            ),
            array(
                'id' => 'gutter',
                'type' => 'select',
                'label' => __('Gutter Spacing', 'builder-infinite-posts'),
                'options' => array(
                    'default' => __('Default', 'builder-infinite-posts'),
                    'narrow' => __('Narrow', 'builder-infinite-posts'),
                    'none' => __('None', 'builder-infinite-posts'),
                ),
                'wrap_with_class' => 'tb-group-element tb-group-element-grid tb-group-element-overlay'
            ),
            array(
                'id' => 'pagination',
                'type' => 'select',
                'label' => __('Pagination', 'builder-infinite-posts'),
                'options' => array(
                    'infinite-scroll' => __('Infinite Scroll', 'builder-infinite-posts'),
                    'links' => __('Pagination Links', 'builder-infinite-posts'),
                    'load-more' => __('Load More Button', 'builder-infinite-posts'),
                    'disabled' => __('No Pagination', 'builder-infinite-posts'),
                )
            ),
            array(
                'type' => 'separator',
                'meta' => array('html' => '<hr />')
            ),
            array(
                'id' => 'display_content',
                'type' => 'select',
                'label' => __('Display', 'builder-infinite-posts'),
                'options' => array(
                    'excerpt' => __('Excerpt', 'builder-infinite-posts'),
                    'content' => __('Content', 'builder-infinite-posts'),
                    'none' => __('None', 'builder-infinite-posts'),
                )
            ),
            array(
                'id' => 'unlink_image',
                'type' => 'select',
                'label' => __('Unlink Featured Image', 'builder-infinite-posts'),
                'options' => array(
                    'yes' => __('Yes', 'builder-infinite-posts'),
                    'no' => __('No', 'builder-infinite-posts'),
                ),
                'wrap_with_class' => 'tb-group-element tb-group-element-grid tb-group-element-list'
            ),
            array(
                'id' => 'hide_post_title',
                'type' => 'select',
                'label' => __('Hide Post Title', 'builder-infinite-posts'),
                'options' => array(
                    'no' => __('No', 'builder-infinite-posts'),
                    'yes' => __('Yes', 'builder-infinite-posts'),
                )
            ),
            array(
                'id' => 'unlink_post_title',
                'type' => 'select',
                'label' => __('Unlink Post Title', 'builder-infinite-posts'),
                'options' => array(
                    'no' => __('No', 'builder-infinite-posts'),
                    'yes' => __('Yes', 'builder-infinite-posts'),
                )
            ),
            array(
                'id' => 'hide_post_date',
                'type' => 'select',
                'label' => __('Hide Post Date', 'builder-infinite-posts'),
                'options' => array(
                    'yes' => __('Yes', 'builder-infinite-posts'),
                    'no' => __('No', 'builder-infinite-posts'),
                )
            ),
            array(
                'id' => 'hide_post_meta',
                'type' => 'select',
                'label' => __('Hide Post Meta', 'builder-infinite-posts'),
                'options' => array(
                    'yes' => __('Yes', 'builder-infinite-posts'),
                    'no' => __('No', 'builder-infinite-posts'),
                )
            ),
            array(
                'type' => 'separator',
                'meta' => array('html' => '<hr /><h4>' . __('Read More Button', 'builder-infinite-posts') . '</h4>')
            ),
            array(
                'id' => 'hide_read_more_button',
                'type' => 'select',
                'label' => __('Hide Read More Button', 'builder-infinite-posts'),
                'options' => array(
                    'no' => __('No', 'builder-infinite-posts'),
                    'yes' => __('Yes', 'builder-infinite-posts'),
                )
            ),
            array(
                'id' => 'read_more_text',
                'type' => 'text',
                'label' => __('Button Text', 'builder-infinite-posts'),
                'class' => '',
                'value' => __('Read More', 'builder-infinite-posts'),
            ),
            array(
                'id' => 'permalink',
                'type' => 'select',
                'label' => __('Open Link In', 'builder-infinite-posts'),
                'options' => array(
                    'default' => __('Same Window', 'builder-infinite-posts'),
                    'lightboxed' => __('Lightbox', 'builder-infinite-posts'),
                    'newwindow' => __('New Window', 'builder-infinite-posts'),
                )
            ),
            array(
                'id' => 'buttons_style',
                'type' => 'radio',
                'label' => __('Button Style', 'builder-infinite-posts'),
                'options' => array(
                    'colored' => __('Colored', 'builder-infinite-posts'),
                    'outline' => __('Outlined', 'builder-infinite-posts'),
                ),
                'default' => 'colored'
            ),
            array(
                'id' => 'color_button',
                'type' => 'layout',
                'mode' => 'sprite',
                'class' => 'tb-colors',
                'label' => __('Button Color', 'builder-infinite-posts'),
                'options' => $colors
            ),
            array(
                'id' => 'read_more_size',
                'type' => 'radio',
                'label' => __('Button Size', 'builder-infinite-posts'),
                'options' => array(
                    'small' => __('Small', 'builder-infinite-posts'),
                    'normal' => __('Normal', 'builder-infinite-posts'),
                    'large' => __('Large', 'builder-infinite-posts'),
                    'xlarge' => __('xLarge', 'builder-infinite-posts'),
                )
            ),
            // Additional CSS
            array(
                'type' => 'separator',
                'meta' => array('html' => '<hr/>')
            ),
            array(
                'id' => 'css_class_contact',
                'type' => 'text',
                'label' => __('Additional CSS Class', 'builder-infinite-posts'),
                'class' => 'large exclude-from-reset-field',
                'description' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-infinite-posts')),
            )
        );

        return $options;
    }

    public function get_default_settings() {
        return array(
            'post_per_page_post' => 3,
            'overlay_color' => '000000_0.30'
        );
    }

    public function get_styling() {
        $general = array(
            //bacground
            self::get_seperator('image_bacground', __('Background', 'themify'), false),
            self::get_color('.module-infinite-posts', 'background_color', __('Background Color', 'themify'), 'background-color'),
            // Font
            self::get_seperator('font', __('Font', 'themify')),
            self::get_font_family('.module-infinite-posts'),
            self::get_color('.module .infinite-post-inner', 'font_color', __('Font Color', 'themify')),
            self::get_font_size('.module-infinite-posts'),
            self::get_line_height('.module-infinite-posts'),
            self::get_text_align('.module-infinite-posts'),
            // Link
            self::get_seperator('link', __('Link', 'themify')),
            self::get_color('.module a:not(.builder_button)', 'link_color'),
            self::get_text_decoration(' .module a:not(.builder_button)'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-infinite-posts'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-infinite-posts'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-infinite-posts')
        );

        $post_title = array(
            self::get_seperator('font', __('Font', 'themify'), false),
            self::get_font_family('.module-infinite-posts .post-title', 'font_family_post_title'),
            self::get_color(array('.module-infinite-posts .post-title', '.module-infinite-posts .post-title a'), 'font_color_post_title', __('Font Color', 'themify')),
            self::get_font_size('.module-infinite-posts .post-title', 'font_size_post_title'),
            self::get_line_height('.module-infinite-posts .post-title', 'line_height_post_title')
        );

        $post_date = array(
            self::get_seperator('font', __('Font', 'themify'), false),
            self::get_font_family('.module-infinite-posts .post-date', 'font_family_post_date'),
            self::get_color('.module-infinite-posts .post-date', 'font_color_post_date', __('Font Color', 'themify')),
            self::get_font_size('.module-infinite-posts .post-date', 'font_size_post_date'),
            self::get_line_height('.module-infinite-posts .post-date', 'line_height_post_date')
        );

        $post_meta = array(
            self::get_seperator('font', __('Font', 'themify'), false),
            self::get_font_family('.module-infinite-posts .post-meta', 'font_family_post_meta'),
            self::get_color(array('.module-infinite-posts .post-meta', '.module-infinite-posts .post-meta a'), 'font_color_post_meta', __('Font Color', 'themify')),
            self::get_font_size('.module-infinite-posts .post-meta', 'font_size_post_meta'),
            self::get_line_height('.module-infinite-posts .post-meta', 'line_height_post_meta')
        );

        $post_content = array(
            self::get_seperator('font', __('Font', 'themify'), false),
            self::get_font_family('.module-infinite-posts .bip-post-content', 'font_family_post_content'),
            self::get_color('.module-infinite-posts .bip-post-content', 'font_color_post_content', __('Font Color', 'themify')),
            self::get_font_size('.module-infinite-posts .bip-post-content', 'font_size_post_content'),
            self::get_line_height('.module-infinite-posts .bip-post-content', 'line_height_post_content')
        );

        $read_more_button = array(
            self::get_seperator('font', __('Font', 'themify'), false),
            self::get_font_family('.module-infinite-posts a.read-more-button', 'font_family_read_more'),
            self::get_color('.module-infinite-posts a.read-more-button', 'background_color_read_more', __('Background Color', 'themify'), 'background-color'),
            self::get_color('.module-infinite-posts a.read-more-button', 'font_color_read_more'),
            self::get_font_size('.module-infinite-posts a.read-more-button', 'font_size_read_more'),
            self::get_text_align('.module-infinite-posts .read-more-button-wrap', 'text_align_read_more')
        );

        return array(
            array(
                'type' => 'tabs',
                'id' => 'module-styling',
                'tabs' => array(
                    'general' => array(
                        'label' => __('General', 'builder-infinite-posts'),
                        'fields' => $general
                    ),
                    'title' => array(
                        'label' => __('Post Title', 'builder-infinite-posts'),
                        'fields' => $post_title
                    ),
                    'meta' => array(
                        'label' => __('Post Meta', 'builder-infinite-posts'),
                        'fields' => $post_meta
                    ),
                    'date' => array(
                        'label' => __('Post Date', 'builder-infinite-posts'),
                        'fields' => $post_date
                    ),
                    'content' => array(
                        'label' => __('Post Content', 'builder-infinite-posts'),
                        'fields' => $post_content
                    ),
                    'read_more_button' => array(
                        'label' => __('Read More Button', 'builder-infinite-posts'),
                        'fields' => $read_more_button
                    )
                )
            ),
        );
    }

    public function get_visual_type() {
        return 'ajax';
    }

    public static function get_pagenav($before = '', $after = '', $query = false, $original_offset = 0) {
        global $wp_query;

        if (false === $query) {
            $query = $wp_query;
        }

        $request = $query->request;
        $posts_per_page = intval(get_query_var('posts_per_page'));
        $paged = !empty($_GET['tb_infinite']) ? (int) $_GET['tb_infinite'] : 1;
        $numposts = $query->found_posts;

        // $query->found_posts does not take offset into account, we need to manually adjust that
        if ((int) $original_offset) {
            $numposts = $numposts - (int) $original_offset;
        }

        $max_page = ceil($numposts / $query->query_vars['posts_per_page']);
        $out = '';
        $pages_to_show = apply_filters('themify_filter_pages_to_show', 5);
        $pages_to_show_minus_1 = $pages_to_show - 1;
        $half_page_start = floor($pages_to_show_minus_1 / 2);
        $half_page_end = ceil($pages_to_show_minus_1 / 2);
        $start_page = $paged - $half_page_start;
        if ($start_page <= 0) {
            $start_page = 1;
        }
        $end_page = $paged + $half_page_end;
        if (($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if ($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if ($start_page <= 0) {
            $start_page = 1;
        }

        if ($max_page > 1) {
            $out .= $before . '<div class="pagenav clearfix">';
			for ($i = $start_page; $i <= $end_page; $i++) {
				if ( (int) $i === (int) $paged) {
                    $out .= ' <span class="number current">' . $i . '</span> ';
                } else {
                    $out .= ' <a href="' . esc_url(add_query_arg(array('tb_infinite' => $i))) . '" class="number">' . $i . '</a> ';
                }
            }
            $out .= '</div>' . $after;
        }

        return $out;
    }

}

Themify_Builder_Model::register_module('TB_Infinite_Posts_Module');
