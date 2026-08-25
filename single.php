<?php
/**
 * Single post template.
 *
 * Left rail: the table of contents (built from headings by main.js), with the
 * author card revealed as a fallback when there aren't enough headings.
 *
 * @package Yesterday
 */

get_header();

// Set up post data before anything in this template renders — the left-rail
// author card below needs the real post author, and single.php only ever
// has the one queried post, so there is no loop to iterate.
if ( have_posts() ) :
	the_post();
	?>

<div class="container">
	<div class="layout">

		<aside class="sticky left-rail" aria-label="<?php esc_attr_e( 'In this post', 'yesterday' ); ?>">
			<nav class="toc card pad" aria-label="<?php esc_attr_e( 'Table of contents', 'yesterday' ); ?>" hidden>
				<h2 class="toc-title"><?php esc_html_e( 'In this post', 'yesterday' ); ?></h2>
				<ul class="toc-list"></ul>
			</nav>
			<div class="left-rail-fallback sidebar" hidden>
				<?php
				// `.sidebar` gives this the same flex layout + gap the listing
				// pages' sidebar-left.php uses — needed here because
				// dynamic_sidebar() below can render multiple widgets, which
				// otherwise just stack with no space between them.
				//
				// Prefer whatever's configured in the "Left Sidebar" widget area
				// (the same Author Card widget the homepage/listing sidebar uses)
				// so there's one place to manage it; only fall back to an
				// auto-generated card from the post's author when that area is
				// empty.
				if ( is_active_sidebar( 'sidebar-left' ) ) :
					dynamic_sidebar( 'sidebar-left' );
				else :
					get_template_part( 'template-parts/author-card' );
				endif;
				?>
			</div>
		</aside>

		<main id="main" class="single-post">
			<?php
				get_template_part( 'template-parts/content', 'single' );

				// --- Author box (from the post author's profile) ---
				$yd_author_id   = (int) get_the_author_meta( 'ID' );
				$yd_author_name = get_the_author_meta( 'display_name', $yd_author_id );
				$yd_author_bio  = get_the_author_meta( 'description', $yd_author_id );
				$yd_author_url  = get_the_author_meta( 'user_url', $yd_author_id );

				// Graceful fallback so the box never looks empty on a fresh install.
				if ( ! $yd_author_bio ) {
					$yd_author_bio = sprintf(
						/* translators: 1: author name, 2: site name. */
						esc_html__( '%1$s writes for %2$s.', 'yesterday' ),
						$yd_author_name,
						get_bloginfo( 'name' )
					);
				}
				?>
				<section class="card pad author-box" aria-label="<?php esc_attr_e( 'About the author', 'yesterday' ); ?>">
					<?php echo get_avatar( $yd_author_id, 96 ); ?>
					<div>
						<div class="ab-head">
							<h2 class="ab-name"><a href="<?php echo esc_url( get_author_posts_url( $yd_author_id ) ); ?>"><?php echo esc_html( $yd_author_name ); ?></a></h2>
							<?php if ( $yd_author_url ) : ?>
								<a class="ab-link" href="<?php echo esc_url( $yd_author_url ); ?>" rel="nofollow noopener">
									<i class="bi bi-link-45deg" aria-hidden="true"></i> <?php esc_html_e( 'Website', 'yesterday' ); ?>
								</a>
							<?php endif; ?>
						</div>
						<p class="ab-bio"><?php echo esc_html( $yd_author_bio ); ?></p>
					</div>
				</section>
				<?php

				// --- Previous / next post navigation ---
				$yd_prev = get_previous_post();
				$yd_next = get_next_post();
				if ( $yd_prev || $yd_next ) :
					?>
					<nav class="post-nav" aria-label="<?php esc_attr_e( 'Post navigation', 'yesterday' ); ?>">
						<?php
						if ( $yd_prev ) {
							printf(
								'<a class="prev" href="%1$s"><span class="dir"><i class="bi bi-arrow-left" aria-hidden="true"></i> %2$s</span><span class="ptitle">%3$s</span></a>',
								esc_url( get_permalink( $yd_prev ) ),
								esc_html__( 'Previous', 'yesterday' ),
								esc_html( get_the_title( $yd_prev ) )
							);
						}
						if ( $yd_next ) {
							printf(
								'<a class="next" href="%1$s"><span class="dir">%2$s <i class="bi bi-arrow-right" aria-hidden="true"></i></span><span class="ptitle">%3$s</span></a>',
								esc_url( get_permalink( $yd_next ) ),
								esc_html__( 'Next', 'yesterday' ),
								esc_html( get_the_title( $yd_next ) )
							);
						}
						?>
					</nav>
					<?php
				endif;

				// --- Comments --- (comments.php decides what, if anything, to show)
				comments_template();
			?>
		</main>

		<?php get_sidebar(); ?>

	</div>
</div>

	<?php
endif;

get_footer();
