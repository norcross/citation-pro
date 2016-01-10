<?php
/**
 * Citation Pro - Admin Module
 *
 * Contains functions only intended to run on the admin side.
 *
 * @package Citation Pro
 */

/**
 * Start our engines.
 */
class CitationPro_Admin {

	/**
	 * Call our hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts',        array( $this, 'admin_scripts'       ),  10      );
		add_action( 'admin_init',                   array( $this, 'editor_load'         )           );
	}

	/**
	 * Load the CSS for our editor button.
	 *
	 * @return void
	 */
	public function admin_scripts() {

		// If we don't have the 'get_current_screen' function, bail immediately.
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		// Fetch the post types we are using this on.
		$types  = CitationPro_Helper::types();

		// Get current screen info.
		$screen = get_current_screen();

		// Load on post types as indicated.
		if ( is_object( $screen ) && ! empty( $screen->post_type ) && in_array( $screen->post_type, $types ) ) {
			wp_enqueue_style( 'citepro-admin', plugins_url( '/css/citepro.admin.css', __FILE__ ), array(), CITEPRO_VER, 'all' );
		}
	}

	/**
	 * Runs our check on allowed post types and if a match, loads all the TinyMCE items needed to work.
	 *
	 * @return void
	 */
	public function editor_load() {

		// Call the global pagenow.
		global $pagenow;

		// First check if we are on the post page.
		if ( 'post.php' === $pagenow && isset( $_GET['post'] ) ) {
			// Fetch the post types we are using this on.
			$types  = CitationPro_Helper::types();

			// Row run against our post types.
			if ( in_array( get_post_type( absint( $_GET['post'] ) ), $types ) ) {
				add_action ( 'admin_print_footer_scripts',  array( __class__, 'quicktag'    ) );
				add_filter ( 'mce_external_plugins',        array( __class__, 'core'        ) );
				add_filter ( 'mce_buttons',                 array( __class__, 'button'      ) );
			}
		}
	}

	/**
	 * Add the citation quicktag button to the first row.
	 *
	 * @return void
	 */
	public static function quicktag() {

		// Bail if not on quicktag row.
		if ( ! wp_script_is( 'quicktags' ) ) {
			return;
		}
	?>
		<script type="text/javascript">
		QTags.addButton( 'citation', 'citation', '[citepro]', '[/citepro]', 'n', 'Insert a citation', 299 );
		</script>
	<?php
	}

	/**
	 * Loader for the required JS.
	 *
	 * @param  array $plugins  The array of items for TinyMCE with their JS.
	 *
	 * @return array $plugins  The same array of items including ours.
	 */
	public static function core( $plugins ) {

		// Set up the item to include in the array.
		$plugins['citation'] = plugins_url( '/js/citepro.admin.js', __FILE__ );

		// Return the array.
		return $plugins;
	}

	/**
	 * Add the button key for address via JS.
	 *
	 * @param  array $buttons  All the tinyMCE buttons.
	 *
	 * @return array $buttons  Our button appended to the end.
	 */
	public static function button( $buttons ) {

		// Push our button to the end.
		array_push( $buttons, 'citation_key' );

		// Send back the full button array with our new key.
		return $buttons;
	}

	// End our class.
}

// Call our class.
$CitationPro_Admin = new CitationPro_Admin();
$CitationPro_Admin->init();
