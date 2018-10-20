<?php
/**
 * Enqueue our assets.
 *
 * @package   ImageAutoLabel
 * @copyright Copyright(c) 2018, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace ImageAutoLabel;

const CACHE_GROUP = 'google_vision_proxy';
const CACHE_TTL   = MONTH_IN_SECONDS;

add_filter( API_BASE . '_proxy_result_pre', '\ImageAutoLabel\get_results', 10, 3 );
add_action( API_BASE . '_proxy_response_received', '\ImageAutoLabel\cache_results', 10, 3 );

/**
 * Fetch results from cache if $results are empty.
 *
 * @param mixed            $result  Proxy results (This should be empty unless results are provided by extension).
 * @param string           $route   The route requested.
 * @param \WP_REST_Request $request The REST Request.
 *
 * @return bool|mixed
 */
function get_results( $result, $route, \WP_REST_Request $request ) {
	if ( empty( $result ) ) {
		$hash = md5( $request->get_body() );
		// Use external object cache if available.
		// @see `cache_results` method.
		if ( wp_using_ext_object_cache() ) {
			$result = wp_cache_get( $hash, CACHE_GROUP );
		} else {
			$result = get_transient( $hash . CACHE_GROUP );
		}
	}
	return $result;
}

/**
 * Caches responses from the Google Vision API.
 *
 * @param mixed            $result  Results returned by the Google Vision API.
 * @param string           $route   The route requested.
 * @param \WP_REST_Request $request The REST Request.
 *
 * @return void
 */
function cache_results( $result, $route, \WP_REST_Request $request ) {

	$hash = md5( $request->get_body() );
	/**
		 * Note: We are explicitly distinguishing object cache from transients even though
		 * transients use object cache when an external cache is available.
		 * The reason is because an external cache LRU handles expiration in an optimised fashion
		 * where as the WordPress option table does not. So we have to set an expiration to avoid
		 * lingering entries and filling the options table infinitely.
		 *
		 * @todo Flushing the cache via webhooks will eliminate this problem.
		 */
	if ( wp_using_ext_object_cache() ) {
		wp_cache_set( $hash, $result, CACHE_GROUP );
	} else {
		// The expiration is to ensure transient doesn't stick around forever since no LRU flushing like with external object cache.
		set_transient( $hash . CACHE_GROUP, $result, CACHE_TTL );
	}
}
