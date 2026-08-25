<?php
/**
 * Search results template.
 *
 * @package Yesterday
 */

get_header();
global $wp_query;
$yd_total = (int) $wp_query->found_posts;
?>

<div class="container">
	<div class="layout">

		<?php get_sidebar( 'left' ); ?>

		<main id="main" class="posts">

			<header class="archive-header card pad pad-lg">
				<h1 class="archive-title">
					<span class="archive-prefix"><?php esc_html_e( 'Search results for:', 'yesterday' ); ?></span>
					<span class="search-term">&ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;</span>
				</h1>
				<p class="archive-count">
					<i class="bi bi-search" aria-hidden="true"></i>
					<?php
					printf(
						esc_html( _n( '%s result found', '%s results found', $yd_total, 'yesterday' ) ),
						esc_html( number_format_i18n( $yd_total ) )
					);
					?>
				</p>
				<div class="banner-search">
					<?php get_search_form(); ?>
				</div>
			</header>

			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'search' );
				endwhile;

				yesterday_pagination();
			else :
				?>
				<section class="card no-results" aria-label="<?php esc_attr_e( 'No results', 'yesterday' ); ?>">
					<i class="bi bi-search big" aria-hidden="true"></i>
					<h2><?php esc_html_e( 'No posts found', 'yesterday' ); ?></h2>
					<p><?php esc_html_e( "We couldn't find anything matching your search. Try a different word, or browse by category from the sidebar.", 'yesterday' ); ?></p>
					<?php get_search_form(); ?>
				</section>
				<?php
			endif;
			?>

		</main>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php
get_footer();
