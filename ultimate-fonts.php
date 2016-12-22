<?php
/**
 * Plugin Name: Ultimate Fonts
 * Plugin URI: http://wpultimatefonts.com
 * Description: Easy customize fonts for your website.
 * Version: 1.0.3
 * Author: Ultimate Fonts
 * Author URI: http://http://wpultimatefonts.com
 * License: GPL2+
 * Text Domain: ultimate-fonts
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 * @package Ultimate Fonts
 * @author  Ultimate Fonts <info@wpultimatefonts.com>
 */
class Ultimate_Fonts {
	/**
	 * @var object The reference to singleton instance of this class
	 */
	private static $instance;

	/**
	 * Plugin dir path.
	 * @var string
	 */
	public $dir;

	/**
	 * Plugin dir URL.
	 * @var string
	 */
	public $url;

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
	 * Set plugin constants.
	 * Protected constructor to prevent creating a new instance of the singleton via the `new` operator from outside of this class.
	 */
	protected function __construct() {
		$this->dir = plugin_dir_path( __FILE__ );
		$this->url = plugin_dir_url( __FILE__ );
	}

	/**
	 * Initialize the plugin.
	 */
	public function init() {
		$this->set_default();

		// Helper classes.
		require_once $this->dir . 'inc/class-ultimate-fonts-fonts.php';
		require_once $this->dir . 'inc/class-ultimate-fonts-elements.php';

		// Customizer.
		require_once $this->dir . 'inc/class-ultimate-fonts-customizer.php';
		$customizer = new Ultimate_Fonts_Customizer;

		if ( ! is_admin() ) {
			// Output custom CSS
			require_once $this->dir . 'inc/class-ultimate-fonts-css.php';
			new Ultimate_Fonts_CSS( $customizer );
		} elseif ( ! $this->get_theme_support( 'no_settings' ) ) {
			// Register plugin settings page. Allow themes to disable settings with theme support.
			require_once $this->dir . 'inc/class-ultimate-fonts-settings.php';
			new Ultimate_Fonts_Settings;
		}
	}

	/**
	 * Set plugin default option.
	 */
	protected function set_default() {
		$option = get_option( 'ultimate-fonts' );
		if ( ! empty( $option ) ) {
			return;
		}
		$option = array(
			'elements' => array(
				array(
					'label'    => esc_html__( 'Body', 'ultimate-fonts' ),
					'selector' => 'body',
				),
				array(
					'label'    => esc_html__( 'Heading 1', 'ultimate-fonts' ),
					'selector' => 'h1',
				),
				array(
					'label'    => esc_html__( 'Heading 2', 'ultimate-fonts' ),
					'selector' => 'h2',
				),
				array(
					'label'    => esc_html__( 'Heading 3', 'ultimate-fonts' ),
					'selector' => 'h3',
				),
				array(
					'label'    => esc_html__( 'Heading 4', 'ultimate-fonts' ),
					'selector' => 'h4',
				),
				array(
					'label'    => esc_html__( 'Heading 5', 'ultimate-fonts' ),
					'selector' => 'h5',
				),
				array(
					'label'    => esc_html__( 'Heading 6', 'ultimate-fonts' ),
					'selector' => 'h6',
				),
			),
		);

		// Allow theme to setup the default elements via theme support.
		if ( $default_elements = $this->get_theme_support( 'default_elements' ) ) {
			$option['elements'] = $default_elements;
		}
		add_option( 'ultimate-fonts', $option );
	}

	/**
	 * Get theme support.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get_theme_support( $name ) {
		$theme_support = get_theme_support( 'ultimate-fonts' );
		if ( ! $theme_support || empty( $theme_support[0] ) || empty( $theme_support[0][ $name ] ) ) {
			return false;
		}

		return $theme_support[0][ $name ];
	}
}

add_action( 'init', array( Ultimate_Fonts::instance(), 'init' ) );