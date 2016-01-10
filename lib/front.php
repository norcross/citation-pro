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
		add_action( 'wp_enqueue_scripts',           array( $this, 'front_js'            )           );
		add_action( 'wp_enqueue_scripts',           array( $this, 'front_css'           )           );
		add_filter( 'the_content',                  array( $this, 'display'             )           );
		add_shortcode( 'citepro',                   array( $this, 'shortcode'           )           );
	}

	/**
	 * Load the JS related to scrolling fanciness.
	 *
	 * @return void
	 */
	public function front_js() {

		// Fetch global post object.
		global $post;

		// Bail without shortcode.
		if ( empty( $post ) || ! is_object( $post ) | ! has_shortcode( $post->post_content, 'citepro' ) ) {
			return;
		}

		// Check for our killswitch flag.
		if ( false === apply_filters( 'citepro_load_js', true ) ) {
			return;
		}

		// Load my JS.
		wp_enqueue_script( 'citepro-front', plugins_url( '/js/citepro.front.js', __FILE__ ) , array( 'jquery' ), CITEPRO_VER, true );
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

		// Fetch global post object.
		global $post;

		// Bail without shortcode.
		if ( empty( $post ) || ! is_object( $post ) | ! has_shortcode( $post->post_content, 'citepro' ) ) {
			return;
		}

		// Check for our killswitch flag.
		if ( false === apply_filters( 'citepro_load_css', true ) ) {
			return;
		}

		// Load my CSS.
		wp_enqueue_style( 'citepro-front', plugins_url( '/css/citepro.front.css', __FILE__ ), array(), CITEPRO_VER, 'all' );
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
		if ( is_front_page() && false === apply_filters( 'citepro_load_homepage', true ) ) {
			return $content;
		}

		// Run our preg match to pull the content out.
		preg_match_all( '/\[citepro](.+?)\[\/citepro]/is', $content, $matches, PREG_PATTERN_ORDER );

		// Pull out the matches.
		$cites = $matches[1];

		// Return if no citations present.
		if ( empty( $cites ) ) {
			return $content;
		}

		// Build my list.
		$list   = CitationPro_Helper::build_cite_list( $cites );

		// Return the list after the content.
		return $content . '<br>' . $list;
	}

	/**
	 * The actual shortcode function, which simply adds the numerical item based on the count.
	 *
	 * @param  array $atts     The shortcode attribute array.
	 * @param  mixed $content  The post content.
	 *
	 * @return [type]          [description]
	 */
	public function shortcode( $atts, $content = null ) {

		// Check for empty first.
		if ( empty( $content ) ) {
			return;
		}

		// Check for filter flag to disable showing on home page.
		if ( is_front_page() && false === apply_filters( 'citepro_load_homepage', true ) ) {
			return;
		}

		// Get my number count.
		$num    = self::$citecount++;

		// Build the markup.
		$cite   = '<sup class="citepro" data-cite-num="' . absint( $num ) . '">'. absint( $num ) .'</sup>';

		// Send it back.
		return $cite;
	}

	// End class.
}

// Call our class.
$CitationPro_Front = new CitationPro_Front();
$CitationPro_Front->init();
