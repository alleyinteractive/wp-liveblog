<?php
/**
 * The main plugin function
 *
 * @package wp-liveblog
 */

namespace Alley\WP\WP_Liveblog;

use Alley\WP\Features\Group;
use Alley\WP\WP_Liveblog\Features\Liveblog_Post_Type;

/**
 * Instantiate the plugin.
 */
function main(): void {
	// Add features here.
	$plugin = new Group(
		new Liveblog_Post_Type(),
	);

	$plugin->boot();
}
