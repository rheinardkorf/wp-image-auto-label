<?php
/**
 * Image Auto Label
 *
 * @package   ImageAutoLabel
 * @copyright Copyright(c) 2018, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 *
 * Plugin Name: Image Auto Label
 * Plugin URI: https://github.com/rheinardkorf/wp-image-auto-label
 * Description: Auto labels images using Google Vision API. Reference implementation for WP Middleware.
 * Version: 0.1
 * Author: Rheinard Korf
 * Author URI: https://github.com/rheinardkorf
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: image-auto-label
 * Domain Path: /languages
 */

if ( version_compare( phpversion(), '5.3', '>=' ) ) {

	define( 'IMAGE_AUTO_LABEL_URL', plugin_dir_url( __FILE__ ) );
	define( 'IMAGE_AUTO_LABEL_DIR', plugin_dir_path( __FILE__ ) );

	// Bootstrap the plugin.
	include __DIR__ . '/lib/bootstrap.php';
} else {
	if ( defined( 'WP_CLI' ) ) {
		WP_CLI::warning( rheinardkorf_image_auto_label_php_version_text() );
	} else {
		add_action( 'admin_notices', 'rheinardkorf_image_auto_label_php_version_error' );
	}
}

/**
 * Admin notice for incompatible versions of PHP.
 */
function rheinardkorf_image_auto_label_php_version_error() {
	printf( '<div class="error"><p>%s</p></div>', esc_html( rheinardkorf_image_auto_label_php_version_text() ) );
}

/**
 * String describing the minimum PHP version.
 *
 * @return string
 */
function rheinardkorf_image_auto_label_php_version_text() {
	return __( 'WP Middleware plugin error: Your version of PHP is too old to run this plugin. You must be running PHP 5.3 or higher.', 'image-auto-label' );
}
