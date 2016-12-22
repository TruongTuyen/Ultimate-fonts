<?php
/**
 * A collection of helper functions for fonts.
 * @package Ultimate Fonts
 * @author  Ultimate Fonts <info@wpultimatefonts.com>
 */

/**
 * Fonts class.
 * @package Ultimate Fonts
 * @author  Ultimate Fonts <info@wpultimatefonts.com>
 */
class Ultimate_Fonts_Fonts {
	/**
	 * @var object The reference to singleton instance of this class
	 */
	private static $instance;

	/**
	 * List of font sources.
	 * @var array
	 */
	public $sources;

	/**
	 * List of fonts.
	 * @var array
	 */
	public $fonts = array();

	/**
	 * Returns the singleton instance of this class.
	 *
	 * @return object The singleton instance.
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Parse font configuration file.
	 * Protected constructor to prevent creating a new instance of the singleton via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		$this->parse();
	}

	/**
	 * Parse all of fonts from the config file.
	 */
	protected function parse() {
		$sources = include Ultimate_Fonts::instance()->dir . 'inc/config.php';
		foreach ( $sources as $key => &$source ) {
			array_walk( $source['fonts'], array( $this, 'parse_font' ), $key );
			$this->fonts = array_merge( $this->fonts, $source['fonts'] );
		}

		$this->sources = $sources;
	}

	/**
	 * Parse font parameters.
	 *
	 * @param array $params Font parameters.
	 * @param string $family Font family.
	 * @param string $source Font source.
	 */
	public function parse_font( &$params, $family, $source ) {
		list( $styles, $fallback, $subset ) = explode( '|', $params . '||' );
		$subset = array_filter( explode( ',', $subset . ',' ) );

		$params = compact( 'family', 'styles', 'fallback', 'subset', 'source' );
	}
}