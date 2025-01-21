<?php
/**
 * Liveblog_Post_Type class file.
 *
 * @package wp-liveblog
 */

namespace Alley\WP\WP_Liveblog\Features;

use Alley\WP\Types\Feature;

/**
 * Liveblog post type class.
 */
class Liveblog_Post_Type implements Feature {
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
			'liveblog',
			[
				'labels'       => [
					'name'                     => __( 'Liveblogs', 'wp-liveblog' ),
					'singular_name'            => __( 'Liveblog', 'wp-liveblog' ),
					'add_new'                  => __( 'Add New Liveblog', 'wp-liveblog' ),
					'add_new_item'             => __( 'Add New Liveblog', 'wp-liveblog' ),
					'edit_item'                => __( 'Edit Liveblog', 'wp-liveblog' ),
					'new_item'                 => __( 'New Liveblog', 'wp-liveblog' ),
					'view_item'                => __( 'View Liveblog', 'wp-liveblog' ),
					'view_items'               => __( 'View Liveblogs', 'wp-liveblog' ),
					'search_items'             => __( 'Search Liveblogs', 'wp-liveblog' ),
					'not_found'                => __( 'No liveblogs found', 'wp-liveblog' ),
					'not_found_in_trash'       => __( 'No liveblogs found in Trash', 'wp-liveblog' ),
					'parent_item_colon'        => __( 'Parent Liveblog:', 'wp-liveblog' ),
					'all_items'                => __( 'All Liveblogs', 'wp-liveblog' ),
					'archives'                 => __( 'Liveblog Archives', 'wp-liveblog' ),
					'attributes'               => __( 'Liveblog Attributes', 'wp-liveblog' ),
					'insert_into_item'         => __( 'Insert into liveblog', 'wp-liveblog' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this liveblog', 'wp-liveblog' ),
					'featured_image'           => __( 'Featured image', 'wp-liveblog' ),
					'set_featured_image'       => __( 'Set featured image', 'wp-liveblog' ),
					'remove_featured_image'    => __( 'Remove featured image', 'wp-liveblog' ),
					'use_featured_image'       => __( 'Use as featured image', 'wp-liveblog' ),
					'filter_items_list'        => __( 'Filter liveblogs list', 'wp-liveblog' ),
					'items_list_navigation'    => __( 'Liveblogs list navigation', 'wp-liveblog' ),
					'items_list'               => __( 'Liveblogs list', 'wp-liveblog' ),
					'item_published'           => __( 'Liveblog published.', 'wp-liveblog' ),
					'item_published_privately' => __( 'Liveblog published privately.', 'wp-liveblog' ),
					'item_reverted_to_draft'   => __( 'Liveblog reverted to draft.', 'wp-liveblog' ),
					'item_trashed'             => __( 'Liveblog trashed.', 'wp-liveblog' ),
					'item_scheduled'           => __( 'Liveblog scheduled.', 'wp-liveblog' ),
					'item_updated'             => __( 'Liveblog updated.', 'wp-liveblog' ),
					'menu_name'                => __( 'Liveblogs', 'wp-liveblog' ),
				],
				'public'       => true,
				'description'  => 'A post that contains an active region where frequent updates can be posted.',
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-clock',
				'supports'     => [ 'title', 'editor', 'comments', 'revisions', 'author', 'excerpt', 'thumbnail', 'custom-fields' ],
				'taxonomies'   => [ 'category', 'post_tag' ],
			]
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

		if ( is_post_type_viewable( $this->name ) ) {
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

		$messages[ $this->name ] = [
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
