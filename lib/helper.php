<?php

 // Start up the engine
class CitationPro_Helper {

    /**
     * build individual items of citations to be generated
     * at the bottom of the content
     *
     * @param  array  $cites        array of citation content
     * @return html   $display      the marked up list of all the citations
     */
    static function build_cite_list( $cites = array() ) {
        // start with an empty
        $display = '';
        // open the markup
        $display .= '<p class="citepro-block">';
        // start the counter
        $i = 1;
        // begin loop
        foreach ( $cites as $cite ):
            // markup for each item
            $display .= '<span class="citepro-text" rel="' . absint( $i ) . '">' . absint( $i ) . '. ' . esc_html( $cite ) . '</span>';
        // trigger counter
        $i++;
        // end loop
        endforeach;
        // close markup
        $display .= '</p>';
        // send it back decoded
        return html_entity_decode( $display );

    }

    /**
     * preset our allowed post types for content
     * modification with filter
     *
     * @return array    post types
     */
    static function types() {
        return apply_filters( 'citepro_post_types', array( 'post' ) );
    }

/// end class
}


// Instantiate our class
new CitationPro_Helper();
