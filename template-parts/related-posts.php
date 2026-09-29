<?php
/**
 * Related posts — shown after the author box on single posts, if the
 * Customizer's "Show related posts" toggle is on (default) and at least one
 * related post is actually found (see yesterday_get_related_posts()). Never
 * renders an empty section or falls back to unrelated "recent posts" filler.
 *
 * @package Yesterday
 */

if ( ! get_theme_mod( 'yesterday_show_related_posts', true ) ) {
	return;
}

$yd_related = yesterday_get_related_posts( 3 );
if ( empty( $yd_related ) ) {
	return;
}
?>
<section class="card pad related-posts" aria-label="<?php esc_attr_e( 'Related posts', 'yesterday' ); ?>">
	<h2 class="related-posts-title"><?php esc_html_e( 'You might also like', 'yesterday' ); ?></h2>
	<div class="related-posts-grid">
		<?php foreach ( $yd_related as $yd_post ) : ?>
			<a class="related-post card" href="<?php echo esc_url( get_permalink( $yd_post ) ); ?>">
				<?php if ( has_post_thumbnail( $yd_post ) ) : ?>
					<div class="related-post-thumb"><?php echo get_the_post_thumbnail( $yd_post, 'medium' ); ?></div>
				<?php endif; ?>
				<div class="related-post-body">
					<span class="related-post-date"><?php echo esc_html( get_the_date( '', $yd_post ) ); ?></span>
					<h3 class="related-post-title"><?php echo esc_html( get_the_title( $yd_post ) ); ?></h3>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</section>
