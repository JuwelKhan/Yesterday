<?php
/**
 * Listing entry — link format. A light card whose title points to the first URL
 * found in the content (falling back to the post permalink).
 *
 * @package Yesterday
 */

$yd_link     = get_url_in_content( get_the_content() );
$yd_external = (bool) $yd_link;
if ( ! $yd_link ) {
	$yd_link = get_permalink();
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card format-link' ); ?>>
	<div class="pad pad-lg">
		<div class="entry-meta">
			<?php yesterday_sticky_badge(); ?>
			<span><i class="bi bi-link-45deg" aria-hidden="true"></i> <?php esc_html_e( 'Link', 'yesterday' ); ?></span>
			<span class="sep">&middot;</span>
			<?php yesterday_meta_date( true ); // Title links out to the URL, so the date is the way into the post + comments. ?>
		</div>
		<h2 class="entry-title small">
			<a href="<?php echo esc_url( $yd_link ); ?>"<?php echo $yd_external ? ' rel="noopener noreferrer"' : ' rel="bookmark"'; ?>>
				<?php the_title(); ?> <i class="bi bi-box-arrow-up-right icon-inline" aria-hidden="true"></i>
			</a>
		</h2>
		<div class="entry-summary"><?php the_excerpt(); ?></div>
	</div>
</article>
