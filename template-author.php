<?php
/**
 * Template Name: About / Author
 *
 * A page template with a portrait "author hero" (featured image + title + intro +
 * social links) above the page content. Selectable from the Page "Template"
 * dropdown. Everything is dynamic:
 *   - portrait  -> the page's featured image
 *   - name      -> the page title
 *   - intro     -> the page excerpt
 *   - social    -> the Social Links Menu (hidden if none assigned)
 *
 * Generic pages instead use page.php (featured-image overlay hero). Keeps the
 * page layout's left TOC rail + right sidebar.
 *
 * @package Yesterday
 */

get_header();
?>

<div class="container">
	<div class="layout layout-page has-rail">

		<aside class="sticky left-rail" aria-label="<?php esc_attr_e( 'In this page', 'yesterday' ); ?>">
			<nav class="toc card pad" aria-label="<?php esc_attr_e( 'Table of contents', 'yesterday' ); ?>" hidden>
				<h2 class="toc-title"><?php esc_html_e( 'In this page', 'yesterday' ); ?></h2>
				<ul class="toc-list"></ul>
			</nav>
		</aside>

		<main id="main" class="posts">
			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<section class="card pad pad-lg about-hero-card">
					<div class="about-hero">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
						<?php endif; ?>
						<div class="about-lead">
							<h1><?php the_title(); ?></h1>
							<?php if ( has_excerpt() ) : ?>
								<p><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>
							<?php yesterday_social_menu( 'about-meta' ); ?>
						</div>
					</div>
				</section>

				<article <?php post_class( 'card pad pad-lg' ); ?>>
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

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

			endwhile;
			?>
		</main>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php
get_footer();
