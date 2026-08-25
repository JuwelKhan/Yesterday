/* Yesterday — Customizer controls pane: show the custom-font selects only when
   the "Custom" font preset is chosen (live, before saving). */
( function ( wp ) {
	'use strict';

	wp.customize( 'yesterday_font_preset', function ( setting ) {
		function toggle( value ) {
			var show = ( 'custom' === value );
			[ 'yesterday_font_body', 'yesterday_font_heading' ].forEach( function ( id ) {
				wp.customize.control( id, function ( control ) {
					control.active.set( show );
				} );
			} );
		}

		toggle( setting.get() );
		setting.bind( toggle );
	} );
} )( wp );
