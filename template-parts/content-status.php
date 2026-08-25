<?php
/**
 * Listing entry — status format. A short personal update given the quote
 * format's "the post is the card" treatment, but on a light background.
 * Title-less; the excerpt truncates long updates and a read-more (plus the
 * linked date) leads into the single post and its comments.
 *
 * @package Yesterday
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card format-status' ); ?>>
	<div class="pad pad-lg">
		<div class="entry-meta">
			<?php yesterday_sticky_badge(); ?>
			<span><i class="bi bi-chat-square-text" aria-hidden="true"></i> <?php esc_html_e( 'Status', 'yesterday' ); ?></span>
			<span class="sep">&middot;</span>
			<?php yesterday_meta_date( true ); ?>
		</div>
		<div class="entry-summary"><?php the_excerpt(); ?></div>
		<a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'yesterday' ); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
	</div>
</article>
