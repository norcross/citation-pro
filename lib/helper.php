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
	 * Build individual items of citations to be generated at the bottom of the content.
	 *
	 * @param  array $cites    Array of citation content.
	 *
	 * @return html  $display  The marked up list of all the citations.
	 */
	public static function build_cite_list( $cites = array() ) {

		// Bail if no citations were passed.
		if ( empty( $cites ) || ! is_array( $cites ) ) {
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

			// Set the class for each individual item class.
			$sclass = apply_filters( 'citepro_markup_span_class', 'citepro-text' );

			// Markup for each item.
			$build .= '<span class="' . esc_attr( $sclass ) . '" rel="' . absint( $i ) . '">';
			$build .= absint( $i ) . '. ' . esc_html( $cite );
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
