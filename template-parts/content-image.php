<?php
/**
 * Listing entry — image format.
 *
 * If a featured image is set, it's the author's chosen representative image, so
 * we show it as a hero (top of card, like other featured posts). Otherwise we
 * pull the first image from the content and show it at its natural aspect ratio
 * under the title (don't crop content that wasn't chosen as a hero).
 *
 * @package Yesterday
 */

$yd_has_featured = has_post_thumbnail();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>

	<?php if ( $yd_has_featured ) : ?>
		<a class="post-thumb post-thumb-wrap" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'large' ); ?>
			<?php yesterday_format_badge(); ?>
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

		<?php
		if ( ! $yd_has_featured ) :
			$yd_image = yesterday_first_content_image();
			if ( $yd_image ) :
				?>
				<figure class="post-figure">
					<a href="<?php the_permalink(); ?>"><?php echo $yd_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from filtered post content. ?></a>
					<?php yesterday_format_badge(); ?>
				</figure>
				<?php
			endif;
		endif;
		?>

		<div class="entry-summary"><?php the_excerpt(); ?></div>

		<a class="read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View photo', 'yesterday' ); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
	</div>
</article>
