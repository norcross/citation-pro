<?php

 // Start up the engine
class CitationPro_Front {

	/**
	 * Static property to start the cite count
	 * @var CitationPro
	 */

	static $citecount = 1;

	/**
	 * This is our constructor. There are many like it, but this one is mine.
	 *
	 * @return
	 */

	public function __construct() {
		add_action		(	'wp_enqueue_scripts',					array(	$this,	'citation_front_scripts'	)			);
		add_filter		(	'the_content',							array(	$this,	'citation_display'			)			);
		add_shortcode	(	'citepro',								array(	$this,	'citation_shortcode'		)			);

	}

	/**
	 * [front_scripts description]
	 * @return [type] [description]
	 */
	public function citation_front_scripts() {
		// fetch global post object
		global $post;

		// bail without shortcode
		if ( ! has_shortcode( $post->post_content, 'citepro' ) ) {
			return;
		}

		// load my CSS and HS
		wp_enqueue_style( 'citepro-front', plugins_url( '/css/citepro.front.css', __FILE__ ), array(), null, 'all' );
		wp_enqueue_script( 'citepro-front', plugins_url( '/js/citepro.front.js', __FILE__ ) , array( 'jquery' ), null, true );
		wp_localize_script( 'citepro-front', 'citeproFront', array(
			'citeSpeed'		=> apply_filters( 'citepro_scroll_speed', 750 ),
			'citeOffset'	=> apply_filters( 'citepro_scroll_offset', 40 )
		));
	}

	/**
	 * [citation_shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function citation_shortcode( $atts, $content = null ) {

		// check for empty first
		if ( empty ( $content ) ) {
			return;
		}
		// get my number count
		$num = self::$citecount++;

		// build the markup
		$cite	= '<sup class="citepro" data-citenum="' . absint( $num ) . '"> '. absint( $num ) . ' </sup>';

		// send it back
		return $cite;

	}

	/**
	 * parse through the content and if the shortcode(s) are found,
	 * add the list to the bottom numbered
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function citation_display( $content ) {

		preg_match_all( '/\[citepro](.+?)\[\/citepro]/is', $content, $matches, PREG_PATTERN_ORDER );

		$cites = $matches[1];

		// return if no citations present
		if ( empty( $cites ) ) {
			return $content;
		}

		// build my list
		$display	= CitationPro_Helper::build_cite_list( $cites );

		return $content.'<br />'.$display;

	}

	/**
	 * [build_cite_list description]
	 * @param  array  $cites [description]
	 * @return [type]        [description]
	 */
	static function build_cite_list( $cites = array() ) {

		$display = '';

		$display .= '<p class="citepro-block">';

		$i = 1;
		foreach ( $cites as $cite ):

			$display .= '<span class="citepro-text" rel="' . absint( $i ) . '">[' . absint( $i ) . '] ' . esc_attr( $cite ) . '</span>';

		$i++;
		endforeach;

		$display .= '</p>';

		// send it back
		return $display;

	}

/// end class
}


// Instantiate our class
new CitationPro_Front();
