jQuery( document ).ready( function ($) {
	$( '.QMCV_IO dt.foldable' ).click( function( e ) {
		e.preventDefault();

		var id = $(this).attr( 'data-id' );

		if ( $(this).find( 'span.dashicons' ).hasClass( 'dashicons-arrow-down' ) ) {
			$(this).find( 'span.dashicons' ).attr( 'class', 'dashicons dashicons-arrow-right' );
		} else {
			$(this).find( 'span.dashicons' ).attr( 'class', 'dashicons dashicons-arrow-down' );
		}

		$( '.QMCV_IO dd[data-id="' + id + '"]' ).toggle();
	});

	$( '.QMCV_IO h3' ).click( function( e ) {
		e.preventDefault();

		var id = $(this).attr( 'data-id' );
		$( 'dl[data-id="' + id + '"]' ).toggle();
	});
} );