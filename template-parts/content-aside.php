<?php
/**
 * Listing entry — aside format. A short, title-less note (tighter padding).
 *
 * @package Yesterday
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<div class="pad">
		<div class="entry-meta">
			<?php yesterday_sticky_badge(); ?>
			<span><i class="bi bi-chat-left-text" aria-hidden="true"></i> <?php esc_html_e( 'Aside', 'yesterday' ); ?></span>
			<span class="sep">&middot;</span>
			<?php yesterday_meta_date( true ); ?>
		</div>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
		<?php yesterday_read_more_link(); ?>
	</div>
</article>
