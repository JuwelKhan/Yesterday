<?php
/**
 * Main template — the blog posts index (and the fallback for any view without
 * a more specific template).
 *
 * @package Yesterday
 */

get_header();
?>

<div class="container">
	<div class="layout">

		<?php get_sidebar( 'left' ); ?>

		<main id="main" class="posts">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;

				yesterday_pagination();
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif;
			?>
		</main>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php
get_footer();
