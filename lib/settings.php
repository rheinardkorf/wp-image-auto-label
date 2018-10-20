<?php
/**
 * Register plugin settings.
 *
 * @package   ImageAutoLabel
 * @copyright Copyright(c) 2018, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace ImageAutoLabel;

add_action( 'admin_menu', '\ImageAutoLabel\admin_menu' );
add_action( 'admin_init', '\ImageAutoLabel\init_settings' );

/**
 * Register settings pages.
 *
 * @return void
 */
function admin_menu() {
	add_menu_page(
		__( 'Image Auto Label', 'image-auto-label' ),
		__( 'Image Auto Label', 'image-auto-label' ),
		'manage_options',
		'image-auto-label',
		'\ImageAutoLabel\admin_menu_page',
		'',
		100
	);
}

/**
 * Settings Page.
 *
 * @return void
 */
function admin_menu_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	include IMAGE_AUTO_LABEL_DIR . 'template/settings-page.php';
}

/**
 * Init settings.
 *
 * @return void
 */
function init_settings() {

	if ( ! get_option( 'image_auto_label_settings' ) ) {
		add_option( 'image_auto_label_settings' );
	}

	add_settings_section(
		'image-auto-label-settings-section',
		__( 'General Settings', 'image-auto-label' ),
		'\ImageAutoLabel\settings_header',
		'image-auto-label'
	);

	add_settings_field(
		'image_auto_label_api_key',
		__( 'Google Vision API Key', 'wpplugin' ),
		'\ImageAutoLabel\image_auto_label_api_key_callback',
		'image-auto-label',
		'image-auto-label-settings-section'
	);

	register_setting(
		'image_auto_label_settings',
		'image_auto_label_settings'
	);
}

/**
 * Settings header.
 *
 * @return void
 */
function settings_header() {
	esc_html_e( 'Add settings as required.', 'image-auto-label' );
}

/**
 * API key field.
 *
 * @return void
 */
function image_auto_label_api_key_callback() {

	$options = get_option( 'image_auto_label_settings' );

	$api_key = '';
	if ( isset( $options['image_auto_label_api_key'] ) ) {
		$api_key = esc_html( $options['image_auto_label_api_key'] );
	}
	echo '<input type="password" id="image_auto_label_api_key" name="image_auto_label_settings[image_auto_label_api_key]" value="' . $api_key . '" />';
}

