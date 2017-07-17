<?php

function sctype_init() {
	register_taxonomy( 'pctype', array( 'scevent' ), array(
		'hierarchical'      => true,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => true,
		'capabilities'      => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts'
		),
		'labels'            => array(
			'name'                       => __( 'Event Type', 'pickle-calendar' ),
			'singular_name'              => _x( 'Event Type', 'taxonomy general name', 'pickle-calendar' ),
			'search_items'               => __( 'Search Event Types', 'pickle-calendar' ),
			'popular_items'              => __( 'Popular Event Types', 'pickle-calendar' ),
			'all_items'                  => __( 'All Event Types', 'pickle-calendar' ),
			'parent_item'                => __( 'Parent Event Type', 'pickle-calendar' ),
			'parent_item_colon'          => __( 'Parent Event Type:', 'pickle-calendar' ),
			'edit_item'                  => __( 'Edit Event Type', 'pickle-calendar' ),
			'update_item'                => __( 'Update Event Type', 'pickle-calendar' ),
			'add_new_item'               => __( 'New Event Type', 'pickle-calendar' ),
			'new_item_name'              => __( 'New Event Type', 'pickle-calendar' ),
			'separate_items_with_commas' => __( 'Separate Event Types with commas', 'pickle-calendar' ),
			'add_or_remove_items'        => __( 'Add or remove Event Types', 'pickle-calendar' ),
			'choose_from_most_used'      => __( 'Choose from the most used Event Types', 'pickle-calendar' ),
			'not_found'                  => __( 'No Event Types found.', 'pickle-calendar' ),
			'menu_name'                  => __( 'Event Type', 'pickle-calendar' ),
		),
		'show_in_rest'      => true,
		'rest_base'         => 'pctype',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	) );

}
add_action( 'init', 'sctype_init' );
?>