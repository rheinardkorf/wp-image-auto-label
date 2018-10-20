<?php
/**
 * Enqueue our assets.
 *
 * @package   ImageAutoLabel
 * @copyright Copyright(c) 2018, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace ImageAutoLabel;

add_action( 'wp_enqueue_scripts', '\ImageAutoLabel\load_scripts' );

/**
 * Load Javascript.
 *
 * @return void
 */
function load_scripts() {
	$js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . '../assets/script.js' ) );
	wp_enqueue_script( 'image_auto_labels_js', plugins_url( '../assets/script.js', __FILE__ ), array( 'wp-api' ), $js_ver );
	wp_localize_script( 'image_auto_labels_js', 'imageAutoLabels', array(
		'nonce' => wp_create_nonce( 'wp_rest' ),
	) );
}
