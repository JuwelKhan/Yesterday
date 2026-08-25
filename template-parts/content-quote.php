<?php
/**
 * Listing entry — quote format. A dark card where the post content (the quote)
 * is the whole card. If the author didn't use a blockquote, we wrap the content
 * in one so the format styling applies.
 *
 * @package Yesterday
 */

$yd_rendered = apply_filters( 'the_content', get_the_content() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card format-quote' ); ?>>
	<div class="pad pad-lg">
		<div class="entry-meta">
			<?php yesterday_sticky_badge(); ?>
			<span><i class="bi bi-quote" aria-hidden="true"></i> <?php esc_html_e( 'Quote', 'yesterday' ); ?></span>
			<span class="sep">&middot;</span>
			<?php yesterday_meta_date( true ); ?>
		</div>
		<?php
		if ( false !== strpos( $yd_rendered, '<blockquote' ) ) {
			echo $yd_rendered; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered post content.
		} else {
			echo '<blockquote>' . $yd_rendered . '</blockquote>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered post content.
		}
		?>
	</div>
</article>
