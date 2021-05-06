//***************************************************************************************************
// My scroller.
//***************************************************************************************************
function citeScroll( citeLocation ) {

	jQuery( 'html, body' ).animate({
		scrollTop: jQuery( citeLocation ).offset().top - citeproFront.citeOffset
	}, citeproFront.citeSpeed );
}

//***************************************************************************************************
// Now start the engine.
//***************************************************************************************************
jQuery(document).ready( function($) {

//***************************************************************************************************
// Set some variables.
//***************************************************************************************************
	var citeNum = 1;
	var citeRel = 1;
	var citeClk;
	var citePClass  = citeproFront.citePClass;
	var citeSClass  = citeproFront.citeSClass;

//***************************************************************************************************
// Jump to citation text.
//***************************************************************************************************
	$( 'sup.citepro' ).on( 'click', 'a.citepro-inline-link', function( event ) {

		// Don't do the link.
		event.preventDefault();

		// Determine where I'm going.
		citeNum = $( this ).data( 'cite-num' );

		// Now scroll down to the citation content.
		citeScroll( 'div#citepro-text-' + parseInt( citeNum ) );
	});

//***************************************************************************************************
// Jump back to citation indicator.
//***************************************************************************************************
	$( '.' + citePClass ).on( 'click', 'a.citepro-list-link', function( event ) {

		// Don't do the link.
		event.preventDefault();

		// Determine where I'm going.
		citeRel = $( this ).attr( 'rel' );

		// Now scroll up to the citation link.
		citeScroll( 'sup#citepro-inline-' + parseInt( citeRel ) );
	});

//***************************************************************************************************
// You're still here? It's over. Go home.
//***************************************************************************************************
});
