<?php
/**
 * Change fonts in the Customizer.
 * @package Ultimate Fonts
 * @author  Ultimate Fonts <info@wpultimatefonts.com>
 */

/**
 * Customizer class.
 * @package Ultimate Fonts
 * @author  Ultimate Fonts <info@wpultimatefonts.com>
 */
class Ultimate_Fonts_Customizer {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Register typography settings in the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 */
	public function register( $wp_customize ) {
		if ( empty( Ultimate_Fonts_Elements::instance()->elements ) ) {
			return;
		}

		// Custom control for font family.
		require_once Ultimate_Fonts::instance()->dir . 'inc/class-ultimate-fonts-font-family-control.php';

		// Typography section.
		$wp_customize->add_panel( 'ultimate-fonts', array(
			'title' => esc_html__( 'Fonts', 'ultimate-fonts' ),
		) );

		foreach ( Ultimate_Fonts_Elements::instance()->elements as $element ) {
			$key = isset( $element['id'] ) ? $element['id'] : sanitize_title( $element['label'] );

			// Section
			$wp_customize->add_section( $key . '_font', array(
				'title' => $element['label'],
				'panel' => 'ultimate-fonts',
			) );

			// Font family
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_font_family]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( new Ultimate_Fonts_Font_Family_Control(
				$wp_customize,
				"ultimate_fonts_customize[{$key}_font_family]",
				array(
					'label'   => esc_html__( 'Font family', 'ultimate-fonts' ),
					'section' => $key . '_font',
					'panel'   => 'ultimate-fonts',
				)
			) );

			// Font style
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_font_style]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "ultimate_fonts_customize[{$key}_font_style]", array(
				'label'   => esc_html__( 'Font style', 'ultimate-fonts' ),
				'type'    => 'select',
				'choices' => array(
					''          => esc_html__( '- No change -', 'ultimate-fonts' ),
					'100'       => esc_html__( 'Thin 100', 'ultimate-fonts' ),
					'100italic' => esc_html__( 'Thin 100 Italic', 'ultimate-fonts' ),
					'200'       => esc_html__( 'Extra-Light 200', 'ultimate-fonts' ),
					'200italic' => esc_html__( 'Extra-Light 200 Italic', 'ultimate-fonts' ),
					'300'       => esc_html__( 'Light 300', 'ultimate-fonts' ),
					'300italic' => esc_html__( 'Light 300 Italic', 'ultimate-fonts' ),
					'400'       => esc_html__( 'Normal 400', 'ultimate-fonts' ),
					'400italic' => esc_html__( 'Normal 400 Italic', 'ultimate-fonts' ),
					'500'       => esc_html__( 'Medium 500', 'ultimate-fonts' ),
					'500italic' => esc_html__( 'Medium 500 Italic', 'ultimate-fonts' ),
					'600'       => esc_html__( 'Semi-Bold 600', 'ultimate-fonts' ),
					'600italic' => esc_html__( 'Semi-Bold 600 Italic', 'ultimate-fonts' ),
					'700'       => esc_html__( 'Bold 700', 'ultimate-fonts' ),
					'700italic' => esc_html__( 'Bold 700 Italic', 'ultimate-fonts' ),
					'800'       => esc_html__( 'Extra-Bold 800', 'ultimate-fonts' ),
					'800italic' => esc_html__( 'Extra-Bold 800 Italic', 'ultimate-fonts' ),
					'900'       => esc_html__( 'Ultra-Bold 900', 'ultimate-fonts' ),
					'900italic' => esc_html__( 'Ultra-Bold 900 Italic', 'ultimate-fonts' ),
				),
				'section' => $key . '_font',
				'panel'   => 'ultimate-fonts',
			) );

			// Font size
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_font_size]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "ultimate_fonts_customize[{$key}_font_size]", array(
				'label'      => esc_html__( 'Font size', 'ultimate-fonts' ),
				'type'       => 'number',
				'section'    => $key . '_font',
				'panel'      => 'ultimate-fonts',
				'input_atts' => array(
					'step' => 'any',
				),
			) );
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_font_size_unit]", array(
				'type'              => 'option',
				'capability'        => 'manage_options',
				'default'           => 'px',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			) );
			$wp_customize->add_control( "ultimate_fonts_customize[{$key}_font_size_unit]", array(
				'type'    => 'select',
				'choices' => array(
					'px'  => esc_html__( 'px', 'ultimate-fonts' ),
					'em'  => esc_html__( 'em', 'ultimate-fonts' ),
					'rem' => esc_html__( 'rem', 'ultimate-fonts' ),
					'%'   => esc_html__( '%', 'ultimate-fonts' ),
				),
				'section' => $key . '_font',
				'panel'   => 'ultimate-fonts',
			) );

			// Line height
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_line_height]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "ultimate_fonts_customize[{$key}_line_height]", array(
				'label'      => esc_html__( 'Line height', 'ultimate-fonts' ),
				'type'       => 'number',
				'section'    => $key . '_font',
				'panel'      => 'ultimate-fonts',
				'input_atts' => array(
					'step' => 'any',
				),
			) );
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_line_height_unit]", array(
				'default'           => '',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			) );
			$wp_customize->add_control( "ultimate_fonts_customize[{$key}_line_height_unit]", array(
				'type'    => 'select',
				'choices' => array(
					''    => esc_html__( '-', 'ultimate-fonts' ),
					'px'  => esc_html__( 'px', 'ultimate-fonts' ),
					'em'  => esc_html__( 'em', 'ultimate-fonts' ),
					'rem' => esc_html__( 'rem', 'ultimate-fonts' ),
					'%'   => esc_html__( '%', 'ultimate-fonts' ),
				),
				'section' => $key . '_font',
				'panel'   => 'ultimate-fonts',
			) );

			// Letter spacing
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_letter_spacing]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "ultimate_fonts_customize[{$key}_letter_spacing]", array(
				'label'      => esc_html__( 'Letter spacing', 'ultimate-fonts' ),
				'type'       => 'number',
				'section'    => $key . '_font',
				'panel'      => 'ultimate-fonts',
				'input_atts' => array(
					'step' => 'any',
				),
			) );
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_letter_spacing_unit]", array(
				'type'              => 'option',
				'capability'        => 'manage_options',
				'default'           => 'px',
				'sanitize_callback' => array( $this, 'sanitize_select' ),
			) );
			$wp_customize->add_control( "ultimate_fonts_customize[{$key}_letter_spacing_unit]", array(
				'type'    => 'select',
				'choices' => array(
					'px'  => esc_html__( 'px', 'ultimate-fonts' ),
					'em'  => esc_html__( 'em', 'ultimate-fonts' ),
					'rem' => esc_html__( 'rem', 'ultimate-fonts' ),
					'%'   => esc_html__( '%', 'ultimate-fonts' ),
				),
				'section' => $key . '_font',
				'panel'   => 'ultimate-fonts',
			) );

			// Text transform
			$wp_customize->add_setting( "ultimate_fonts_customize[{$key}_text_transform]", array(
				'type'       => 'option',
				'capability' => 'manage_options',
			) );
			$wp_customize->add_control( "ultimate_fonts_customize[{$key}_text_transform]", array(
				'label'   => esc_html__( 'Text transform', 'ultimate-fonts' ),
				'type'    => 'select',
				'choices' => array(
					''           => esc_html__( '- No change -', 'ultimate-fonts' ),
					'normal'     => esc_html__( 'None', 'ultimate-fonts' ),
					'lowercase'  => esc_html__( 'lowercase', 'ultimate-fonts' ),
					'uppercase'  => esc_html__( 'UPPERCASE', 'ultimate-fonts' ),
					'capitalize' => esc_html__( 'Capitalize', 'ultimate-fonts' ),
				),
				'section' => $key . '_font',
				'panel'   => 'ultimate-fonts',
			) );
		}
	}

	/**
	 * Select sanitization callback.
	 *
	 * @param string $input Slug to sanitize.
	 * @param WP_Customize_Setting $setting Setting instance.
	 *
	 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
	 */
	public function sanitize_select( $input, $setting ) {
		$choices = $setting->manager->get_control( $setting->id )->choices;

		return isset( $choices[ $input ] ) ? $input : $setting->default;
	}

	/**
	 * Enqueue scripts and styles for the customizer.
	 */
	public function enqueue() {
		wp_enqueue_style( 'ultimate-fonts-customizer', Ultimate_Fonts::instance()->url . 'css/customizer.css' );
		wp_enqueue_script( 'ultimate-fonts-customizer', Ultimate_Fonts::instance()->url . 'js/customizer.js', array( 'jquery' ), '1.0.0', true );
	}
}