<?php
/**
 * The main plugin function
 *
 * @package wp-liveblog
 */

namespace Alley\WP\WP_Liveblog;

use Alley\WP\Features\Group;
use Alley\WP\WP_Liveblog\Features\WP_Liveblog_Post_Type_Liveblog;

/**
 * Instantiate the plugin.
 */
function main(): void {
	// Add features here.
	$plugin = new Group(
		new WP_Liveblog_Post_Type_Liveblog(),
	);

	$plugin->boot();
}
