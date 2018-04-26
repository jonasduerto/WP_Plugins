<?php
/**
 * Editor field template
 *
 * @var string $type
 * @var array $args
 * @var array $data
 * @var array $meta_data
 * @var array $lang
 * @var boolean $is_single single page
 * @var string $index index in themplate
 *
 * @package Themify PTB
 */
?>

<?php
PTB_Public::$render_content = true;
?>
<div class="ptb_entry_content" itemprop="articleBody">
	<?php the_content(); ?>
</div>
<?php
PTB_Public::$render_content = false;
