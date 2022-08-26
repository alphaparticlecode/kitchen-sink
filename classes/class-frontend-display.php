<?php

class Frontend_Display {
	/**
	 * Static property to hold our singleton instance
	 */
	public static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return void
	 */
	private function __construct() {
		add_filter( 'the_content', [ $this, 'filter_html_special_chars' ], 99 );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return Frontend_Display
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Handle any special characters that got turned into entities in the DB
	 */
	public function filter_html_special_chars( $content ) {
		if( ! is_page( 'kitchen-sink' ) ) {
			return $content;
		}

		$content = str_replace( 'u002d\\u002d', '--', $content );
		$content = str_replace( 'u003c', '<', $content );
		$content = str_replace( 'u003e', '>', $content );
		$content = str_replace( 'u0026', '&', $content );
		$content = str_replace( 'u0022', '"', $content );
		$content = str_replace( '&#8230;', '...', $content );
		$content = str_replace( '>rn<', '', $content );

		return $content;
	}
}