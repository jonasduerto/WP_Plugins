<?php
/**
 * Image field template
 *
 * @var string $type
 * @var array $args
 * @var array $data
 * @var array $meta_data
 * @var array $lang
 * @var boolean $is_single single page
 *
 * @package Themify PTB
 */
?>

<div class="ptb_image">
<?php
	$url = false;

	if( ! empty( $meta_data[0] ) ) {
		$url = is_numeric( $meta_data[0] )
			? wp_get_attachment_url( $meta_data[0] )
			: esc_url( $meta_data[0] );
	} else if( ! empty( $meta_data[1] ) ) {
		$url = $meta_data[1];
	}

	$url = PTB_CMB_Base::ptb_resize( $url, $data['width'], $data['height'] );

	if( $url ) {
		$link = ! empty( $meta_data[2] ) ? esc_url( $meta_data[2] ) : false;
		$link = ! $link && ! empty( $data['custom_url'] ) ? esc_url( $data['custom_url'] ) : $link;
		$link = ! $link && isset( $data['permalink'] ) ? $meta_data['post_url'] : $link;
		
		$image = sprintf( '<figure class="ptb_post_image clearfix"><img src="%s"></figure>', $url );
		echo $link ? sprintf( '<a href="%s">%s</a>', $link, $image ) : $image;
	}
?>
</div>