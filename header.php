<?php
/**
 * The site header.
 *
 * Outputs the document head, opens <body>, and prints the site header + primary
 * navigation. Each template opens its own .container/.layout wrapper after this,
 * so different page types (3-column, full-width, 404) can structure themselves.
 *
 * NOTE: branding (logo/title/tagline), the primary menu and the social icons are
 * all dynamic — custom logo or get_bloginfo; wp_nav_menu with a page-list
 * fallback; and a "Social Links Menu" rendered as icons (hidden when
 * unassigned). The header search renders via get_search_form() (searchform.php)
 * wrapped in .header-search for layout.
 *
 * @package Yesterday
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'yesterday' ); ?></a>

	<!-- ============ Header ============ -->
	<header class="site-header">
		<div class="container">

			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif; ?>

				<?php
				$yd_description = get_bloginfo( 'description', 'display' );
				if ( $yd_description || is_customize_preview() ) :
					?>
					<span class="site-description"><?php echo $yd_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 'display' context is escaped by bloginfo(). ?></span>
				<?php endif; ?>
			</div>

			<button class="nav-toggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'yesterday' ); ?>" aria-expanded="false">
				<i class="bi bi-list" aria-hidden="true"></i>
			</button>

			<nav class="primary-nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'yesterday' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'menu',
						'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
						'fallback_cb'    => 'yesterday_nav_fallback',
						'walker'         => new Yesterday_Nav_Walker(),
						'depth'          => 0,
					)
				);
				?>

				<div class="header-search">
					<?php get_search_form(); ?>
				</div>

				<?php yesterday_social_menu( 'header-social' ); ?>
			</nav>

		</div>
	</header>
