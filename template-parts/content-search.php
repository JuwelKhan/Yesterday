<?php
/**
 * A single search result row.
 *
 * @package Yesterday
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card result' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a class="r-thumb-link" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'thumbnail', array( 'class' => 'r-thumb' ) ); ?></a>
	<?php endif; ?>

	<div class="r-body">
		<div class="entry-meta">
			<span><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
			<?php yesterday_meta_category(); ?>
		</div>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<p class="r-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '&hellip;' ) ); ?></p>
	</div>

</article>
