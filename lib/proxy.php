<?php
/**
 * Add proxy hooks.
 *
 * @package   ImageAutoLabel
 * @copyright Copyright(c) 2018, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace ImageAutoLabel;

const API_BASE = 'vision/v1';

add_filter( 'wp_middleware_proxy', '\ImageAutoLabel\add_proxy' );
add_filter( API_BASE . '_proxy_response_override', '\ImageAutoLabel\response_override', 10, 2 );
add_filter( API_BASE . '_proxy_request', '\ImageAutoLabel\append_api_key' );

/**
 * Add proxy to middleware.
 *
 * @param Array(type) $proxies A list of proxies.
 * @return Array(type)
 */
function add_proxy( $proxies ) {

	$proxy = array(
		'namespace'     => API_BASE,
		'api_host'      => 'https://vision.googleapis.com/v1',
		'valid_methods' => array( 'POST' ),
	);

	$proxies[] = $proxy;

	return $proxies;
}

/**
 * Only allow local requests (or requests that provide their own key).
 *
 * @param [type]           $response Empty or already overridden response.
 * @param \WP_REST_Request $request The original request.
 * @return [type]
 */
function response_override( $response, $request ) {

	// We wan't requests to originate from the site so we will use nonces.
	$nonce = $request->get_header( 'X-WP-NONCE' );

	if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		return new \WP_Error( 'offsite-request', __( 'Only requests from site expected.', 'image-auto-label' ) );
	}

	return $response;
}

/**
 * Append the API key to the request.
 *
 * @param [type] $request Original request.
 * @return \WP_REST_Request
 */
function append_api_key( $request ) {
	$options = get_option( 'image_auto_label_settings' );
	$api_key = isset( $options['image_auto_label_api_key'] ) ? $options['image_auto_label_api_key'] : '';
	$route   = $request->get_route();
	$route   = add_query_arg( array( 'key' => $api_key ), $route );
	$request->set_route( $route );
	return $request;
}
