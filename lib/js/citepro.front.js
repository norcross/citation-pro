//***************************************************************************************************
// my scroller
//***************************************************************************************************
function citeScroll( citeLocation ) {

	jQuery( 'html, body' ).animate({
		scrollTop: jQuery( citeLocation ).offset().top - citeproFront.citeOffset
	}, citeproFront.citeSpeed );
}

//***************************************************************************************************
// now start the engine
//***************************************************************************************************
jQuery(document).ready( function($) {

//***************************************************************************************************
// set some variables
//***************************************************************************************************
	var citeNum = 0;
	var citeRel = 0;
	var citeClk;
	var citePClass  = citeproFront.citePClass;
	var citeSClass  = citeproFront.citeSClass;

//***************************************************************************************************
// jump to citation text
//***************************************************************************************************
	$( 'sup.citepro' ).on( 'click', function() {

		// determine where I'm going
		citeNum = $( this ).data( 'cite-num' );

		// now scroll down to the citation content
		citeScroll( '.citepro-text[rel="' + parseInt( citeNum ) + '"]' );
	});

//***************************************************************************************************
// jump back to citation indicator
//***************************************************************************************************
	$( '.' + citePClass ).on( 'click', '.' + citeSClass, function( event ) {

		// determine what was clicked
		citeClk = $( event.target );

		// follow the link if clicked and bail on our scrolling
		if ( citeClk.is( 'a' ) ) {
			return;
		}

		// determine where I'm going
		citeRel = $( this ).attr( 'rel' );

		// now scroll up to the citation link
		citeScroll( 'sup.citepro[data-cite-num="' + parseInt( citeRel ) + '"]' );
	});

//***************************************************************************************************
// you're still here? it's over. go home.
//***************************************************************************************************
});