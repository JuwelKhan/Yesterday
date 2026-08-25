<?php
/**
 * Listing entry — audio format. Shows the post's audio player under a short
 * summary.
 *
 * @package Yesterday
 */

$yd_audio = get_media_embedded_in_content(
	apply_filters( 'the_content', get_the_content() ),
	array( 'audio', 'iframe' )
);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<div class="pad pad-lg">
		<div class="entry-meta">
			<?php yesterday_sticky_badge(); ?>
			<span><i class="bi bi-music-note-beamed" aria-hidden="true"></i> <?php esc_html_e( 'Audio', 'yesterday' ); ?></span>
			<span class="sep">&middot;</span>
			<span><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
		</div>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<div class="entry-summary has-mb-sm"><?php the_excerpt(); ?></div>
		<?php
		if ( ! empty( $yd_audio ) ) {
			echo $yd_audio[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- embed markup from filtered content.
		}
		?>
	</div>
</article>
