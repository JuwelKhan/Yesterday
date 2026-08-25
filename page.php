<?php
/**
 * Static page template.
 *
 * Left rail holds the table of contents (built by main.js); when a page has too
 * few headings the rail collapses. The right sidebar is kept for consistency
 * with the design.
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

				get_template_part( 'template-parts/content', 'page' );

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
