<?php

/**
 * Registers the `pyscrpt` post type.
 */
function pyscrpt_init() {
	register_post_type( 'pyscrpt', array(
		'labels'                => array(
			'name'                  => __( 'Scripts', 'wp_py_az' ),
			'singular_name'         => __( 'Script', 'wp_py_az' ),
			'all_items'             => __( 'All Scripts', 'wp_py_az' ),
			'archives'              => __( 'Script Archives', 'wp_py_az' ),
			'attributes'            => __( 'Script Attributes', 'wp_py_az' ),
			'insert_into_item'      => __( 'Insert into Script', 'wp_py_az' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Script', 'wp_py_az' ),
			'featured_image'        => _x( 'Featured Image', 'pyscrpt', 'wp_py_az' ),
			'set_featured_image'    => _x( 'Set featured image', 'pyscrpt', 'wp_py_az' ),
			'remove_featured_image' => _x( 'Remove featured image', 'pyscrpt', 'wp_py_az' ),
			'use_featured_image'    => _x( 'Use as featured image', 'pyscrpt', 'wp_py_az' ),
			'filter_items_list'     => __( 'Filter Scripts list', 'wp_py_az' ),
			'items_list_navigation' => __( 'Scripts list navigation', 'wp_py_az' ),
			'items_list'            => __( 'Scripts list', 'wp_py_az' ),
			'new_item'              => __( 'New Script', 'wp_py_az' ),
			'add_new'               => __( 'Add New', 'wp_py_az' ),
			'add_new_item'          => __( 'Add New Script', 'wp_py_az' ),
			'edit_item'             => __( 'Edit Script', 'wp_py_az' ),
			'view_item'             => __( 'View Script', 'wp_py_az' ),
			'view_items'            => __( 'View Scripts', 'wp_py_az' ),
			'search_items'          => __( 'Search Scripts', 'wp_py_az' ),
			'not_found'             => __( 'No Scripts found', 'wp_py_az' ),
			'not_found_in_trash'    => __( 'No Scripts found in trash', 'wp_py_az' ),
			'parent_item_colon'     => __( 'Parent Script:', 'wp_py_az' ),
			'menu_name'             => __( 'Scripts', 'wp_py_az' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title'),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'pyscrpt',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'pyscrpt_init' );

/**
 * Sets the post updated messages for the `pyscrpt` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `pyscrpt` post type.
 */
function pyscrpt_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['pyscrpt'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Script updated. <a target="_blank" href="%s">View Script</a>', 'wp_py_az' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'wp_py_az' ),
		3  => __( 'Custom field deleted.', 'wp_py_az' ),
		4  => __( 'Script updated.', 'wp_py_az' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Script restored to revision from %s', 'wp_py_az' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Script published. <a href="%s">View Script</a>', 'wp_py_az' ), esc_url( $permalink ) ),
		7  => __( 'Script saved.', 'wp_py_az' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Script submitted. <a target="_blank" href="%s">Preview Script</a>', 'wp_py_az' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Script scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Script</a>', 'wp_py_az' ),
		date_i18n( __( 'M j, Y @ G:i', 'wp_py_az' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Script draft updated. <a target="_blank" href="%s">Preview Script</a>', 'wp_py_az' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'pyscrpt_updated_messages' );
