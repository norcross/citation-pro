<?php

 // Start up the engine
class CitationPro_Admin {

	/**
	 * This is our constructor. There are many like it, but this one is mine.
	 *
	 * @return
	 */

	public function __construct() {
		add_action		(	'admin_enqueue_scripts',				array(	$this,	'citation_admin_scripts'	),	10		);
		add_action		(	'admin_init',							array(	$this,	'citation_editor_load'		)			);

	}

	/**
	 * [citation_admin_scripts description]
	 * @param  [type] $hook [description]
	 * @return [type]       [description]
	 */
	public function citation_admin_scripts( $hook ) {

		// get our post types
		$types	= CitationPro_Helper::types();

		// get current screen info
		$screen	= get_current_screen();

		// load on post types as indicated
		if ( is_object( $screen ) && in_array( $screen->post_type, $types ) ) {
			wp_enqueue_style( 'citepro-admin', plugins_url( '/css/citepro.admin.css', __FILE__ ), array(), CITEPRO_VER, 'all' );
		}

	}

	/**
	 * [citation_editor_load description]
	 * @return [type] [description]
	 */
	public function citation_editor_load() {

		// get our post types
		$types	= CitationPro_Helper::types();

		// get current screen info
		global $pagenow;

		if ( 'post.php' == $pagenow && isset( $_GET['post'] ) ) {
			if ( in_array( get_post_type( $_GET['post'] ), $types ) ) {

				add_action ( 'admin_print_footer_scripts', array( __class__, 'citation_quicktag' ) );
				add_filter ( 'mce_external_plugins', array( __class__, 'citation_tinymce_core' ) );
				add_filter ( 'mce_buttons', array( __class__, 'citation_tinymce_button'	) );

			}
		}

	}

	/**
	 * [citation_quicktag description]
	 * @return [type] [description]
	 */
	static function citation_quicktag() {
		// bail if not on quicktag row
		if ( ! wp_script_is( 'quicktags' ) ) {
			return;
		}

	?>
		<script type="text/javascript">
		QTags.addButton( 'citation', 'citation', '[citepro]', '[/citepro]', 'n', 'Citation', 299 );
		</script>
	<?php
	}

	/**
	 * loader for the required JS
	 * @param  [type] $plugin_array [description]
	 * @return [type]               [description]
	 */
	static function citation_tinymce_core( $plugin_array ) {
		// add our JS
		$plugin_array['citation'] = plugins_url( '/js/citepro.admin.js', __FILE__ );
		// return the array
		return $plugin_array;
	}

	/**
	 * Add the button key for address via JS
	 * @param  [type] $buttons [description]
	 * @return [type]          [description]
	 */
	static function citation_tinymce_button( $buttons ) {
		// push our button to the end
		array_push( $buttons, 'citation_key' );
		// send them back
		return $buttons;
	}

/// end class
}


// Instantiate our class
new CitationPro_Admin();
