<?php
/**
 * Listing entry — video format. Embeds the first video/iframe found in the
 * content; falls back to the featured image.
 *
 * @package Yesterday
 */

$yd_media = get_media_embedded_in_content(
	apply_filters( 'the_content', get_the_content() ),
	array( 'video', 'iframe', 'object', 'embed' )
);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>

	<?php if ( ! empty( $yd_media ) ) : ?>
		<div class="ratio"><?php echo $yd_media[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- embed markup from filtered content. ?></div>
	<?php elseif ( has_post_thumbnail() ) : ?>
		<a class="post-thumb post-thumb-wrap" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'large' ); ?>
			<?php yesterday_format_badge(); ?>
		</a>
	<?php endif; ?>

	<div class="pad pad-lg">
		<div class="entry-meta">
			<?php yesterday_sticky_badge(); ?>
			<span><i class="bi bi-play-btn" aria-hidden="true"></i> <?php esc_html_e( 'Video', 'yesterday' ); ?></span>
			<span class="sep">&middot;</span>
			<span><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
		</div>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<div class="entry-summary"><?php the_excerpt(); ?></div>
	</div>

</article>
