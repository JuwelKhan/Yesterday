<?php
/**
 * Author card — the post author's profile, used as the single-post left-rail
 * fallback (see single.php) when the "Left Sidebar" widget area has no
 * widgets assigned. Driven entirely by the current post's author, with a
 * graceful bio fallback.
 *
 * @package Yesterday
 */

$yd_author_id = (int) get_the_author_meta( 'ID' );
$yd_name      = get_the_author_meta( 'display_name', $yd_author_id );
$yd_bio       = get_the_author_meta( 'description', $yd_author_id );
$yd_url       = get_the_author_meta( 'user_url', $yd_author_id );
$yd_avatar    = get_avatar_url( $yd_author_id, array( 'size' => 300 ) );

if ( ! $yd_bio ) {
	$yd_bio = sprintf(
		/* translators: 1: author name, 2: site name. */
		esc_html__( '%1$s writes for %2$s.', 'yesterday' ),
		$yd_name,
		get_bloginfo( 'name' )
	);
}
?>
<section class="author-card card pad">
	<?php if ( $yd_avatar ) : ?>
		<img class="author-avatar" src="<?php echo esc_url( $yd_avatar ); ?>" alt="<?php echo esc_attr( $yd_name ); ?>">
	<?php endif; ?>
	<h2 class="author-name"><?php echo esc_html( $yd_name ); ?></h2>
	<p class="author-bio"><?php echo esc_html( $yd_bio ); ?></p>
	<?php if ( $yd_url ) : ?>
		<a class="author-contact" href="<?php echo esc_url( $yd_url ); ?>" rel="nofollow noopener">
			<i class="bi bi-link-45deg" aria-hidden="true"></i> <?php esc_html_e( 'Website', 'yesterday' ); ?>
		</a>
	<?php endif; ?>
</section>
