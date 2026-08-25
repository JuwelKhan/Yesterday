<?php
/**
 * Static page content.
 *
 * When the page has a featured image it shows an overlay hero (image background
 * with the title + excerpt on top); otherwise a plain title card. The About /
 * Author page template (template-author.php) provides the alternative portrait
 * hero instead.
 *
 * @package Yesterday
 */
?>
<?php if ( has_post_thumbnail() ) : ?>

	<section class="page-hero">
		<?php the_post_thumbnail( 'large', array( 'alt' => '' ) ); ?>
		<div class="page-hero-inner">
			<h1 class="page-hero-title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="page-hero-meta"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'card pad pad-lg' ); ?>>
		<div class="entry-content">
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page sections', 'yesterday' ) . '">' . esc_html__( 'Pages:', 'yesterday' ) . ' ',
					'after'  => '</nav>',
				)
			);
			?>
		</div>
	</article>

<?php else : ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'card pad pad-lg' ); ?>>

		<header class="single-header has-mb-lg">
			<h1 class="page-title-lg"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page sections', 'yesterday' ) . '">' . esc_html__( 'Pages:', 'yesterday' ) . ' ',
					'after'  => '</nav>',
				)
			);
			?>
		</div>

	</article>

<?php endif; ?>
