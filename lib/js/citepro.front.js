jQuery(document).ready( function($) {

//***************************************************************************************************
// set some variables
//***************************************************************************************************
	var citeNum;
	var citeRel;
	var citeOffset	= citeproFront.citeOffset;
	var citeSpeed	= citeproFront.citeSpeed;

//***************************************************************************************************
// jump to citation text
//***************************************************************************************************
	$( 'sup.citepro' ).on( 'click', function() {

		// determine where I'm going
		citeNum	= $( this ).data( 'citenum' );

		// now scroll to the citation
		$( 'html, body' ).animate({
			scrollTop: $( 'span.citepro-text[rel="' + parseInt( citeNum ) + '"]' ).offset().top - citeOffset
		}, citeSpeed );

	});

//***************************************************************************************************
// jump back to citation indicator
//***************************************************************************************************
	$( 'p.citepro-block' ).on( 'click', 'span.citepro-text', function() {

		// determine where I'm going
		citeRel	= $( this ).attr( 'rel' );

		// now scroll to the citation
		$( 'html, body' ).animate({
			scrollTop: $( 'sup.citepro[data-citenum="' + parseInt( citeRel ) + '"]' ).offset().top - citeOffset
		}, citeSpeed );

	});

//***************************************************************************************************
// you're still here? it's over. go home.
//***************************************************************************************************
});