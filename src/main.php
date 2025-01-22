<?php
/**
 * The main plugin function
 *
 * @package wp-liveblog
 */

namespace Alley\WP\WP_Liveblog;

use Alley\WP\Features\Group;
use Alley\WP\Liveblog\Features\Liveblog_Entry_Post_Type;
use Alley\WP\Liveblog\Features\Liveblog_Post_Type;

/**
 * Instantiate the plugin.
 */
function main(): void {
	$plugin = new Group(
		new Liveblog_Post_Type(),
		new Liveblog_Entry_Post_Type(),
	);

	$plugin->boot();
}
