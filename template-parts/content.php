<?php
/**
 * Listing entry — standard format (and the fallback for any format without its
 * own content-{format}.php).
 *
 * @package Yesterday
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-thumb post-thumb-wrap" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'large' ); ?>
			<?php yesterday_format_badge(); ?>
		</a>
	<?php endif; ?>

	<div class="pad pad-lg">
		<div class="entry-meta">
			<?php
			yesterday_sticky_badge();
			// No title (e.g. an untitled standard post) → link the date so the
			// post and its comments are still reachable; otherwise the title is
			// the link and the date stays plain.
			yesterday_meta_date( '' === get_the_title() );
			yesterday_meta_category();
			?>
		</div>

		<?php if ( '' !== get_the_title() ) : ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php endif; ?>

		<div class="entry-summary"><?php the_excerpt(); ?></div>

		<a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'yesterday' ); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
	</div>

</article>
