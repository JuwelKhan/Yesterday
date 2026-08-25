/* Author Card widget — image picker (admin). */
( function ( $ ) {
	'use strict';

	// Open the media library and store the chosen image's URL + preview.
	$( document ).on( 'click', '.yd-ac-upload', function ( e ) {
		e.preventDefault();

		var $btn = $( this );
		var $row = $btn.closest( 'p' );
		var $input = $row.find( '.yd-ac-image' );
		var $preview = $row.find( '.yd-ac-preview' );

		var frame = wp.media( {
			title: $btn.text(),
			multiple: false,
			library: { type: 'image' }
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var url = attachment.url;

			// trigger('change') so the widget registers the edit and enables Save.
			$input.val( url ).trigger( 'change' );
			$preview.attr( 'src', url ).show();
			$row.find( '.yd-ac-remove' ).show();
		} );

		frame.open();
	} );

	// Clear the image.
	$( document ).on( 'click', '.yd-ac-remove', function ( e ) {
		e.preventDefault();
		var $row = $( this ).closest( 'p' );
		$row.find( '.yd-ac-image' ).val( '' ).trigger( 'change' );
		$row.find( '.yd-ac-preview' ).attr( 'src', '' ).hide();
		$( this ).hide();
	} );
} )( jQuery );
