<?php
/**
 * Custom Image field template
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

<?php if (!empty($data['image'])): ?>
	<?php
	$url = PTB_CMB_Base::ptb_resize($data['image'], $data['width'], $data['height']);
	?>
	<figure class="ptb_post_image clearfix">
		<?php
		if (!empty($data['link'])): echo '<a href="' . $data['link'] . '">';
		endif;
		?>
		<img src="<?php echo $url ?>" />
		<?php
		if (!empty($data['link'])): echo '</a>';
		endif;
		?>
	</figure>
<?php endif; ?>
