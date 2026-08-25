<?php
/**
 * Single post content: featured image, header/meta, the content, tags, share.
 * The author box, post navigation and comments are added by single.php.
 *
 * @package Yesterday
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumb post-thumb-wrap"><?php the_post_thumbnail( 'large' ); ?></div>
	<?php endif; ?>

	<div class="pad pad-lg">

		<header class="single-header">
			<div class="entry-meta">
					<?php
					// For a post with a format (status, quote, etc.), name it first —
					// before the date — so the single post states its type.
					if ( get_post_format() ) {
						list( $yd_fmt_icon, $yd_fmt_label ) = yesterday_format_meta();
						printf(
							'<span><i class="bi %1$s" aria-hidden="true"></i> %2$s</span> <span class="sep">&middot;</span> ',
							esc_attr( $yd_fmt_icon ),
							esc_html( $yd_fmt_label )
						);
					}
					?>
				<?php
					// Author byline — the only author attribution at the top of the
					// post. After the format label, before the date, so the meta
					// reads "[Type ·] By Author · Date · …" (text-only, like the
					// date/category links).
					echo '<span>';
					printf(
						/* translators: %s: post author name, linked to their archive. */
						esc_html__( 'By %s', 'yesterday' ),
						'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . esc_html( get_the_author() ) . '</a>'
					);
					echo '</span> <span class="sep">&middot;</span> ';
					?>
					<span><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
				<?php
				yesterday_meta_category();
				if ( comments_open() || get_comments_number() ) {
					echo '<span class="sep">&middot;</span> <span>';
					comments_number( esc_html__( 'No comments', 'yesterday' ), esc_html__( '1 comment', 'yesterday' ), esc_html__( '% comments', 'yesterday' ) );
					echo '</span>';
				}
				?>
			</div>
			<h1 class="single-title"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Post pages', 'yesterday' ) . '">' . esc_html__( 'Pages:', 'yesterday' ) . ' ',
					'after'  => '</nav>',
				)
			);
			?>
		</div>

		<?php
		$yd_tags = get_the_tags();
		if ( ! empty( $yd_tags ) ) :
			?>
			<div class="entry-tags">
				<span class="label"><?php esc_html_e( 'Tagged:', 'yesterday' ); ?></span>
				<div class="tags-items">
					<?php foreach ( $yd_tags as $yd_tag ) : ?>
						<a href="<?php echo esc_url( get_tag_link( $yd_tag ) ); ?>" class="tag"><?php echo esc_html( $yd_tag->name ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		$yd_share_url   = rawurlencode( get_permalink() );
		$yd_share_title = rawurlencode( get_the_title() );
		?>
		<div class="entry-share">
			<span class="label"><?php esc_html_e( 'Share:', 'yesterday' ); ?></span>
			<div class="share-items">
				<a class="share-btn" data-tooltip="<?php esc_attr_e( 'Share on X', 'yesterday' ); ?>" href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $yd_share_url ); ?>&amp;text=<?php echo esc_attr( $yd_share_title ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on X', 'yesterday' ); ?>"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
				<a class="share-btn" data-tooltip="<?php esc_attr_e( 'Share on Facebook', 'yesterday' ); ?>" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $yd_share_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Facebook', 'yesterday' ); ?>"><i class="bi bi-facebook" aria-hidden="true"></i></a>
				<a class="share-btn" data-tooltip="<?php esc_attr_e( 'Share on LinkedIn', 'yesterday' ); ?>" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr( $yd_share_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'yesterday' ); ?>"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
				<a class="share-btn" data-tooltip="<?php esc_attr_e( 'Share by email', 'yesterday' ); ?>" href="mailto:?subject=<?php echo esc_attr( $yd_share_title ); ?>&amp;body=<?php echo esc_attr( $yd_share_url ); ?>" aria-label="<?php esc_attr_e( 'Share by email', 'yesterday' ); ?>"><i class="bi bi-envelope" aria-hidden="true"></i></a>
				<button class="share-btn" data-tooltip="<?php esc_attr_e( 'Copy link', 'yesterday' ); ?>" type="button" aria-label="<?php esc_attr_e( 'Copy link', 'yesterday' ); ?>" data-copy-url="<?php echo esc_url( get_permalink() ); ?>"><i class="bi bi-link-45deg" aria-hidden="true"></i></button>
			</div>
		</div>

	</div>
</article>
