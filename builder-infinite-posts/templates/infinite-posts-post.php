<?php
/**
 * Template for displaying the loop
 */
$param_image = 'w=' . $fields_args['img_width'] . '&h=' . $fields_args['img_height'] . '&ignore=true';
if (Themify_Builder_Model::is_img_php_disabled() && $fields_args['image_size'] !== '') {
    $param_image .= '&image_size=' . $fields_args['image_size'];
}
$is_comment_open = themify_builder_get('setting-comments_posts');
if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
        ?>

        <div <?php post_class(array('post', $animation_effect)); ?>>

            <div class="infinite-post-cover" style="background-color: <?php echo Themify_Builder_Stylesheet::get_rgba_color($fields_args['overlay_color']); ?>;"></div>
			<?php if( in_array( $fields_args['layout'], array( 'parallax', 'overlay' ) ) || $fields_args['hide_post_image'] !== 'yes' ) : ?>
            <div class="infinite-post-image">
                <?php if (( $fields_args['layout'] === 'list' || $fields_args['layout'] === 'grid' ) && $fields_args['unlink_image'] !== 'yes') : ?>
                    <a href="<?php echo get_permalink(); ?>" <?php if ($fields_args['permalink'] === 'newwindow') echo 'target="_blank"'; ?> class="<?php if ($fields_args['permalink'] === 'lightboxed') echo 'themify_lightbox'; ?>">
                        <?php echo themify_get_image($param_image); ?>
                    </a>
                <?php else : ?>
                    <?php echo themify_get_image($param_image); ?>
                <?php endif; ?>
            </div>
			<?php endif; ?>

            <div class="infinite-post-inner-wrap">
                <div class="infinite-post-inner">

                    <div class="bip-post-text">

                        <?php if ($fields_args['hide_post_date'] !== 'yes') : ?>
                            <time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php the_date(apply_filters('themify_loop_date', '')) ?></time>
                        <?php endif; ?>

                        <?php if ($fields_args['hide_post_title'] !== 'yes') : ?>
                            <h2 class="post-title">
                                <?php if ($fields_args['unlink_post_title'] !== 'yes') : ?>
                                    <a href="<?php echo get_permalink(); ?>" <?php if ($fields_args['permalink'] === 'newwindow') echo 'target="_blank"'; ?> class="<?php if ($fields_args['permalink'] === 'lightboxed') echo 'themify_lightbox'; ?>">
                                        <?php the_title(); ?>
                                    </a>
                                <?php else : ?>
                                    <?php the_title(); ?>
                                <?php endif; ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ($fields_args['hide_post_meta'] !== 'yes') : ?>
                            <p class="post-meta entry-meta">

                                <span class="post-author"><?php echo themify_get_author_link() ?></span>
                                <span class="post-category"><?php the_category(', ') ?></span>
                                <?php the_tags(' <span class="post-tag">', ', ', '</span>'); ?>
                                <?php if (!$is_comment_open && comments_open()) : ?>
                                    <span class="post-comment"><?php comments_popup_link(__('0 Comments', 'builder-infinite-posts'), __('1 Comment', 'builder-infinite-posts'), __('% Comments', 'builder-infinite-posts')); ?></span>
                                <?php endif; //post comment ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($fields_args['display_content'] === 'excerpt') : ?>
                            <div class="bip-post-content">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php elseif ($fields_args['display_content'] === 'content') : ?>
                            <div class="bip-post-content">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($fields_args['hide_read_more_button'] !== 'yes') : ?>
                            <div class="read-more-button-wrap">
                                <a href="<?php echo get_permalink(); ?>" <?php if ($fields_args['permalink'] === 'newwindow') echo 'target="_blank"'; ?> class="read-more-button button-size-<?php echo $fields_args['read_more_size']; ?> <?php echo ( $fields_args['color_button'] !== 'default' ) ? 'ui builder_button ' . $fields_args['color_button'] : ''; ?> <?php if ($fields_args['permalink'] === 'lightboxed') echo 'themify_lightbox'; ?> button-style-<?php echo $fields_args['buttons_style']; ?>">
                                    <?php echo $fields_args['read_more_text']; ?>
                                </a>
                            </div>
                        <?php endif; ?>

                    </div><!-- .bip-post-text -->
                </div><!-- .infinite-post-inner -->

            </div><!-- .infinite-post-inner -->

            <?php
            if ($fields_args['layout'] === 'parallax') {
                echo '<style type="text/css" scoped>
				#' . $module_ID . ' ' . '.post-' . $post->ID . ' { background-image: url(' . wp_get_attachment_url(get_post_thumbnail_id($post->ID)) . '); }
			</style>';
            }
            ?>

        </div><!-- .post -->
<?php
endwhile;
wp_reset_postdata(); endif;