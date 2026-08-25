<?php
/**
 * Listing entry — chat format. Shows only the first few lines of the
 * conversation, then an ellipsis and a read-more, so it's clear the full
 * transcript (and the comments) live on the single post. Title-less; the date
 * is linked too.
 *
 * @package Yesterday
 */

$yd_full     = apply_filters( 'the_content', get_the_content() );
preg_match_all( '/<p\b[^>]*>.*?<\/p>/is', $yd_full, $yd_matches );
$yd_lines    = $yd_matches[0];
$yd_preview  = array_slice( $yd_lines, 0, 3 );
$yd_has_more = count( $yd_lines ) > count( $yd_preview );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card format-chat' ); ?>>
	<div class="pad">
		<div class="entry-meta">
			<?php yesterday_sticky_badge(); ?>
			<span><i class="bi bi-chat-dots" aria-hidden="true"></i> <?php esc_html_e( 'Chat', 'yesterday' ); ?></span>
			<span class="sep">&middot;</span>
			<?php yesterday_meta_date( true ); ?>
		</div>
		<div class="entry-content chat-preview">
			<?php
			echo implode( "\n", $yd_preview ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered post content.
			if ( $yd_has_more ) {
				echo '<p class="chat-ellipsis" aria-hidden="true">&hellip;</p>';
			}
			?>
		</div>
		<a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'yesterday' ); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
	</div>
</article>
