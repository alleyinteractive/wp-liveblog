<?php
/**
 * Liveblog_Entry_Post_Type class file
 *
 * @package wp-liveblog
 */

namespace Alley\WP\Liveblog\Features;

use Alley\WP\Types\Feature;

/**
 * Liveblog Entry post type.
 */
final readonly class Liveblog_Entry_Post_Type implements Feature {
	/**
	 * Boot the feature.
	 */
	public function boot(): void {
		add_action( 'init', [ $this, 'create_post_type' ], 10 );
		add_filter( 'post_updated_messages', [ $this, 'set_post_updated_messages' ] );
	}

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			'liveblog_entry',
			[
				'labels'           => [
					'name'                     => __( 'Liveblog Entries', 'wp-liveblog' ),
					'singular_name'            => __( 'Liveblog Entry', 'wp-liveblog' ),
					'add_new'                  => __( 'Add New Liveblog Entry', 'wp-liveblog' ),
					'add_new_item'             => __( 'Add New Liveblog Entry', 'wp-liveblog' ),
					'edit_item'                => __( 'Edit Liveblog Entry', 'wp-liveblog' ),
					'new_item'                 => __( 'New Liveblog Entry', 'wp-liveblog' ),
					'view_item'                => __( 'View Liveblog Entry', 'wp-liveblog' ),
					'view_items'               => __( 'View Liveblog Entries', 'wp-liveblog' ),
					'search_items'             => __( 'Search Liveblog Entries', 'wp-liveblog' ),
					'not_found'                => __( 'No liveblog entries found', 'wp-liveblog' ),
					'not_found_in_trash'       => __( 'No liveblog entries found in Trash', 'wp-liveblog' ),
					'parent_item_colon'        => __( 'Parent Liveblog Entry:', 'wp-liveblog' ),
					'all_items'                => __( 'All Liveblog Entries', 'wp-liveblog' ),
					'archives'                 => __( 'Liveblog Entry Archives', 'wp-liveblog' ),
					'attributes'               => __( 'Liveblog Entry Attributes', 'wp-liveblog' ),
					'insert_into_item'         => __( 'Insert into liveblog entry', 'wp-liveblog' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this liveblog entry', 'wp-liveblog' ),
					'featured_image'           => __( 'Featured image', 'wp-liveblog' ),
					'set_featured_image'       => __( 'Set featured image', 'wp-liveblog' ),
					'remove_featured_image'    => __( 'Remove featured image', 'wp-liveblog' ),
					'use_featured_image'       => __( 'Use as featured image', 'wp-liveblog' ),
					'filter_items_list'        => __( 'Filter liveblog entries list', 'wp-liveblog' ),
					'items_list_navigation'    => __( 'Liveblog Entries list navigation', 'wp-liveblog' ),
					'items_list'               => __( 'Liveblog Entries list', 'wp-liveblog' ),
					'item_published'           => __( 'Liveblog Entry published.', 'wp-liveblog' ),
					'item_published_privately' => __( 'Liveblog Entry published privately.', 'wp-liveblog' ),
					'item_reverted_to_draft'   => __( 'Liveblog Entry reverted to draft.', 'wp-liveblog' ),
					'item_trashed'             => __( 'Liveblog Entry trashed.', 'wp-liveblog' ),
					'item_scheduled'           => __( 'Liveblog Entry scheduled.', 'wp-liveblog' ),
					'item_updated'             => __( 'Liveblog Entry updated.', 'wp-liveblog' ),
					'menu_name'                => __( 'Liveblog Entries', 'wp-liveblog' ),
				],
				'description'      => __( 'A single update belonging to a Liveblog post.', 'wp-liveblog' ),
				'public'           => false,
				'hierarchical'     => true,
				'show_in_rest'     => true,
				'rest_base'        => 'liveblog-entries',
				'map_meta_cap'     => true,
				'supports'         => [
					'title',
					'editor',
					'revisions',
					'author',
					'excerpt',
					'custom-fields',
				],
				'rewrite'          => false,
				'query_var'        => false,
				'delete_with_user' => false,
			],
		);
	}

	/**
	 * Set post type updated messages.
	 *
	 * The messages are as follows:
	 *
	 *   1 => "Post updated. {View Post}"
	 *   2 => "Custom field updated."
	 *   3 => "Custom field deleted."
	 *   4 => "Post updated."
	 *   5 => "Post restored to revision from [date]."
	 *   6 => "Post published. {View post}"
	 *   7 => "Post saved."
	 *   8 => "Post submitted. {Preview post}"
	 *   9 => "Post scheduled for: [date]. {Preview post}"
	 *  10 => "Post draft updated. {Preview post}"
	 *
	 * (Via https://github.com/johnbillion/extended-cpts.)
	 *
	 * @param array $messages An associative array of post updated messages with post type as keys.
	 *
	 * @return array Updated array of post updated messages.
	 */
	public function set_post_updated_messages( $messages ) {
		global $post;

		$preview_url    = get_preview_post_link( $post );
		$permalink      = get_permalink( $post );
		$scheduled_date = date_i18n( 'M j, Y @ H:i', strtotime( $post->post_date ) );

		$preview_post_link_html   = '';
		$scheduled_post_link_html = '';
		$view_post_link_html      = '';

		if ( is_post_type_viewable( 'liveblog_entry' ) ) {
			// Preview-post link.
			$preview_post_link_html = sprintf(
				' <a target="_blank" href="%1$s">%2$s</a>',
				esc_url( $preview_url ),
				__( 'Preview liveblog', 'wp-liveblog' )
			);

			// Scheduled post preview link.
			$scheduled_post_link_html = sprintf(
				' <a target="_blank" href="%1$s">%2$s</a>',
				esc_url( $permalink ),
				__( 'Preview liveblog', 'wp-liveblog' )
			);

			// View-post link.
			$view_post_link_html = sprintf(
				' <a href="%1$s">%2$s</a>',
				esc_url( $permalink ),
				__( 'View liveblog', 'wp-liveblog' )
			);
		}

		$messages['liveblog_entry'] = [
			1  => __( 'Liveblog updated.', 'wp-liveblog' ) . $view_post_link_html,
			2  => __( 'Custom field updated.', 'wp-liveblog' ),
			3  => __( 'Custom field updated.', 'wp-liveblog' ),
			4  => __( 'Liveblog updated.', 'wp-liveblog' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Liveblog restored to revision from %s.', 'wp-liveblog' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			6  => __( 'Liveblog published.', 'wp-liveblog' ) . $view_post_link_html,
			7  => __( 'Liveblog saved.', 'wp-liveblog' ),
			8  => __( 'Liveblog submitted.', 'wp-liveblog' ) . $preview_post_link_html,
			/* translators: %s: date on which the liveblog is currently scheduled to be published */
			9  => sprintf( __( 'Liveblog scheduled for: %s.', 'wp-liveblog' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_post_link_html,
			10 => __( 'Liveblog draft updated.', 'wp-liveblog' ) . $preview_post_link_html,
		];

		return $messages;
	}
}
