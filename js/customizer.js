jQuery( function ( $ ) {
	'use strict';

	function updateFontStyle() {
		var styles = $( this ).find( 'option:selected' ).data( 'styles' ),
			$options = $( this ).closest( '.customize-control' ).next().find( 'option' );

		styles = styles.toString().split( ',' );
		$options.prop( 'selected', false ).show();

		// Hide unmatched styles.
		$options.filter( function ( index ) {
			return index && - 1 === $.inArray( this.value, styles );
		} ).hide();

		// Reset the selection to "No change".
		$options.first().prop( 'selected', true );
	}

	$( document ).on( 'change', '.ultimatefonts-font-family', updateFontStyle );
} );