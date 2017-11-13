<?php

function sctype_init() {
	register_taxonomy('pctype', array( 'pcevent' ), array(
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
			'name'                       => __( picklecalendar()->settings['tax_plural'], 'pickle-calendar' ),
			'singular_name'              => _x( picklecalendar()->settings['tax_single'], 'taxonomy general name', 'pickle-calendar' ),
			'search_items'               => __( 'Search '.picklecalendar()->settings['tax_plural'], 'pickle-calendar' ),
			'popular_items'              => __( 'Popular '.picklecalendar()->settings['tax_plural'], 'pickle-calendar' ),
			'all_items'                  => __( 'All '.picklecalendar()->settings['tax_plural'], 'pickle-calendar' ),
			'parent_item'                => __( 'Parent '.picklecalendar()->settings['tax_single'], 'pickle-calendar' ),
			'parent_item_colon'          => __( 'Parent '.picklecalendar()->settings['tax_single'].':', 'pickle-calendar' ),
			'edit_item'                  => __( 'Edit '.picklecalendar()->settings['tax_single'], 'pickle-calendar' ),
			'update_item'                => __( 'Update '.picklecalendar()->settings['tax_single'], 'pickle-calendar' ),
			'add_new_item'               => __( 'New '.picklecalendar()->settings['tax_single'], 'pickle-calendar' ),
			'new_item_name'              => __( 'New '.picklecalendar()->settings['tax_single'], 'pickle-calendar' ),
			'separate_items_with_commas' => __( 'Separate '.picklecalendar()->settings['tax_plural'].' with commas', 'pickle-calendar' ),
			'add_or_remove_items'        => __( 'Add or remove '.picklecalendar()->settings['tax_plural'], 'pickle-calendar' ),
			'choose_from_most_used'      => __( 'Choose from the most used '.picklecalendar()->settings['tax_plural'], 'pickle-calendar' ),
			'not_found'                  => __( 'No '.picklecalendar()->settings['tax_plural'].' found.', 'pickle-calendar' ),
			'menu_name'                  => __( picklecalendar()->settings['tax_plural'], 'pickle-calendar' ),
		),
		'show_in_rest'      => true,
		'rest_base'         => 'pctype',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	) );

}
add_action('init', 'sctype_init', 5);
?>