<?php
/**
 * Bootstraps the plugin.
 *
 * @package   ImageAutoLabel
 * @copyright Copyright(c) 2018, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace ImageAutoLabel;

// Plugin Settings.
include __DIR__ . '/settings.php';

// Enqueue Assets.
include __DIR__ . '/assets.php';

// Register Proxy.
include __DIR__ . '/proxy.php';

// Register Caching.
include __DIR__ . '/cache.php';
