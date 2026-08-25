<?php
/**
 * Listing entry — gallery format. Shows up to four images from the post's
 * gallery (block or [gallery] shortcode), falling back to attached images, then
 * the featured image.
 *
 * @package Yesterday
 */

$yd_images = array();

$yd_gallery = get_post_gallery( get_the_ID(), false );
if ( ! empty( $yd_gallery['ids'] ) ) {
	$yd_images = array_filter( array_map( 'absint', explode( ',', $yd_gallery['ids'] ) ) );
}

if ( empty( $yd_images ) ) {
	$yd_attached = get_attached_media( 'image', get_the_ID() );
	$yd_images   = array_keys( $yd_attached );
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>

	<?php if ( ! empty( $yd_images ) ) : ?>
		<div class="gallery-grid">
			<?php
			foreach ( array_slice( $yd_images, 0, 4 ) as $yd_img_id ) {
				echo wp_get_attachment_image( $yd_img_id, 'medium' );
			}
			?>
		</div>
	<?php elseif ( has_post_thumbnail() ) : ?>
		<a class="post-thumb post-thumb-wrap" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'large' ); ?>
		</a>
	<?php endif; ?>

	<div class="pad pad-lg">
		<div class="entry-meta">
			<?php
			yesterday_sticky_badge();
			yesterday_meta_date();
			yesterday_meta_category();
			?>
		</div>

		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

		<div class="entry-summary"><?php the_excerpt(); ?></div>

		<a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'See gallery', 'yesterday' ); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
	</div>

</article>
