<?php
/**
 * Plugin Name: Citation Pro
 * Plugin URI: http://andrewnorcross.com/plugins/
 * Description: Built in method for easiliy citing your content.
 * Author: Andrew Norcross
 * Author URI: http://andrewnorcross.com/
 * Version: 0.0.2
 * Text Domain: citation-pro
 * Requires WP: 4.0
 * Domain Path: languages
 * GitHub Plugin URI: https://github.com/norcross/citation-pro
 */

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Andrew Norcross
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

if ( ! defined( 'CITEPRO_BASE ' ) ) {
	define( 'CITEPRO_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'CITEPRO_DIR' ) ) {
	define( 'CITEPRO_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CITEPRO_VER' ) ) {
	define( 'CITEPRO_VER', '0.0.2' );
}

/**
 * Call our class.
 */
class CitationPro_Base
{
	/**
	 * Static property to hold our singleton instance.
	 * @var $instance
	 */
	static $instance = false;

	/**
	 * This is our constructor. There are many like it, but this one is mine.
	 */
	private function __construct() {
		add_action( 'plugins_loaded',               array( $this, 'textdomain'          )           );
		add_action( 'plugins_loaded',               array( $this, 'load_files'          )           );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return $instance
	 */
	public static function getInstance() {

		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load our textdomain for localization.
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'citation-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Load our actual files in the places they belong.
	 *
	 * @return void
	 */
	public function load_files() {

		// Load our front-end display functions.
		if ( ! is_admin() ) {
			require_once( CITEPRO_DIR . 'lib/front.php' );
		}

		// Load our admin-related functions.
		if ( is_admin() ) {
			require_once( CITEPRO_DIR . 'lib/admin.php' );
		}

		// Load our helper file which is used in both.
		require_once( CITEPRO_DIR . 'lib/helper.php' );
	}

	// End our class.
}

// Instantiate our class.
$CitationPro_Base = CitationPro_Base::getInstance();
