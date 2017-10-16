<?php
/**
 * Citation Pro - Front Module
 *
 * Contains functions only intended to run on the front-end.
 *
 * @package Citation Pro
 */

/**
 * Start our engines.
 */
class CitationPro_Front {

	/**
	 * Static property to start the cite count
	 * @var $citecount
	 */
	static $citecount = 1;

	/**
	 * Call our hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts',           array( $this, 'front_js'            ),  50      );
		add_action( 'wp_enqueue_scripts',           array( $this, 'front_css'           ),  50      );
		add_filter( 'the_content',                  array( $this, 'display'             )           );
		add_shortcode( 'citepro',                   array( $this, 'shortcode'           )           );
	}

	/**
	 * Load the JS related to scrolling fanciness.
	 *
	 * @return void
	 */
	public function front_js() {

		// Check for filter flag to disable showing on home page.
		if ( false === self::check_homepage_load() ) {
			return;
		}

		// Fetch global post object.
		global $post;

		// Bail without shortcode.
		if ( empty( $post ) || ! is_object( $post ) | ! has_shortcode( $post->post_content, 'citepro' ) ) {
			return;
		}

		// Check for our killswitch flag.
		if ( false === apply_filters( 'citepro_load_js', '__return_true' ) ) {
			return;
		}

		// Set a file suffix structure based on whether or not we want a minified version.
		$file   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'citepro.front' : 'citepro.front.min';

		// Set a version for whether or not we're debugging.
		$vers   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : CITEPRO_VERS;

		// Load my JS.
		wp_enqueue_script( 'citepro-front', CITEPRO_URL . 'lib/js/' . $file . '.js', array( 'jquery' ), $vers, true );
		wp_localize_script( 'citepro-front', 'citeproFront', array(
			'citeSpeed'     => apply_filters( 'citepro_scroll_speed', 750 ),
			'citeOffset'    => apply_filters( 'citepro_scroll_offset', 40 ),
			'citePClass'    => apply_filters( 'citepro_markup_paragraph_class', 'citepro-block' ),
			'citeSClass'    => apply_filters( 'citepro_markup_span_class', 'citepro-text' ),
		));
	}

	/**
	 * Load the front end CSS related to the shortcode and generated list.
	 *
	 * @return void
	 */
	public function front_css() {

		// Check for filter flag to disable showing on home page.
		if ( false === self::check_homepage_load() ) {
			return;
		}

		// Fetch global post object.
		global $post;

		// Bail without shortcode.
		if ( empty( $post ) || ! is_object( $post ) | ! has_shortcode( $post->post_content, 'citepro' ) ) {
			return;
		}

		// Check for our killswitch flag.
		if ( false === apply_filters( 'citepro_load_css', '__return_true' ) ) {
			return;
		}

		// Set a file suffix structure based on whether or not we want a minified version.
		$file   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'citepro.front' : 'citepro.front.min';

		// Set a version for whether or not we're debugging.
		$vers   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : CITEPRO_VERS;

		// Load my CSS.
		wp_enqueue_style( 'citepro-front', CITEPRO_URL . 'lib/css/' . $file . '.css', array(), $vers, 'all' );
	}

	/**
	 * Parse through the content and if the shortcode(s) are found, add the list to the bottom numbered.
	 *
	 * @param  mixed $content  The stored content in the database.
	 *
	 * @return mixed $content  The modified content in the database.
	 */
	public function display( $content ) {

		// Bail without shortcode.
		if ( ! has_shortcode( $content, 'citepro' ) ) {
			return $content;
		}

		// Check for filter flag to disable showing on home page.
		if ( false === self::check_homepage_load() ) {
			return $content;
		}

		// Run our preg match to pull the content out.
		preg_match_all( '/\[citepro](.+?)\[\/citepro]/is', $content, $matches, PREG_PATTERN_ORDER );

		// Bail without any matches from our regex.
		if ( empty( $matches[1] ) ) {
			return $content;
		}

		// Pull out the matches.
		$cites = $matches[1];

		// Return if no citations present.
		if ( empty( $cites ) ) {
			return $content;
		}

		// Build my list.
		$list   = CitationPro_Helper::build_cite_list( $cites, get_the_ID() );

		// Return the list after the content.
		return $content . '<br>' . $list;
	}

	/**
	 * The actual shortcode function, which simply adds the numerical item based on the count.
	 *
	 * @param  array $atts     The shortcode attribute array.
	 * @param  mixed $content  The post content.
	 *
	 * @return HTML
	 */
	public function shortcode( $atts, $content = null ) {

		// Check for empty first.
		if ( empty( $content ) ) {
			return;
		}

		// Check for filter flag to disable showing on home page.
		if ( false === self::check_homepage_load() ) {
			return;
		}

		// Get my number count.
		$num    = self::$citecount++;

		// Send it back.
		return CitationPro_Helper::get_single_cite( $num, get_the_ID() );
	}

	/**
	 * Check to see if we want the home page loading items.
	 *
	 * @return boolean
	 */
	public static function check_homepage_load() {

		// If we aren't on the front page, bail right away.
		if ( ! is_front_page() ) {
			return true;
		}

		// Now return whatever we've passed via filter.
		return apply_filters( 'citepro_load_homepage', '__return_true' );
	}

	// End class.
}

// Call our class.
$CitationPro_Front = new CitationPro_Front();
$CitationPro_Front->init();
