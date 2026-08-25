<?php
/**
 * Archive template: categories, tags, author, date and other term archives.
 *
 * @package Yesterday
 */

get_header();
?>

<div class="container">
	<div class="layout">

		<?php get_sidebar( 'left' ); ?>

		<main id="main" class="posts">

			<header class="archive-header card pad pad-lg">
				<?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
				<?php the_archive_description( '<div class="archive-desc">', '</div>' ); ?>
				<?php
				global $wp_query;
				$yd_total = (int) $wp_query->found_posts;
				?>
				<p class="archive-count">
					<i class="bi bi-collection" aria-hidden="true"></i>
					<?php
					printf(
						esc_html( _n( '%s post', '%s posts', $yd_total, 'yesterday' ) ),
						esc_html( number_format_i18n( $yd_total ) )
					);
					?>
				</p>
			</header>

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
