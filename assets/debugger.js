jQuery( document ).ready( function ($) {
	$( '.SJ_Debugger dt' ).each( function() {
		if ( !$(this).next( 'dd' ).attr( 'class' ) ) $(this).css({ 'cursor' : 'pointer' });
	} );

	$( '.SJ_Debugger dt' ).click( function( e ) {
		e.preventDefault();

		if ( $(this).next( 'dd' ).attr( 'class' ) ) return false;
		$(this).next( 'dd' ).toggle();
	} );

	$( '#btn-IndependVarCheck' ).click( function( e ) {
		e.preventDefault();

		var data = {
			'action': 'QMCV_CHANGE_MODE',
			'mode': 'stand alone',
		};

		$.post( ajaxurl, data, function( response ) {
			$( '#btn-IndependVarCheck' ).html( "Success!" );
		});
	});

	$( '#btn-DependVarCheck' ).click( function( e ) {
		e.preventDefault();

		var data = {
			'action': 'QMCV_CHANGE_MODE',
			'mode': 'extended',
		};

		$.post( ajaxurl, data, function( response ) {
			$( '#btn-DependVarCheck' ).html( "Success!" );
		});
	});
} );