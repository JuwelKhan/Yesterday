<?php
/**
 * Customizer — colours, typography and layout options.
 *
 * The design is driven by CSS custom properties defined in :root (assets/css/
 * style.css). The Customizer never edits that file; instead it prints a small
 * <style> override in wp_head, so untouched settings = the compiled defaults.
 *
 * Colours: one-click palette presets + an accent colour picker (overrides the
 * brand/primary colour). Live preview via assets/js/customizer-preview.js.
 *
 * @package Yesterday
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Colour palette presets. Each maps CSS variable => value; the default preset
 * ('slate') is empty because it IS the compiled default.
 *
 * @return array
 */
function yesterday_color_presets() {
	return array(
		'slate'  => array(
			'label' => __( 'Slate (default)', 'yesterday' ),
			'vars'  => array(),
		),
		'sepia'  => array(
			'label' => __( 'Warm Sepia', 'yesterday' ),
			'vars'  => array(
				'--bg'               => '#ece7df',
				'--surface'          => '#fbf8f3',
				'--ink'              => '#3b2f26',
				'--ink-soft'         => '#5a4a3d',
				'--muted'            => '#8a7a6b',
				'--line'             => '#e6ddd2',
				'--brand'            => '#8a5a3c',
				'--brand-dark'       => '#6f4630',
				'--brand-soft'       => '#c9a98f',
				'--brand-soft-light' => '#dcc4b0',
				'--status'           => '#7a6250',
				'--chip'             => '#f1ebe2',
			),
		),
		'forest' => array(
			'label' => __( 'Forest', 'yesterday' ),
			'vars'  => array(
				'--bg'               => '#dce4df',
				'--surface'          => '#ffffff',
				'--ink'              => '#1e2b25',
				'--ink-soft'         => '#354940',
				'--muted'            => '#5f7168',
				'--line'             => '#dde7e2',
				'--brand'            => '#2f5d4a',
				'--brand-dark'       => '#244a3b',
				'--brand-soft'       => '#9fc0b1',
				'--brand-soft-light' => '#b8d2c6',
				'--status'           => '#4a6357',
				'--chip'             => '#e8efeb',
			),
		),
	);
}

/**
 * Shift a hex colour brighter (+) or darker (-) by an amount per channel.
 *
 * @param string $hex   Hex colour (#rrggbb).
 * @param int    $steps -255..255 added to each channel.
 * @return string Hex colour.
 */
function yesterday_adjust_brightness( $hex, $steps ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) ) {
		return '#' . $hex;
	}
	$out = '#';
	for ( $i = 0; $i < 3; $i++ ) {
		$c    = hexdec( substr( $hex, $i * 2, 2 ) );
		$c    = max( 0, min( 255, $c + $steps ) );
		$out .= str_pad( dechex( $c ), 2, '0', STR_PAD_LEFT );
	}
	return $out;
}

/**
 * Resolve the CSS variable overrides implied by the current colour settings.
 *
 * @return array variable => value.
 */
function yesterday_color_css_vars() {
	$presets = yesterday_color_presets();
	$preset  = get_theme_mod( 'yesterday_color_preset', 'slate' );
	$vars    = isset( $presets[ $preset ] ) ? $presets[ $preset ]['vars'] : array();

	$accent = get_theme_mod( 'yesterday_accent_color', '' );
	if ( $accent ) {
		$vars['--brand']      = $accent;
		$vars['--brand-dark'] = yesterday_adjust_brightness( $accent, -22 );
	}

	return $vars;
}

/**
 * Font-family stacks the theme offers. Bundled webfonts (Inter/Lora/Playfair)
 * are self-hosted via @font-face (assets/scss/_fonts.scss); the two "system"
 * options need no download.
 *
 * @return array key => font-family stack.
 */
function yesterday_font_stacks() {
	return array(
		'system-sans'  => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif',
		'system-serif' => 'Georgia, Cambria, "Times New Roman", Times, serif',
		'inter'        => '"Inter", sans-serif',
		'lora'         => '"Lora", Georgia, serif',
		'playfair'     => '"Playfair Display", Georgia, serif',
	);
}

/**
 * Typography presets. `body`/`heading` are keys into yesterday_font_stacks();
 * null = use the compiled default (system). 'custom' pulls from its own settings.
 *
 * @return array
 */
function yesterday_font_presets() {
	return array(
		'system'    => array( 'label' => __( 'System (default)', 'yesterday' ), 'body' => null, 'heading' => null ),
		'elegant'   => array( 'label' => __( 'Elegant — Playfair + Lora', 'yesterday' ), 'body' => 'lora', 'heading' => 'playfair' ),
		'modern'    => array( 'label' => __( 'Modern — Inter', 'yesterday' ), 'body' => 'inter', 'heading' => 'inter' ),
		'editorial' => array( 'label' => __( 'Editorial — Playfair + Inter', 'yesterday' ), 'body' => 'inter', 'heading' => 'playfair' ),
		'custom'    => array( 'label' => __( 'Custom', 'yesterday' ), 'body' => null, 'heading' => null ),
	);
}

/**
 * Resolve the --font-body / --font-heading overrides from the current settings.
 *
 * @return array variable => font stack.
 */
function yesterday_font_css_vars() {
	$stacks  = yesterday_font_stacks();
	$presets = yesterday_font_presets();
	$preset  = get_theme_mod( 'yesterday_font_preset', 'system' );
	$vars    = array();

	if ( 'custom' === $preset ) {
		$body = get_theme_mod( 'yesterday_font_body', 'system-sans' );
		$head = get_theme_mod( 'yesterday_font_heading', 'system-sans' );
		if ( isset( $stacks[ $body ] ) ) {
			$vars['--font-body'] = $stacks[ $body ];
		}
		if ( isset( $stacks[ $head ] ) ) {
			$vars['--font-heading'] = $stacks[ $head ];
		}
	} elseif ( isset( $presets[ $preset ] ) && $presets[ $preset ]['body'] ) {
		$vars['--font-body']    = $stacks[ $presets[ $preset ]['body'] ];
		$vars['--font-heading'] = $stacks[ $presets[ $preset ]['heading'] ];
	}

	return $vars;
}

/**
 * Keep only characters valid in a CSS font-family list.
 *
 * @param string $value Raw stack.
 * @return string
 */
function yesterday_sanitize_font_stack( $value ) {
	return trim( preg_replace( '/[^a-zA-Z0-9 ,"\'\-]/', '', (string) $value ) );
}

/**
 * Print the colour + typography overrides as a :root <style> block in the head.
 */
function yesterday_customizer_css() {
	$decls = '';

	foreach ( yesterday_color_css_vars() as $name => $value ) {
		$value = sanitize_hex_color( $value );
		if ( $value ) {
			$decls .= $name . ':' . $value . ';';
		}
	}

	foreach ( yesterday_font_css_vars() as $name => $value ) {
		$value = yesterday_sanitize_font_stack( $value );
		if ( $value ) {
			$decls .= $name . ':' . $value . ';';
		}
	}

	if ( '' === $decls ) {
		return;
	}

	printf( "<style id=\"yesterday-customizer-css\">:root{%s}</style>\n", $decls ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- literal var names + sanitized values.
}
add_action( 'wp_head', 'yesterday_customizer_css' );

/**
 * Register Customizer sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function yesterday_customize_register( $wp_customize ) {

	$wp_customize->add_section(
		'yesterday_colors',
		array(
			'title'       => __( 'Colours', 'yesterday' ),
			'priority'    => 30,
			'description' => __( 'Pick a palette, then optionally override the accent colour.', 'yesterday' ),
		)
	);

	// Palette preset.
	$choices = array();
	foreach ( yesterday_color_presets() as $key => $preset ) {
		$choices[ $key ] = $preset['label'];
	}

	$wp_customize->add_setting(
		'yesterday_color_preset',
		array(
			'default'           => 'slate',
			'sanitize_callback' => 'yesterday_sanitize_color_preset',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'yesterday_color_preset',
		array(
			'label'   => __( 'Palette', 'yesterday' ),
			'section' => 'yesterday_colors',
			'type'    => 'radio',
			'choices' => $choices,
		)
	);

	// Accent (brand/primary) colour override.
	$wp_customize->add_setting(
		'yesterday_accent_color',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'yesterday_accent_color',
			array(
				'label'       => __( 'Accent colour', 'yesterday' ),
				'description' => __( 'Overrides the palette\'s primary colour (links, buttons, header). Leave empty to use the palette.', 'yesterday' ),
				'section'     => 'yesterday_colors',
			)
		)
	);

	// ---- Typography ----
	$wp_customize->add_section(
		'yesterday_typography',
		array(
			'title'       => __( 'Typography', 'yesterday' ),
			'priority'    => 35,
			'description' => __( 'Pick a font preset, or choose Custom to mix your own body and heading fonts. Bundled fonts are self-hosted — no external requests.', 'yesterday' ),
		)
	);

	$preset_choices = array();
	foreach ( yesterday_font_presets() as $key => $preset ) {
		$preset_choices[ $key ] = $preset['label'];
	}

	$wp_customize->add_setting(
		'yesterday_font_preset',
		array(
			'default'           => 'system',
			'sanitize_callback' => 'yesterday_sanitize_font_preset',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'yesterday_font_preset',
		array(
			'label'   => __( 'Font preset', 'yesterday' ),
			'section' => 'yesterday_typography',
			'type'    => 'radio',
			'choices' => $preset_choices,
		)
	);

	$font_choices = array(
		'system-sans'  => __( 'System Sans', 'yesterday' ),
		'system-serif' => __( 'System Serif', 'yesterday' ),
		'inter'        => 'Inter',
		'lora'         => 'Lora',
		'playfair'     => 'Playfair Display',
	);

	$is_custom = function () {
		return 'custom' === get_theme_mod( 'yesterday_font_preset', 'system' );
	};

	$wp_customize->add_setting(
		'yesterday_font_body',
		array(
			'default'           => 'system-sans',
			'sanitize_callback' => 'yesterday_sanitize_font_key',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'yesterday_font_body',
		array(
			'label'           => __( 'Body font (Custom)', 'yesterday' ),
			'section'         => 'yesterday_typography',
			'type'            => 'select',
			'choices'         => $font_choices,
			'active_callback' => $is_custom,
		)
	);

	$wp_customize->add_setting(
		'yesterday_font_heading',
		array(
			'default'           => 'system-sans',
			'sanitize_callback' => 'yesterday_sanitize_font_key',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'yesterday_font_heading',
		array(
			'label'           => __( 'Heading font (Custom)', 'yesterday' ),
			'section'         => 'yesterday_typography',
			'type'            => 'select',
			'choices'         => $font_choices,
			'active_callback' => $is_custom,
		)
	);

	// ---- Layout ----
	$wp_customize->add_section(
		'yesterday_layout',
		array(
			'title'    => __( 'Layout', 'yesterday' ),
			'priority' => 40,
		)
	);

	// These three are pure CSS visibility (markup always renders; a body class
	// hides it), so the Customizer preview can update them instantly.
	$toggles = array(
		'yesterday_show_header_search' => __( 'Show search in the header', 'yesterday' ),
		'yesterday_show_author_box'    => __( 'Show the author box on single posts', 'yesterday' ),
		'yesterday_sticky_sidebars'    => __( 'Sticky sidebars (rails follow scroll)', 'yesterday' ),
	);

	foreach ( $toggles as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => true,
				'sanitize_callback' => 'yesterday_sanitize_checkbox',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'yesterday_layout',
				'type'    => 'checkbox',
			)
		);
	}

	// These two decide whether PHP renders the markup at all (reading time is
	// computed and printed, or not; the related-posts query runs, or doesn't),
	// so there's no live-preview class to toggle — the preview refreshes
	// instead, same as any other server-rendered change.
	$refresh_toggles = array(
		'yesterday_show_reading_time'  => __( 'Show estimated reading time', 'yesterday' ),
		'yesterday_show_related_posts' => __( 'Show related posts after each article', 'yesterday' ),
	);

	foreach ( $refresh_toggles as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => true,
				'sanitize_callback' => 'yesterday_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'yesterday_layout',
				'type'    => 'checkbox',
			)
		);
	}

	// Excerpt length — defaults to WordPress core's own default (55 words) so
	// upgrading the theme never silently changes an existing site's excerpts.
	$wp_customize->add_setting(
		'yesterday_excerpt_length',
		array(
			'default'           => 55,
			'sanitize_callback' => 'yesterday_sanitize_excerpt_length',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'yesterday_excerpt_length',
		array(
			'label'       => __( 'Excerpt length (words)', 'yesterday' ),
			'description' => __( 'How many words to show in auto-generated excerpts on listing pages.', 'yesterday' ),
			'section'     => 'yesterday_layout',
			'type'        => 'number',
			'input_attrs' => array(
				'min' => 10,
				'max' => 100,
			),
		)
	);
}
add_action( 'customize_register', 'yesterday_customize_register' );

/**
 * Sanitize a checkbox setting to a boolean.
 *
 * @param mixed $value Submitted value.
 * @return bool
 */
function yesterday_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Sanitize the excerpt-length setting, clamped to the control's own 10-100
 * range — falls back to WordPress core's default (55) for anything outside
 * that range or not a usable number.
 *
 * @param mixed $value Submitted value.
 * @return int
 */
function yesterday_sanitize_excerpt_length( $value ) {
	$value = absint( $value );
	if ( $value < 10 || $value > 100 ) {
		return 55;
	}
	return $value;
}

/**
 * Add body classes for the layout toggles so CSS can hide/adjust the affected
 * regions (kept in the markup for live preview; removed visually when off).
 *
 * @param array $classes Body classes.
 * @return array
 */
function yesterday_layout_body_classes( $classes ) {
	if ( ! get_theme_mod( 'yesterday_show_header_search', true ) ) {
		$classes[] = 'no-header-search';
	}
	if ( ! get_theme_mod( 'yesterday_show_author_box', true ) ) {
		$classes[] = 'no-author-box';
	}
	if ( ! get_theme_mod( 'yesterday_sticky_sidebars', true ) ) {
		$classes[] = 'no-sticky';
	}
	return $classes;
}
add_filter( 'body_class', 'yesterday_layout_body_classes' );

/**
 * Sanitize the typography preset against the registered presets.
 *
 * @param string $value Submitted value.
 * @return string
 */
function yesterday_sanitize_font_preset( $value ) {
	$presets = yesterday_font_presets();
	return isset( $presets[ $value ] ) ? $value : 'system';
}

/**
 * Sanitize a font choice against the registered stacks.
 *
 * @param string $value Submitted value.
 * @return string
 */
function yesterday_sanitize_font_key( $value ) {
	$stacks = yesterday_font_stacks();
	return isset( $stacks[ $value ] ) ? $value : 'system-sans';
}

/**
 * Sanitize the palette choice against the registered presets.
 *
 * @param string $value Submitted value.
 * @return string
 */
function yesterday_sanitize_color_preset( $value ) {
	$presets = yesterday_color_presets();
	return isset( $presets[ $value ] ) ? $value : 'slate';
}

/**
 * Enqueue the live-preview script and hand it the palette data.
 */
function yesterday_customize_preview_js() {
	wp_enqueue_script(
		'yesterday-customizer-preview',
		get_theme_file_uri( 'assets/js/customizer-preview.js' ),
		array( 'customize-preview' ),
		YESTERDAY_VERSION,
		true
	);

	$presets = array();
	foreach ( yesterday_color_presets() as $key => $preset ) {
		$presets[ $key ] = $preset['vars'];
	}

	$font_presets = array();
	foreach ( yesterday_font_presets() as $key => $preset ) {
		$font_presets[ $key ] = array(
			'body'    => $preset['body'],
			'heading' => $preset['heading'],
		);
	}

	wp_localize_script(
		'yesterday-customizer-preview',
		'yesterdayCustomize',
		array(
			'presets'     => $presets,
			'fontStacks'  => yesterday_font_stacks(),
			'fontPresets' => $font_presets,
		)
	);
}
add_action( 'customize_preview_init', 'yesterday_customize_preview_js' );

/**
 * Controls-pane script: live-toggle the custom-font selects when the Custom
 * preset is chosen (PHP active_callback only reflects the saved value).
 */
function yesterday_customize_controls_js() {
	wp_enqueue_script(
		'yesterday-customizer-controls',
		get_theme_file_uri( 'assets/js/customizer-controls.js' ),
		array( 'customize-controls' ),
		YESTERDAY_VERSION,
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'yesterday_customize_controls_js' );
