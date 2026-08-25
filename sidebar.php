<?php
/**
 * Right sidebar.
 *
 * The registered `sidebar-right` widget area. A single widget makes
 * the rail sticky. When no widgets are assigned we render the default set with
 * real content (yesterday_render_fallback_widgets) so the column is never empty
 * — and never shows demo images or dead links.
 *
 * @package Yesterday
 */

$yd_active = is_active_sidebar( 'sidebar-right' );
$yd_sticky = $yd_active ? yesterday_rail_sticky_class( 'sidebar-right' ) : '';
?>
<aside class="sidebar<?php echo esc_attr( $yd_sticky ); ?>" aria-label="<?php esc_attr_e( 'Blog sidebar', 'yesterday' ); ?>">
	<?php
	if ( $yd_active ) {
		dynamic_sidebar( 'sidebar-right' );
	} else {
		yesterday_render_fallback_widgets( 'sidebar-right' );
	}
	?>
</aside>
