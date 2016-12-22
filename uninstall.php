<?php
/**
 * Uninstall the plugin.
 * Remove plugin options.
 *
 * @package Ultimate Fonts
 * @author  Ultimate Fonts <info@wpultimatefonts.com>
 */

// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'ultimate_fonts' );
delete_option( 'ultimate_fonts_customize' );
