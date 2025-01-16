<?php
/**
 * The main plugin function
 *
 * @package wp-liveblog
 */

namespace Alley\WP\WP_Liveblog;

use Alley\WP\Features\Group;

/**
 * Instantiate the plugin.
 */
function main(): void {
	// Add features here.
	$plugin = new Group();

	$plugin->boot();
}
