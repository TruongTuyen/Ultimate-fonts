<?php
/**
 * Create settings page for fonts configuration.
 * @package Ultimate Fonts
 * @author  Ultimate Fonts <info@wpultimatefonts.com>
 */

/**
 * Settings page class.
 * @package Ultimate Fonts
 * @author  Ultimate Fonts <info@wpultimatefonts.com>
 */
class Ultimate_Fonts_Settings {
	/**
	 * Add hooks to create settings page and register settings.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add plugin settings menu.
	 */
	public function add_menu() {
		$page = add_menu_page(
			esc_html__( 'Ultimate Fonts', 'ultimate-fonts' ),
			esc_html__( 'Ultimate Fonts', 'ultimate-fonts' ),
			'manage_options',
			'ultimate-fonts',
			array( $this, 'render_page' ),
			'dashicons-editor-textcolor'
		);
		add_action( "admin_print_styles-$page", array( $this, 'enqueue' ) );
	}

	/**
	 * Render settings page.
	 */
	public function render_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Ultimate Fonts', 'ultimate-fonts' ); ?></h1>
			<p><?php esc_html_e( 'Please add and configure elements that you want to change fonts for.', 'ultimate-fonts' ); ?></p>
			<p><?php echo wp_kses_post( sprintf( __( 'After saving, please <a href="%s">go to the Customizer</a> to change the font settings and preview them in real-time.', 'ultimate-fonts' ), esc_url( admin_url( 'customize.php' ) ) ) ); ?></p>
			<form method="POST" action="options.php">
				<?php
				settings_fields( 'ultimate-fonts' );
				do_settings_sections( 'ultimate-fonts' );
				?>
				<p class="submit">
					<?php submit_button( esc_html__( 'Save Changes', 'ultimate-fonts' ), 'primary', 'submit', false ); ?>
					<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button"><?php esc_html_e( 'Customize', 'ultimate-fonts' ); ?></a>
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts and styles for the settings page.
	 */
	public function enqueue() {
		wp_enqueue_style( 'ultimate-fonts-settings', Ultimate_Fonts::instance()->url . 'css/settings.css' );
		wp_enqueue_script( 'ultimate-fonts-settings', Ultimate_Fonts::instance()->url . 'js/settings.js', array(
			'jquery',
			'wp-util',
			'backbone',
		), '1.0.0', true );
		wp_localize_script( 'ultimate-fonts-settings', 'Ultimate_Fonts', get_option( 'ultimate-fonts' ) );
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( 'ultimate-fonts', 'ultimate-fonts', array( $this, 'sanitize' ) );
		add_settings_section(
			'default',
			'',
			'',
			'ultimate-fonts'
		);
		add_settings_field(
			'elements',
			esc_html__( 'Elements', 'ultimate-fonts' ),
			array( $this, 'render_elements' ),
			'ultimate-fonts'
		);
	}

	/**
	 * Sanitize options. Save all elements as un no-associate array.
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function sanitize( $options ) {
		$options['elements'] = isset( $options['elements'] ) && is_array( $options['elements'] ) ? array_values( $options['elements'] ) : array();

		return $options;
	}

	/**
	 * Render elements field.
	 */
	public function render_elements() {
		?>
		<div id="ultimate-fonts-elements">
			<a href="javascript:;" id="ultimate-fonts-add" class="button"><?php esc_html_e( '+ Add Element', 'ultimate-fonts' ); ?></a>
		</div>
		<script type="text/template" id="tmpl-ultimate-fonts-element">
			<label class="ultimate-fonts-element__label">
				<span class="ultimate-fonts-element__title"><?php esc_html_e( 'Label' ); ?></span>
				<input type="text" name="<?php echo esc_attr( "ultimate-fonts[elements][{{ data.index }}][label]" ); ?>" value="{{ data.label }}">
				<small class="description"><?php esc_html_e( 'The element label displayed in the Customizer.', 'ultimate-fonts' ); ?></small>
			</label>
			<label class="ultimate-fonts-element__selector">
				<span class="ultimate-fonts-element__title"><?php esc_html_e( 'Selector' ); ?></span>
				<input type="text" class="regular-text" name="<?php echo esc_attr( "ultimate-fonts[elements][{{ data.index }}][selector]" ); ?>" value="{{ data.selector }}">
				<small class="description"><?php esc_html_e( 'Separate multiple selectors with commas.', 'ultimate-fonts' ); ?></small>
			</label>
			<a href="javascript:;" class="ultimate-fonts-element__delete" title="<?php esc_attr_e( 'Remove element', 'ultimate-fonts' ); ?>"><i class="dashicons dashicons-minus"></i></a>
		</script>
		<?php
	}
}
