<?php
/**
 * The site footer.
 *
 * Prints the footer widget area + bottom bar and fires wp_footer(). The
 * .container/.layout wrapper is opened and closed by each template, not here.
 *
 * NOTE: the bottom-bar copyright is dynamic (the user's site name + current
 * year — never the theme author, per the .org rules). The footer widgets
 * above are a registered widget area (see yesterday_widgets_init()).
 *
 * @package Yesterday
 */
?>
	<!-- ============ Footer ============ -->
	<footer class="site-footer">
		<div class="container">

			<div class="footer-widgets">
				<?php
				if ( is_active_sidebar( 'footer' ) ) {
					dynamic_sidebar( 'footer' );
				} else {
					yesterday_render_fallback_widgets( 'footer' );
				}
				?>
			</div>

			<div class="footer-bottom">
				<p>
					<?php
					echo '&copy; ';
					printf(
						/* translators: 1: current year, 2: site name. */
						esc_html__( '%1$s %2$s', 'yesterday' ),
						esc_html( date_i18n( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
					?>
				</p>
				<p>
					<?php
					printf(
						/* translators: %s: WordPress.org link. */
						esc_html__( 'Built with %s', 'yesterday' ),
						'<a href="' . esc_url( 'https://wordpress.org/' ) . '">' . esc_html__( 'WordPress', 'yesterday' ) . '</a>'
					);
					?>
				</p>
			</div>

		</div>
	</footer>

	<button class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'yesterday' ); ?>">
		<i class="bi bi-arrow-up" aria-hidden="true"></i>
	</button>

<?php wp_footer(); ?>
</body>
</html>
