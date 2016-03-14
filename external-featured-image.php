<?php
/*
Plugin Name: WP External Featured image
Description: Allow the use of external image as featured image
Author: Yehuda Hassine
Version: 1.0
*/

add_action('add_meta_boxes', 'external_thumbnail_url_mb');
function external_thumbnail_url_mb() {
	add_meta_box( 'External thumbnail url', 'External thumbnail url', 'thumbnail_url_mb', 'post', 'side' );
}

function thumbnail_url_mb( $post ) { 
	$external_url = get_post_meta( $post->ID , 'external_thumbnail_url', true); ?>
	<p>
		<?php wp_nonce_field( 'thumbnail_external_none', 'meta_box_nonce' ); ?>
		<input type="url" name="thumbnail_external_url" value="<?php echo esc_url( $external_url ); ?>" />
	</p>
<?php
}

function save_meta_box( $post_id ) {
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'thumbnail_external_none' ) ) return;

	$thumbnail_external_url = esc_url( $_POST['thumbnail_external_url'] );

	update_post_meta( $post_id, 'external_thumbnail_url', $thumbnail_external_url );

}
add_action( 'save_post', 'save_meta_box' );

function post_thumbnail_urls($image, $attachment_id, $size, $icon) {
	global $post;

	$external_url = get_post_meta( $post->ID, 'external_thumbnail_url', true);

	if ( filter_var( $external_url, FILTER_VALIDATE_URL ) )
		$image[0] = $external_url;

	return $image;
}
add_filter('wp_get_attachment_image_src', 'post_thumbnail_urls', 10, 4);
?>
