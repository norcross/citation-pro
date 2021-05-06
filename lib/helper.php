<?php
/**
 * Citation Pro - Helper Module
 *
 * Contains various helper functions used in various places.
 *
 * @package Citation Pro
 */

/**
 * Start our engines.
 */
class CitationPro_Helper {

	/**
	 * Build the markup for a single inline citation.
	 *
	 * @param  integer $number   Which citation we are on.
	 * @param  integer $post_id  The post ID we are on (to get links and whatnot).
	 *
	 * @return HTML
	 */
	public static function get_single_cite( $number = 1, $post_id = 0 ) {

		// Bail without a post ID.
		if ( empty( $post_id ) ) {
			return;
		}

		// Get my class so I know where to apply.
		$sclass = apply_filters( 'citepro_markup_span_class', 'citepro-text' );

		// Get my link.
		$link   = get_permalink( $post_id ) . '#' . esc_attr( $sclass ) . '-' . absint( $number );

		// And build my text.
		$text   = '<a class="citepro-link citepro-inline-link" data-cite-num="' . absint( $number ) . '" href="' . esc_url( $link ) . '">'. absint( $number ) .'</a>';

		// Build the markup and return it.
		return '<sup id="citepro-inline-' . absint( $number ) . '" class="citepro" data-cite-num="' . absint( $number ) . '">'. $text .'</sup>';
	}

	/**
	 * Build individual items of citations to be generated at the bottom of the content.
	 *
	 * @param  array   $cites    Array of citation content.
	 * @param  integer $post_id  The post ID we are on (to get links and whatnot).
	 *
	 * @return html    $display  The marked up list of all the citations.
	 */
	public static function build_cite_list( $cites = array(), $post_id = 0 ) {

		// Bail if no post ID or citations were passed.
		if ( empty( $post_id ) || empty( $cites ) || ! is_array( $cites ) ) {
			return;
		}

		// Set the class for the paragraph.
		$pclass = apply_filters( 'citepro_markup_paragraph_class', 'citepro-block' );

		// Start with an empty.
		$build  = '';

		// Open the markup.
		$build .= '<p class="' . esc_attr( $pclass ) . '">';

		// Start the counter.
		$i = 1;

		// Loop through the citations.
		foreach ( $cites as $cite ) {

			// Get my link.
			$link   = get_permalink( $post_id ) . '#citepro-inline-' . absint( $i );

			// Set my text and link.
			$intro  = '<a class="citepro-link citepro-list-link" rel="' . absint( $i ) . '" href="' . esc_url( $link ) . '">'. absint( $i ) . '</a>';

			// Set my return back arrow.
			$arrow  = '<a class="citepro-link citepro-arrow-link" rel="' . absint( $i ) . '" href="' . esc_url( $link ) . '">&uarr;</a>';

			// Set the class for each individual item class.
			$sclass = apply_filters( 'citepro_markup_span_class', 'citepro-text' );

			// Markup for each item.
			$build .= '<span id="' . esc_attr( $sclass ) . '-' . absint( $i ) . '" class="' . esc_attr( $sclass ) . '">';
				$build .= $intro . '. ' . esc_html( $cite ) . ' ' . $arrow;
			$build .= '</span>';

			// Increment the counter.
			$i++;
		}

		// Close markup.
		$build .= '</p>';

		// Run it through a filter and return.
		return apply_filters( 'citepro_markup_display', html_entity_decode( $build ) );
	}

	/**
	 * Preset our allowed post types for content modification with filter.
	 *
	 * @return array $types  The post types we are using.
	 */
	public static function types() {
		return apply_filters( 'citepro_post_types', array( 'post' ) );
	}

	// End class.
}

// Load the class.
new CitationPro_Helper();
