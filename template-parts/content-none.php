<?php
/**
 * Shown when a listing query (index/archive) returns no posts.
 *
 * @package Yesterday
 */
?>
<section class="card no-results" aria-label="<?php esc_attr_e( 'Nothing found', 'yesterday' ); ?>">
	<i class="bi bi-journal-text big" aria-hidden="true"></i>
	<h2><?php esc_html_e( 'Nothing here yet', 'yesterday' ); ?></h2>
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		<p>
			<?php
			printf(
				/* translators: %s: URL to the new-post screen. */
				wp_kses( __( 'Ready to publish your first post? <a href="%s">Get started here.</a>', 'yesterday' ), array( 'a' => array( 'href' => array() ) ) ),
				esc_url( admin_url( 'post-new.php' ) )
			);
			?>
		</p>
	<?php else : ?>
		<p><?php esc_html_e( 'There are no posts to show right now. Please check back soon.', 'yesterday' ); ?></p>
	<?php endif; ?>
</section>
