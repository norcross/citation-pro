( function() {
	tinymce.PluginManager.add( 'citation', function( editor, url ) {

		// Add a button that opens a window
		editor.addButton( 'citation_key', {
			icon: 'citation-icon',
			tooltip: 'Insert Citation',
			onclick: function() {
				// Open window
				editor.windowManager.open( {
					title: 'Insert Citation',
					body: [{
						type: 'textbox',
						name: 'cite_text',
						label: 'Citation Text'
					}],
					width: 500,
					height: 80,
					onsubmit: function( e ) {
						// Insert content when the window form is submitted
						editor.insertContent( '[citepro]' + e.data.cite_text + '[/citepro]' );
					}
				});
			}
		});
	});
} )();