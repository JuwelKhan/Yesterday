<?php
/**
 * 404 (not found) template — its own full-width wrapper, no sidebars.
 *
 * @package Yesterday
 */

get_header();
?>

<div class="container error-wrap">
	<main id="main">
		<section class="error-404 card pad pad-lg">

			<p class="error-code">404</p>
			<h1><?php esc_html_e( 'This page wandered off', 'yesterday' ); ?></h1>
			<p><?php esc_html_e( "The page you're looking for isn't here — it may have moved, or never existed. Let's get you back to something readable.", 'yesterday' ); ?></p>

			<div class="error-actions">
				<?php get_search_form(); ?>
				<a class="btn-solid btn-inline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<i class="bi bi-house" aria-hidden="true"></i> <?php esc_html_e( 'Back to home', 'yesterday' ); ?>
				</a>
			</div>

			<?php
			$yd_recent = new WP_Query(
				array(
					'posts_per_page'      => 3,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				)
			);
			if ( $yd_recent->have_posts() ) :
				?>
				<div class="error-suggest">
					<h2><?php esc_html_e( 'Recent posts you might like', 'yesterday' ); ?></h2>
					<ul class="recent">
						<?php
						while ( $yd_recent->have_posts() ) :
							$yd_recent->the_post();
							?>
							<li>
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'thumbnail', array( 'class' => 'thumb' ) );
								}
								?>
								<div>
									<a class="title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									<span class="date"><i class="bi bi-calendar3" aria-hidden="true"></i> <?php echo esc_html( get_the_date() ); ?></span>
								</div>
							</li>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
				</div>
			<?php endif; ?>

		</section>
	</main>
</div>

<?php
get_footer();
