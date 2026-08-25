<?php
/**
 * Yesterday theme functions and definitions.
 *
 * @package Yesterday
 */

// Block direct access — only WordPress should load this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Theme version, used to cache-bust enqueued assets.
if ( ! defined( 'YESTERDAY_VERSION' ) ) {
	define( 'YESTERDAY_VERSION', '1.0.3' );
}

/**
 * Basic theme setup: text domain, theme supports, nav menu locations.
 */
function yesterday_setup() {

	// Make the theme available for translation. Translations go in /languages.
	load_theme_textdomain( 'yesterday', get_template_directory() . '/languages' );

	// Let WordPress manage the document <title>.
	add_theme_support( 'title-tag' );

	// Add RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// Enable featured images (the "Featured image" panel in the editor) and
	// output them via the_post_thumbnail() in the templates.
	add_theme_support( 'post-thumbnails' );

	// Let Pages have an Excerpt (WordPress enables it for Posts only by default).
	// The page hero + the About template use the manual excerpt as their intro
	// line (has_excerpt(), so an empty one is simply skipped — never the
	// auto-generated content trim).
	add_post_type_support( 'page', 'excerpt' );

	// Custom logo. When the user sets a logo under Appearance →
	// Customize → Site Identity it replaces the text site title in the header;
	// with no logo set, the text title shows instead (see header.php).
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Primary navigation location. The header menu is filled from
	// whatever menu the user assigns here; with none assigned it falls back to
	// an automatic page list (yesterday_nav_fallback).
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'yesterday' ),
			'social'  => esc_html__( 'Social Links Menu', 'yesterday' ),
		)
	);

	// Post formats the design styles distinctly.
	add_theme_support(
		'post-formats',
		array( 'aside', 'image', 'video', 'quote', 'link', 'gallery', 'audio', 'status', 'chat' )
	);

	// Markup WordPress's built-in HTML5 output (search form, comment form/list,
	// gallery, caption) instead of the legacy table-based/XHTML fallbacks.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	// Block-editor alignment support and default core block styling, so
	// block content matches the theme's design intent.
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	// Load the compiled stylesheet in the block editor too, so authored
	// content previews close to the real front-end output.
	add_editor_style( 'assets/css/style.css' );
}
add_action( 'after_setup_theme', 'yesterday_setup' );

/**
 * Enqueue styles and scripts.
 *
 * Everything is loaded from the theme itself — no CDN, no external requests:
 *   - Bootstrap Icons: bundled locally in assets/vendor/bootstrap-icons/ (MIT).
 *   - Fonts: self-hosted webfonts in assets/fonts/ (OFL-1.1), plus a system
 *     stack option — see the Typography section in the Customizer.
 *   - The compiled stylesheet (assets/css/style.css) is the SCSS build output.
 *
 * Asset versions use the file's mod/time so browsers pick up recompiled CSS/JS
 * immediately during development and after updates.
 */
function yesterday_enqueue_assets() {

	// Bootstrap Icons icon font — bundled, loaded first so our styles can rely
	// on it. The unminified source (bootstrap-icons.css, same v1.11.3) ships
	// alongside this file in the same folder for reference; only the minified
	// build is enqueued.
	wp_enqueue_style(
		'bootstrap-icons',
		get_theme_file_uri( 'assets/vendor/bootstrap-icons/bootstrap-icons.min.css' ),
		array(),
		'1.11.3'
	);

	// Main compiled theme stylesheet (built from assets/scss/).
	$style_rel = 'assets/css/style.css';
	wp_enqueue_style(
		'yesterday-style',
		get_theme_file_uri( $style_rel ),
		array( 'bootstrap-icons' ),
		yesterday_asset_version( $style_rel )
	);

	// Front-end JavaScript: nav toggle, Pages dropdown, table of contents + scroll-spy.
	$script_rel = 'assets/js/main.js';
	wp_enqueue_script(
		'yesterday-main',
		get_theme_file_uri( $script_rel ),
		array(),
		yesterday_asset_version( $script_rel ),
		true // load in the footer.
	);

	// Translatable strings used by main.js (e.g. the long-list "show more" toggle).
	wp_localize_script(
		'yesterday-main',
		'yesterdayData',
		array(
			'showMore' => __( 'Show more', 'yesterday' ),
			'showLess' => __( 'Show less', 'yesterday' ),
		)
	);

	// Threaded comment replies, when the user enables threaded comments.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'yesterday_enqueue_assets' );

// Don't print WordPress's inline [gallery] CSS — the theme styles .gallery itself.
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Version string for a theme asset: its last-modified time when available,
 * otherwise the theme version. Keeps caches fresh without manual bumping.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string|int Version stamp for wp_enqueue_*.
 */
function yesterday_asset_version( $relative_path ) {
	$file = get_theme_file_path( $relative_path );
	$mtime = file_exists( $file ) ? filemtime( $file ) : false;
	return $mtime ? $mtime : YESTERDAY_VERSION;
}

/**
 * Use a plain ellipsis for auto-generated excerpts; the listing templates add
 * their own "Read more" link, so the default "[...]" is not needed.
 */
function yesterday_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'yesterday_excerpt_more' );

/**
 * Paginated-posts navigation, rendered into the theme's markup
 * (ul.pagination > li) so it matches the design. Uses core paginate_links()
 * for all the URL/number logic, then wraps each item.
 */
function yesterday_pagination() {
	$links = paginate_links(
		array(
			'type'      => 'array',
			'mid_size'  => 1,
			'end_size'  => 1,
			'prev_text' => '<i class="bi bi-arrow-left" aria-hidden="true"></i> ' . esc_html__( 'Previous', 'yesterday' ),
			'next_text' => esc_html__( 'Next', 'yesterday' ) . ' <i class="bi bi-arrow-right" aria-hidden="true"></i>',
		)
	);

	if ( empty( $links ) ) {
		return;
	}

	echo '<nav aria-label="' . esc_attr__( 'Posts navigation', 'yesterday' ) . '"><ul class="pagination">';
	foreach ( $links as $link ) {
		$class = '';
		if ( false !== strpos( $link, 'dots' ) ) {
			$class = ' class="dots"';
		} elseif ( false !== strpos( $link, 'current' ) ) {
			$class = ' class="current"';
		}
		// $link is core-generated markup (an <a> or <span>), safe to print.
		echo '<li' . $class . '>' . $link . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</ul></nav>';
}

/**
 * Render a single comment in the theme's markup. Used as the wp_list_comments()
 * callback in comments.php. Walker_Comment closes the <li> for us.
 *
 * @param WP_Comment $comment The comment object.
 * @param array      $args    wp_list_comments() arguments.
 * @param int        $depth   Nesting depth.
 */
function yesterday_comment( $comment, $args, $depth ) {
	// Pingbacks and trackbacks render as a compact one-line reference (their own
	// list/section, separate from human comments — see comments.php).
	if ( 'comment' !== $comment->comment_type ) {
		?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'pingback', $comment ); ?>>
			<i class="bi bi-link-45deg" aria-hidden="true"></i>
			<div class="pingback-body">
				<?php comment_author_link( $comment ); ?>
				<span class="pingback-date"><?php echo esc_html( get_comment_date() ); ?></span>
			</div>
		<?php
		// No closing </li> — Walker_Comment adds it.
		return;
	}
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( '', $comment ); ?>>
		<?php echo get_avatar( $comment, (int) $args['avatar_size'], '', '', array( 'class' => 'avatar' ) ); ?>
		<div class="comment-main">
			<div class="c-head">
				<span class="c-name"><?php comment_author(); ?></span>
				<?php
				// Badge when the comment is by the post author.
				$yd_post = get_post( $comment->comment_post_ID );
				if ( $yd_post && (int) $comment->user_id === (int) $yd_post->post_author && $comment->user_id ) {
					echo '<span class="author-badge">' . esc_html__( 'Author', 'yesterday' ) . '</span>';
				}
				?>
				<span class="c-date">
					<?php
					printf(
						/* translators: 1: comment date, 2: comment time. */
						esc_html__( '%1$s at %2$s', 'yesterday' ),
						esc_html( get_comment_date() ),
						esc_html( get_comment_time() )
					);
					?>
				</span>
			</div>

			<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="c-moderation"><em><?php esc_html_e( 'Your comment is awaiting moderation.', 'yesterday' ); ?></em></p>
			<?php endif; ?>

			<div class="c-body"><?php comment_text(); ?></div>

			<?php
			comment_reply_link(
				array_merge(
					$args,
					array(
						'add_below' => 'comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'reply_text' => '<i class="bi bi-reply" aria-hidden="true"></i> ' . esc_html__( 'Reply', 'yesterday' ),
					)
				),
				$comment
			);
			?>
		</div>
	<?php
	// No closing </li> — Walker_Comment adds it after any child comments.
}

/**
 * Use the theme's `archive-prefix` class on the archive-title label so it
 * matches the design's styling.
 *
 * @param string $title The archive title markup.
 * @return string
 */
function yesterday_archive_title_class( $title ) {
	return str_replace( 'archive-title-prefix', 'archive-prefix', $title );
}
add_filter( 'get_the_archive_title', 'yesterday_archive_title_class' );

/**
 * The icon + label for the current post's format.
 *
 * @return array { 0: Bootstrap Icons class, 1: translated label }
 */
function yesterday_format_meta() {
	$format = get_post_format();
	$map    = array(
		'image'   => array( 'bi-image', __( 'Image', 'yesterday' ) ),
		'gallery' => array( 'bi-images', __( 'Gallery', 'yesterday' ) ),
		'video'   => array( 'bi-play-btn', __( 'Video', 'yesterday' ) ),
		'audio'   => array( 'bi-music-note-beamed', __( 'Audio', 'yesterday' ) ),
		'quote'   => array( 'bi-quote', __( 'Quote', 'yesterday' ) ),
		'link'    => array( 'bi-link-45deg', __( 'Link', 'yesterday' ) ),
		'aside'   => array( 'bi-chat-left-text', __( 'Aside', 'yesterday' ) ),
		'status'  => array( 'bi-chat-square-text', __( 'Status', 'yesterday' ) ),
		'chat'    => array( 'bi-chat-dots', __( 'Chat', 'yesterday' ) ),
	);

	if ( isset( $map[ $format ] ) ) {
		return $map[ $format ];
	}
	return array( 'bi-file-text', __( 'Standard', 'yesterday' ) );
}

/**
 * Print the corner format badge used on listing thumbnails.
 */
function yesterday_format_badge() {
	list( $icon, $label ) = yesterday_format_meta();
	printf(
		'<span class="format-badge"><i class="bi %1$s" aria-hidden="true"></i> %2$s</span>',
		esc_attr( $icon ),
		esc_html( $label )
	);
}

/**
 * Return the first <img> tag found in the current post's content, or ''.
 * Used so image-format posts can show their image in listings even when no
 * featured image is set.
 *
 * @return string The <img> markup, or empty string.
 */
function yesterday_first_content_image() {
	$rendered = apply_filters( 'the_content', get_the_content() );
	if ( preg_match( '/<img\b[^>]*>/i', $rendered, $match ) ) {
		return $match[0];
	}
	return '';
}

/**
 * Print a "Featured" badge for sticky posts in listing views.
 */
function yesterday_sticky_badge() {
	if ( is_sticky() && ! is_singular() ) {
		printf(
			'<span class="sticky-badge"><i class="bi bi-pin-angle-fill" aria-hidden="true"></i> %s</span>',
			esc_html__( 'Featured', 'yesterday' )
		);
	}
}

/**
 * Print the post date as an entry-meta item (calendar icon + <time>).
 */
function yesterday_meta_date( $link = false ) {
	$time = sprintf(
		'<time datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	// On title-less listing cards (status, chat, quote, aside) the date is the
	// only way into the single post — and so to its comments. Link it there so
	// those formats still invite a response. Resting style is unchanged (the
	// .entry-meta a rule keeps it muted, no underline); only hover reveals it.
	if ( $link ) {
		$time = sprintf(
			'<a class="meta-permalink" href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time
		);
	}
	echo '<span>' . $time . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- composed from escaped parts.
}

/**
 * Print the first category as an entry-meta link, prefixed with a separator.
 */
function yesterday_meta_category() {
	// All categories, comma-separated (not just the first one).
	$list = get_the_category_list( ', ' );
	if ( $list ) {
		echo '<span class="sep">&middot;</span> ';
		echo wp_kses_post( $list );
	}
}

/* ============================================================
 *  Navigation
 *
 *  The design's Pages menu is a multi-level dropdown whose parent items are
 *  disclosure buttons (matching assets/js/main.js, which toggles `.open` on the
 *  `.has-dropdown` li via its `.dropdown-toggle` button) and whose sub-lists are
 *  wrapped as `<ul class="submenu"><div class="submenu-inner">…</div></ul>`.
 *  Two walkers reproduce that exact markup: one for an assigned menu, one for
 *  the automatic page-list fallback used when no menu is set.
 * ============================================================ */

/**
 * Walker for the primary menu — renders the theme's dropdown markup.
 *
 * Items with children become a `.dropdown-toggle` button (the design has no
 * link on a parent; it is purely a disclosure control) with a caret; leaf items
 * render as ordinary links. Sub-lists use the `.submenu` / `.submenu-inner`
 * wrapper the stylesheet and script expect.
 */
class Yesterday_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Open a sub-list: `<ul class="submenu"><div class="submenu-inner">`.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<ul class=\"submenu\"><div class=\"submenu-inner\">\n";
	}

	/**
	 * Close a sub-list opened by start_lvl().
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "{$indent}</div></ul>\n";
	}

	/**
	 * Open a menu item: the <li>, then either a disclosure button (has children)
	 * or a link (leaf).
	 *
	 * @param string   $output Passed by reference. Accumulated menu HTML.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of the item.
	 * @param stdClass $args   wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$has_children = $this->has_children;

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		if ( $has_children ) {
			$classes[] = 'has-dropdown';
		}
		// Map WordPress's current-item classes onto the design's `.current`.
		if ( in_array( 'current-menu-item', $classes, true )
			|| in_array( 'current_page_item', $classes, true )
			|| in_array( 'current-menu-ancestor', $classes, true )
			|| in_array( 'current-menu-parent', $classes, true ) ) {
			$classes[] = 'current';
		}

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= '<li' . $class_names . '>';

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		if ( $has_children ) {
			// Parent items are disclosure buttons (design + main.js behaviour).
			$output .= sprintf(
				'<button class="dropdown-toggle" aria-expanded="false" aria-haspopup="true">%s <i class="bi bi-chevron-down caret" aria-hidden="true"></i></button>',
				esc_html( $title )
			);
		} else {
			$atts = array(
				'href'   => ! empty( $item->url ) ? $item->url : '',
				'target' => ! empty( $item->target ) ? $item->target : '',
				'rel'    => ! empty( $item->xfn ) ? $item->xfn : '',
				'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
			);
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( '' !== $value && false !== $value ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output  = isset( $args->before ) ? $args->before : '';
			$item_output .= '<a' . $attributes . '>';
			$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . $title . ( isset( $args->link_after ) ? $args->link_after : '' );
			$item_output .= '</a>';
			$item_output .= isset( $args->after ) ? $args->after : '';

			$output .= apply_filters( 'walk_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Close a menu item opened by start_el().
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}

/**
 * Walker for the page-list fallback — mirrors Yesterday_Nav_Walker's markup so a
 * brand-new install with no assigned menu still gets a styled, working dropdown.
 *
 * Used by yesterday_nav_fallback() via wp_list_pages().
 */
class Yesterday_Page_Walker extends Walker_Page {

	/**
	 * Open a sub-list (same wrapper as the nav walker).
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "\n<ul class=\"submenu\"><div class=\"submenu-inner\">\n";
	}

	/**
	 * Close a sub-list.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "</div></ul>\n";
	}

	/**
	 * Open a page item: the <li>, then a disclosure button (has children) or a
	 * link (leaf).
	 *
	 * @param string  $output       Passed by reference.
	 * @param WP_Post $page         Page object.
	 * @param int     $depth        Depth of the page.
	 * @param array   $args         wp_list_pages() arguments.
	 * @param int     $current_page ID of the page being viewed, if any.
	 */
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		$has_children = $this->has_children;

		$css_class = array( 'page-item-' . $page->ID );
		if ( $has_children ) {
			$css_class[] = 'has-dropdown';
		}
		if ( $current_page ) {
			if ( (int) $page->ID === (int) $current_page ) {
				$css_class[] = 'current';
			} elseif ( in_array( $page->ID, get_post_ancestors( $current_page ), true ) ) {
				$css_class[] = 'current';
			}
		}

		$class_names = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
		$title       = apply_filters( 'the_title', $page->post_title, $page->ID );

		$output .= '<li class="' . esc_attr( $class_names ) . '">';

		if ( $has_children ) {
			$output .= sprintf(
				'<button class="dropdown-toggle" aria-expanded="false" aria-haspopup="true">%s <i class="bi bi-chevron-down caret" aria-hidden="true"></i></button>',
				esc_html( $title )
			);
		} else {
			$output .= sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_url( get_permalink( $page->ID ) ),
				esc_html( $title )
			);
		}
	}

	/**
	 * Close a page item.
	 */
	public function end_el( &$output, $page, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
}

/**
 * Fallback for the primary menu when the user has not assigned one: an automatic
 * list of the site's pages, in the theme's menu markup. Only top-level callers
 * (wp_nav_menu) reach this, and only when no menu is set for the location.
 *
 * @param array $args wp_nav_menu() arguments (passed through by core).
 */
function yesterday_nav_fallback( $args ) {
	$list = wp_list_pages(
		array(
			'echo'        => false,
			'title_li'    => '',
			'sort_column' => 'menu_order, post_title',
			'walker'      => new Yesterday_Page_Walker(),
		)
	);

	if ( $list ) {
		// $list is core-generated markup from our walker (each part escaped there).
		echo '<ul class="menu">' . $list . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/* ============================================================
 *  Social links menu
 *
 *  Header/footer social icons come from a "Social Links Menu" the user builds
 *  in Appearance → Menus with Custom Links. Each link's icon is chosen
 *  automatically from its URL, so the user only pastes their profile URLs — no
 *  hardcoded networks, no dead links, and the whole block hides when no menu is
 *  assigned (yesterday_social_menu checks has_nav_menu first).
 * ============================================================ */

/**
 * Pick a Bootstrap Icons class for a social URL from its host (or scheme).
 * Falls back to a generic link icon for anything unrecognised.
 *
 * @param string $url The link URL.
 * @return string Bootstrap Icons class (e.g. 'bi-instagram').
 */
function yesterday_social_icon( $url ) {
	$url = (string) $url;

	if ( 0 === stripos( $url, 'mailto:' ) ) {
		return 'bi-envelope';
	}

	$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
	$host = preg_replace( '/^www\./', '', $host );
	$path = strtolower( (string) wp_parse_url( $url, PHP_URL_PATH ) );

	// Host substring => icon. Ordered so the first match wins.
	$map = array(
		'instagram.com' => 'bi-instagram',
		'x.com'         => 'bi-twitter-x',
		'twitter.com'   => 'bi-twitter-x',
		'facebook.com'  => 'bi-facebook',
		'fb.com'        => 'bi-facebook',
		'youtube.com'   => 'bi-youtube',
		'youtu.be'      => 'bi-youtube',
		'linkedin.com'  => 'bi-linkedin',
		'github.com'    => 'bi-github',
		'gitlab.com'    => 'bi-gitlab',
		'tiktok.com'    => 'bi-tiktok',
		'pinterest.'    => 'bi-pinterest',
		'reddit.com'    => 'bi-reddit',
		'discord.'      => 'bi-discord',
		'twitch.tv'     => 'bi-twitch',
		't.me'          => 'bi-telegram',
		'telegram.'     => 'bi-telegram',
		'wa.me'         => 'bi-whatsapp',
		'whatsapp.com'  => 'bi-whatsapp',
		'vimeo.com'     => 'bi-vimeo',
		'medium.com'    => 'bi-medium',
		'spotify.com'   => 'bi-spotify',
		'snapchat.com'  => 'bi-snapchat',
		'dribbble.com'  => 'bi-dribbble',
		'behance.net'   => 'bi-behance',
		'mastodon'      => 'bi-mastodon',
		'slack.com'     => 'bi-slack',
	);

	foreach ( $map as $needle => $icon ) {
		if ( '' !== $host && false !== strpos( $host, $needle ) ) {
			return $icon;
		}
	}

	// Site/feed links get the RSS glyph.
	if ( false !== strpos( $path, '/feed' ) || false !== strpos( $host, 'feedburner' ) ) {
		return 'bi-rss';
	}

	return 'bi-link-45deg';
}

/**
 * Walker for the social menu: each item is just an icon link (no list markup),
 * so it drops straight into the header/footer social containers.
 */
class Yesterday_Social_Walker extends Walker_Nav_Menu {

	// Social menus are flat — no sub-levels.
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}

	/**
	 * Render one social link as an icon.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth (always 0 here).
	 * @param stdClass $args   wp_nav_menu() args.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$url   = ! empty( $item->url ) ? $item->url : '';
		$label = ! empty( $item->title ) ? $item->title : '';
		$icon  = yesterday_social_icon( $url );

		// Honour the menu item's "open in new tab" setting, safely.
		$rel = '';
		if ( ! empty( $item->target ) ) {
			$rel = sprintf( ' target="%s" rel="noopener noreferrer"', esc_attr( $item->target ) );
		}

		$output .= sprintf(
			'<a href="%1$s" aria-label="%2$s"%3$s><i class="bi %4$s" aria-hidden="true"></i></a>',
			esc_url( $url ),
			esc_attr( $label ),
			$rel, // composed above from escaped parts.
			esc_attr( $icon )
		);
	}
}

/**
 * Print the social menu wrapped in a container with the given class, but only
 * when a social menu is actually assigned (so the block disappears cleanly on a
 * fresh install). Shared by the header and the footer.
 *
 * @param string $container_class Wrapper class ('header-social' or 'footer-social').
 */
function yesterday_social_menu( $container_class = 'header-social' ) {
	if ( ! has_nav_menu( 'social' ) ) {
		return;
	}

	wp_nav_menu(
		array(
			'theme_location'  => 'social',
			'container'       => 'div',
			'container_class' => $container_class,
			'items_wrap'      => '%3$s', // walker emits bare <a> icons — no <ul>.
			'depth'           => 1,
			'fallback_cb'     => false,
			'walker'          => new Yesterday_Social_Walker(),
		)
	);
}

/* ============================================================
 *  Widget areas
 *
 *  Both side rails and the footer are real widget areas. The two sidebars share
 *  the card markup the design uses for widgets; the footer uses its own column
 *  markup. Sticky rule: a rail holding exactly ONE widget gets the `sticky`
 *  class (it follows the reader); two or more and it scrolls normally — handled
 *  per template via yesterday_widget_count().
 * ============================================================ */

/**
 * Register the left/right sidebars and the footer widget area.
 */
function yesterday_widgets_init() {
	$sticky_note = __( 'Tip: a single widget here becomes sticky and follows the reader as they scroll; add a second widget and stickiness turns off.', 'yesterday' );

	register_sidebar(
		array(
			'name'          => __( 'Left Sidebar', 'yesterday' ),
			'id'            => 'sidebar-left',
			'description'   => __( 'Left column on listing views (home, archives, search). Designed to hold the Author Card. ', 'yesterday' ) . $sticky_note,
			'before_widget' => '<section id="%1$s" class="widget card %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Right Sidebar', 'yesterday' ),
			'id'            => 'sidebar-right',
			'description'   => __( 'Right column on listing and single views. ', 'yesterday' ) . $sticky_note,
			'before_widget' => '<section id="%1$s" class="widget card %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer', 'yesterday' ),
			'id'            => 'footer',
			'description'   => __( 'Footer columns, shown in a row across the bottom of every page.', 'yesterday' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'yesterday_widgets_init' );

/**
 * The custom Author Card widget (image/name/role/bio/social/contact).
 */
require_once get_theme_file_path( 'inc/class-author-card-widget.php' );

/**
 * Customizer (colours, typography, layout options).
 */
require_once get_theme_file_path( 'inc/customizer.php' );

/**
 * Register theme widgets.
 */
function yesterday_register_widgets() {
	register_widget( 'Yesterday_Author_Card_Widget' );
}
add_action( 'widgets_init', 'yesterday_register_widgets' );

/**
 * Admin assets for the Author Card image picker (widgets + Customizer screens).
 *
 * @param string $hook Current admin page.
 */
function yesterday_author_card_admin_assets( $hook ) {
	if ( 'widgets.php' !== $hook && 'customize.php' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'yesterday-author-card-admin',
		get_theme_file_uri( 'assets/js/admin-author-card.js' ),
		array( 'jquery' ),
		YESTERDAY_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'yesterday_author_card_admin_assets' );

/**
 * Number of widgets currently assigned to a widget area. Drives the sticky rule
 * (exactly one widget → the rail is made sticky).
 *
 * @param string $sidebar_id Registered sidebar id.
 * @return int Widget count.
 */
function yesterday_widget_count( $sidebar_id ) {
	$sidebars = wp_get_sidebars_widgets();
	if ( empty( $sidebars[ $sidebar_id ] ) || ! is_array( $sidebars[ $sidebar_id ] ) ) {
		return 0;
	}
	return count( $sidebars[ $sidebar_id ] );
}

/**
 * The ' sticky' class (with leading space) when an area holds exactly one
 * widget, else ''. Convenience wrapper around yesterday_widget_count() for the
 * sidebar templates.
 *
 * @param string $sidebar_id Registered sidebar id.
 * @return string
 */
function yesterday_rail_sticky_class( $sidebar_id ) {
	return ( 1 === yesterday_widget_count( $sidebar_id ) ) ? ' sticky' : '';
}

/**
 * The theme's default widget set per area. Used both to seed real, movable
 * widgets on first activation and to render a graceful fallback when an area has
 * been emptied — so the shell always looks complete with REAL content (no demo
 * images, no dead links).
 *
 * @return array area id => list of [ id_base, class, instance ].
 */
function yesterday_default_widget_config() {
	return array(
		'sidebar-left' => array(
			array(
				'id_base'  => 'yesterday_author_card',
				'class'    => 'Yesterday_Author_Card_Widget',
				'instance' => array(
					'image'         => '',
					'name'          => get_bloginfo( 'name' ),
					'role'          => '',
					'bio'           => get_bloginfo( 'description' ),
					'social'        => '',
					'contact_url'   => '',
					'contact_label' => __( 'Get in touch', 'yesterday' ),
					'layout'        => 'auto',
				),
			),
		),
		'sidebar-right' => array(
			array(
				'id_base'  => 'search',
				'class'    => 'WP_Widget_Search',
				'instance' => array( 'title' => '' ),
			),
			array(
				'id_base'  => 'categories',
				'class'    => 'WP_Widget_Categories',
				'instance' => array( 'title' => __( 'Categories', 'yesterday' ), 'count' => 0, 'hierarchical' => 0, 'dropdown' => 0 ),
			),
			array(
				'id_base'  => 'recent-posts',
				'class'    => 'WP_Widget_Recent_Posts',
				'instance' => array( 'title' => __( 'Recent Posts', 'yesterday' ), 'number' => 5, 'show_date' => 1 ),
			),
		),
		'footer' => array(
			array(
				'id_base'  => 'text',
				'class'    => 'WP_Widget_Text',
				'instance' => array( 'title' => get_bloginfo( 'name' ), 'text' => get_bloginfo( 'description' ), 'filter' => true, 'visual' => true ),
			),
			array(
				'id_base'  => 'recent-posts',
				'class'    => 'WP_Widget_Recent_Posts',
				'instance' => array( 'title' => __( 'Recent Posts', 'yesterday' ), 'number' => 4, 'show_date' => 1 ),
			),
			array(
				'id_base'  => 'categories',
				'class'    => 'WP_Widget_Categories',
				'instance' => array( 'title' => __( 'Categories', 'yesterday' ), 'count' => 1, 'hierarchical' => 0, 'dropdown' => 0 ),
			),
			array(
				'id_base'  => 'archives',
				'class'    => 'WP_Widget_Archives',
				'instance' => array( 'title' => __( 'Archives', 'yesterday' ), 'count' => 0, 'dropdown' => 0 ),
			),
		),
	);
}

/**
 * Seed default widgets the first time the theme is activated. Runs once (guarded
 * by an option) and only fills areas the user hasn't already set up, so it never
 * clobbers an existing configuration.
 */
function yesterday_set_default_widgets() {
	if ( get_option( 'yesterday_default_widgets_done' ) ) {
		return;
	}
	update_option( 'yesterday_default_widgets_done', 1 );

	$config   = yesterday_default_widget_config();
	$sidebars = get_option( 'sidebars_widgets', array() );
	if ( ! is_array( $sidebars ) ) {
		$sidebars = array();
	}

	foreach ( $config as $area => $widgets ) {
		if ( ! empty( $sidebars[ $area ] ) ) {
			continue; // Respect a pre-existing setup.
		}

		$ids = array();
		foreach ( $widgets as $widget ) {
			$option = 'widget_' . $widget['id_base'];
			$stored = get_option( $option, array() );
			if ( ! is_array( $stored ) ) {
				$stored = array();
			}

			$index = 1;
			foreach ( array_keys( $stored ) as $key ) {
				if ( is_numeric( $key ) ) {
					$index = max( $index, (int) $key + 1 );
				}
			}

			$stored[ $index ]       = $widget['instance'];
			$stored['_multiwidget'] = 1;
			update_option( $option, $stored );

			$ids[] = $widget['id_base'] . '-' . $index;
		}

		$sidebars[ $area ] = $ids;
	}

	update_option( 'sidebars_widgets', $sidebars );
}
add_action( 'after_switch_theme', 'yesterday_set_default_widgets' );

/**
 * Render the default widget set for an area as a graceful fallback, using real
 * content via the_widget(). Called by the sidebar/footer templates when an area
 * has no widgets assigned.
 *
 * @param string $area Registered sidebar id.
 */
function yesterday_render_fallback_widgets( $area ) {
	$config = yesterday_default_widget_config();
	if ( empty( $config[ $area ] ) ) {
		return;
	}

	if ( 'footer' === $area ) {
		$args = array(
			'before_widget' => '<div class="footer-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		);
	} else {
		$args = array(
			'before_widget' => '<section class="widget card">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		);
	}

	foreach ( $config[ $area ] as $widget ) {
		the_widget( $widget['class'], $widget['instance'], $args );
	}
}
