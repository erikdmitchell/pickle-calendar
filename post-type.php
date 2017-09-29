<?php

function pcevents_init() {
	register_post_type('pcevent', array(
		'labels'            => array(
			'name'                => __( picklecalendar()->settings['cpt_plural'], 'pickle-calendar' ),
			'singular_name'       => __( picklecalendar()->settings['cpt_single'], 'pickle-calendar' ),
			'all_items'           => __( 'All '.picklecalendar()->settings['cpt_plural'], 'pickle-calendar' ),
			'new_item'            => __( 'New '.picklecalendar()->settings['cpt_single'], 'pickle-calendar' ),
			'add_new'             => __( 'Add New', 'pickle-calendar' ),
			'add_new_item'        => __( 'Add New '.picklecalendar()->settings['cpt_single'], 'pickle-calendar' ),
			'edit_item'           => __( 'Edit '.picklecalendar()->settings['cpt_single'], 'pickle-calendar' ),
			'view_item'           => __( 'View '.picklecalendar()->settings['cpt_single'], 'pickle-calendar' ),
			'search_items'        => __( 'Search '.picklecalendar()->settings['cpt_plural'], 'pickle-calendar' ),
			'not_found'           => __( 'No '.picklecalendar()->settings['cpt_plural'].' found', 'pickle-calendar' ),
			'not_found_in_trash'  => __( 'No '.picklecalendar()->settings['cpt_plural'].' found in trash', 'pickle-calendar' ),
			'parent_item_colon'   => __( 'Parent '.picklecalendar()->settings['cpt_single'], 'pickle-calendar' ),
			'menu_name'           => __( picklecalendar()->settings['adminlabel'], 'pickle-calendar' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		//'supports'          => array( 'title', 'editor', 'thumbnail' ),
		'supports'          => array('title'),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-calendar-alt',
		'show_in_rest'      => true,
		'rest_base'         => 'pcevent',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action('init', 'pcevents_init');

function pcevents_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['pcevent'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __(picklecalendar()->settings['cpt_single'].' updated. <a target="_blank" href="%s">View '.picklecalendar()->settings['cpt_single'].'</a>', 'pickle-calendar'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'pickle-calendar'),
		3 => __('Custom field deleted.', 'pickle-calendar'),
		4 => __(picklecalendar()->settings['cpt_single'].' updated.', 'pickle-calendar'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __(picklecalendar()->settings['cpt_single'].' restored to revision from %s', 'pickle-calendar'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __(picklecalendar()->settings['cpt_single'].' published. <a href="%s">View '.picklecalendar()->settings['cpt_single'].'</a>', 'pickle-calendar'), esc_url( $permalink ) ),
		7 => __(picklecalendar()->settings['cpt_single'].' saved.', 'pickle-calendar'),
		8 => sprintf( __(picklecalendar()->settings['cpt_single'].' submitted. <a target="_blank" href="%s">Preview '.picklecalendar()->settings['cpt_single'].'</a>', 'pickle-calendar'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __(picklecalendar()->settings['cpt_single'].' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview '.picklecalendar()->settings['cpt_single'].'</a>', 'pickle-calendar'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __(picklecalendar()->settings['cpt_single'].' draft updated. <a target="_blank" href="%s">Preview '.picklecalendar()->settings['cpt_single'].'</a>', 'pickle-calendar'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'pcevents_updated_messages' );

function pcevents_remove_wp_seo_meta_box() {
	global $post;
	
	if ($post->post_type != 'pcevents')
		return;
		
	remove_meta_box('wpseo_meta', 'pcevent', 'normal');
}
add_action('add_meta_boxes', 'pcevents_remove_wp_seo_meta_box', 100);

function pcevents_dequeue_wp_seo_scripts() {
	global $post;
	
	if (isset($post->post_type) && $post->post_type!='pcevents')
		return;
			
    wp_dequeue_script('yoast-seo-post-scraper');  
}
add_action('wp_print_scripts', 'pcevents_dequeue_wp_seo_scripts', 100);