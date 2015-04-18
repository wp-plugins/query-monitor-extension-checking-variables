jQuery( document ).ready( function ($) {
	$( '.SJ_Debugger dt' ).each( function() {
		if ( !$(this).next( 'dd' ).attr( 'class' ) ) $(this).css({ 'cursor' : 'pointer' });
	} );

	$( '.SJ_Debugger dt' ).click( function( e ) {
		e.preventDefault();

		if ( $(this).next( 'dd' ).attr( 'class' ) ) return false;
		$(this).next( 'dd' ).toggle();
	} );
} );