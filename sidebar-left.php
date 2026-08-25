<?php
/**
 * Left sidebar.
 *
 * The registered `sidebar-left` widget area, used on listing views and
 * designed for the Author Card. A single widget makes the rail sticky. With no
 * widgets assigned we render the default Author Card with real content
 * (yesterday_render_fallback_widgets) — a lone widget, so the rail is sticky.
 *
 * (On single posts the left rail is built inline in single.php — table of
 * contents, with the post-author card as its own fallback.)
 *
 * @package Yesterday
 */

if ( is_active_sidebar( 'sidebar-left' ) ) :
	?>
	<aside class="sidebar<?php echo esc_attr( yesterday_rail_sticky_class( 'sidebar-left' ) ); ?>" aria-label="<?php esc_attr_e( 'Sidebar', 'yesterday' ); ?>">
		<?php dynamic_sidebar( 'sidebar-left' ); ?>
	</aside>
	<?php
else :
	?>
	<aside class="sidebar sticky" aria-label="<?php esc_attr_e( 'About the author', 'yesterday' ); ?>">
		<?php yesterday_render_fallback_widgets( 'sidebar-left' ); ?>
	</aside>
	<?php
endif;
