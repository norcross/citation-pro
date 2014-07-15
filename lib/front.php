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
        add_action      (   'wp_enqueue_scripts',               array(  $this,  'citation_front_js'         )           );
        add_action      (   'wp_enqueue_scripts',               array(  $this,  'citation_front_css'        )           );
        add_filter      (   'the_content',                      array(  $this,  'citation_display'          )           );
        add_shortcode   (   'citepro',                          array(  $this,  'citation_shortcode'        )           );
    }

    /**
     * load the JS related to scrolling fanciness
     *
     * @return [type] [description]
     */
    public function citation_front_js() {
        // fetch global post object
        global $post;

        // bail without shortcode
        if ( ! has_shortcode( $post->post_content, 'citepro' ) ) {
            return;
        }

        // check for our killswitch flag
        if ( false === apply_filters( 'citepro_load_js', true ) ) {
            return;
        }

        // load my JS
        wp_enqueue_script( 'citepro-front', plugins_url( '/js/citepro.front.js', __FILE__ ) , array( 'jquery' ), CITEPRO_VER, true );
        wp_localize_script( 'citepro-front', 'citeproFront', array(
            'citeSpeed'     => apply_filters( 'citepro_scroll_speed', 750 ),
            'citeOffset'    => apply_filters( 'citepro_scroll_offset', 40 )
        ));

    }

    /**
     * load the front end CSS related to the shortcode
     * and generated list
     *
     * @return [type] [description]
     */
    public function citation_front_css() {
        // fetch global post object
        global $post;

        // bail without shortcode
        if ( ! has_shortcode( $post->post_content, 'citepro' ) ) {
            return;
        }

        // check for our killswitch flag
        if ( false === apply_filters( 'citepro_load_css', true ) ) {
            return;
        }

        // load my CSS
        wp_enqueue_style( 'citepro-front', plugins_url( '/css/citepro.front.css', __FILE__ ), array(), CITEPRO_VER, 'all' );

    }

    /**
     * parse through the content and if the shortcode(s) are found,
     * add the list to the bottom numbered
     *
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    public function citation_display( $content ) {

        // bail without shortcode
        if ( ! has_shortcode( $content, 'citepro' ) ) {
            return $content;
        }

        // run our preg match to pull the content out
        preg_match_all( '/\[citepro](.+?)\[\/citepro]/is', $content, $matches, PREG_PATTERN_ORDER );

        // pull out the matches
        $cites = $matches[1];

        // return if no citations present
        if ( empty( $cites ) ) {
            return $content;
        }

        // build my list
        $display    = CitationPro_Helper::build_cite_list( $cites );

        return $content . '<br />' . $display;

    }

    /**
     * the actual shortcode function, which simply adds the numerical
     * item based on the count
     *
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
        $cite   = '<sup class="citepro" data-num="' . absint( $num ) . '">'. absint( $num ) .'</sup>';

        // send it back
        return $cite;

    }

/// end class
}


// Instantiate our class
new CitationPro_Front();
