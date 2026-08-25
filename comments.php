<?php
/**
 * Comments area: the list of comments and the comment form.
 *
 * Rendered via comments_template() from single.php / page.php. The individual
 * comment markup is produced by yesterday_comment() in functions.php so it
 * matches the design.
 *
 * @package Yesterday
 */

// Don't load directly, and bail for password-protected posts that aren't unlocked.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( post_password_required() ) {
	return;
}

// Show a "closed" notice for posts (with comments, or any post that has them
// turned off). Bail out entirely when there is nothing to show, so we never
// render an empty comments card (e.g. a page with comments simply off).
$yd_closed_notice = ! comments_open() && ( get_comments_number() > 0 || 'post' === get_post_type() );

if ( ! have_comments() && ! comments_open() && ! $yd_closed_notice ) {
	return;
}
?>

<section class="card pad pad-lg comments-area" aria-label="<?php esc_attr_e( 'Comments', 'yesterday' ); ?>">

	<?php
	// Count human comments and pingbacks/trackbacks separately so they can be
	// shown in distinct sections (real discussion first, link references after).
	$yd_comment_count = (int) get_comments( array( 'post_id' => get_the_ID(), 'type' => 'comment', 'status' => 'approve', 'count' => true ) );
	$yd_ping_count    = (int) get_comments( array( 'post_id' => get_the_ID(), 'type' => 'pings', 'status' => 'approve', 'count' => true ) );
	?>

	<?php if ( $yd_comment_count ) : ?>

		<h2 class="comments-title">
			<?php
			printf(
				esc_html( _n( '%s comment', '%s comments', $yd_comment_count, 'yesterday' ) ),
				esc_html( number_format_i18n( $yd_comment_count ) )
			);
			?>
		</h2>

		<ul class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ul',
					'type'        => 'comment',
					'callback'    => 'yesterday_comment',
					'avatar_size' => 48,
				)
			);
			?>
		</ul>

		<?php
		// Pagination for long comment threads.
		the_comments_pagination(
			array(
				'prev_text' => '<i class="bi bi-arrow-left" aria-hidden="true"></i> ' . esc_html__( 'Older', 'yesterday' ),
				'next_text' => esc_html__( 'Newer', 'yesterday' ) . ' <i class="bi bi-arrow-right" aria-hidden="true"></i>',
			)
		);
		?>

	<?php endif; ?>

	<?php if ( $yd_ping_count ) : ?>
		<div class="pingbacks">
			<h3 class="pingbacks-title">
				<?php
				printf(
					esc_html( _n( '%s pingback', '%s pingbacks', $yd_ping_count, 'yesterday' ) ),
					esc_html( number_format_i18n( $yd_ping_count ) )
				);
				?>
			</h3>
			<ul class="pingback-list">
				<?php
				wp_list_comments(
					array(
						'style'    => 'ul',
						'type'     => 'pings',
						'callback' => 'yesterday_comment',
					)
				);
				?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( $yd_closed_notice ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'yesterday' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_form'           => 'comment-form',
			'title_reply'          => esc_html__( 'Leave a comment', 'yesterday' ),
			'title_reply_before'   => '<h3 id="reply-title" class="comments-title has-mt">',
			'title_reply_after'    => '</h3>',
			'comment_notes_before' => '',
			'comment_notes_after'  => '',
			'class_submit'         => 'btn-solid btn-inline',
			'label_submit'         => esc_html__( 'Post comment', 'yesterday' ),
			'fields'               => array(
				'author' => '<div class="row2"><div><label for="author">' . esc_html__( 'Name', 'yesterday' ) . '</label>'
					. '<input id="author" name="author" type="text" value="" required></div>',
				'email'  => '<div><label for="email">' . esc_html__( 'Email', 'yesterday' ) . '</label>'
					. '<input id="email" name="email" type="email" value="" required></div></div>',
			),
			'comment_field'        => '<label for="comment">' . esc_html__( 'Comment', 'yesterday' ) . '</label>'
				. '<textarea id="comment" name="comment" required></textarea>',
		)
	);
	?>

</section>
