/* Yesterday — Customizer live preview (colours). */
( function ( wp ) {
	'use strict';

	var root = document.documentElement;
	var data = window.yesterdayCustomize || { presets: {} };

	// Every variable any preset (or the accent) can touch — so we can reset
	// cleanly back to the compiled defaults by removing the inline override.
	var allVars = { '--brand': true, '--brand-dark': true };
	Object.keys( data.presets ).forEach( function ( key ) {
		Object.keys( data.presets[ key ] ).forEach( function ( v ) {
			allVars[ v ] = true;
		} );
	} );

	function adjust( hex, steps ) {
		hex = String( hex ).replace( '#', '' );
		if ( 3 === hex.length ) {
			hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
		}
		if ( 6 !== hex.length ) {
			return '#' + hex;
		}
		var out = '#';
		for ( var i = 0; i < 3; i++ ) {
			var c = parseInt( hex.substr( i * 2, 2 ), 16 );
			c = Math.max( 0, Math.min( 255, c + steps ) );
			out += ( '0' + c.toString( 16 ) ).slice( -2 );
		}
		return out;
	}

	var state = { preset: 'slate', accent: '' };

	function render() {
		var vars = {};
		var preset = data.presets[ state.preset ] || {};
		Object.keys( preset ).forEach( function ( v ) {
			vars[ v ] = preset[ v ];
		} );

		if ( state.accent ) {
			vars['--brand'] = state.accent;
			vars['--brand-dark'] = adjust( state.accent, -22 );
		}

		Object.keys( allVars ).forEach( function ( v ) {
			if ( vars[ v ] ) {
				root.style.setProperty( v, vars[ v ] );
			} else {
				root.style.removeProperty( v );
			}
		} );
	}

	wp.customize( 'yesterday_color_preset', function ( setting ) {
		state.preset = setting();
		setting.bind( function ( value ) {
			state.preset = value;
			render();
		} );
	} );

	wp.customize( 'yesterday_accent_color', function ( setting ) {
		state.accent = setting();
		setting.bind( function ( value ) {
			state.accent = value;
			render();
		} );
	} );

	// ----- Typography -----
	var fontStacks = data.fontStacks || {};
	var fontPresets = data.fontPresets || {};
	var fstate = { preset: 'system', body: 'system-sans', heading: 'system-sans' };

	function renderFonts() {
		var vars = {};

		if ( 'custom' === fstate.preset ) {
			if ( fontStacks[ fstate.body ] ) {
				vars['--font-body'] = fontStacks[ fstate.body ];
			}
			if ( fontStacks[ fstate.heading ] ) {
				vars['--font-heading'] = fontStacks[ fstate.heading ];
			}
		} else {
			var p = fontPresets[ fstate.preset ];
			if ( p && p.body ) {
				vars['--font-body'] = fontStacks[ p.body ];
				vars['--font-heading'] = fontStacks[ p.heading ];
			}
		}

		[ '--font-body', '--font-heading' ].forEach( function ( v ) {
			if ( vars[ v ] ) {
				root.style.setProperty( v, vars[ v ] );
			} else {
				root.style.removeProperty( v );
			}
		} );
	}

	wp.customize( 'yesterday_font_preset', function ( setting ) {
		fstate.preset = setting();
		setting.bind( function ( value ) {
			fstate.preset = value;
			renderFonts();
		} );
	} );

	wp.customize( 'yesterday_font_body', function ( setting ) {
		fstate.body = setting();
		setting.bind( function ( value ) {
			fstate.body = value;
			renderFonts();
		} );
	} );

	wp.customize( 'yesterday_font_heading', function ( setting ) {
		fstate.heading = setting();
		setting.bind( function ( value ) {
			fstate.heading = value;
			renderFonts();
		} );
	} );

	// ----- Layout toggles (body classes; class is present when the option is OFF) -----
	function bindBodyClass( settingId, className ) {
		wp.customize( settingId, function ( setting ) {
			function apply( value ) {
				document.body.classList.toggle( className, ! value );
			}
			apply( setting.get() );
			setting.bind( apply );
		} );
	}

	bindBodyClass( 'yesterday_show_header_search', 'no-header-search' );
	bindBodyClass( 'yesterday_show_author_box', 'no-author-box' );
	bindBodyClass( 'yesterday_sticky_sidebars', 'no-sticky' );
} )( wp );

