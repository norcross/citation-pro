<?php

 // Start up the engine
class CitationPro_Helper {

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

	/**
	 * preset our allowed post types for content
	 * modification with filter
	 *
	 * @return array	post types
	 */
	static function types() {
		return apply_filters( 'citepro_post_types', array( 'post' ) );
	}

/// end class
}


// Instantiate our class
new CitationPro_Helper();
