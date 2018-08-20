<?php
/**
 * Pickle Calendar functions
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

/**
 * Single event template.
 * 
 * @access public
 * @param mixed $single (string).
 * @return file
 */
function pc_single_event_template($single) {
    global $post;

    /* Checks for single template by post type */
    if ( 'pcevent' === $post->post_type ) {
        return pc_locate_template( 'single-pcevent.php' );
    }

    return $single;
}

add_filter('single_template', 'pc_single_event_template');

/**
 * Lcate template.
 * 
 * @access public
 * @param string $template_name (default: '').
 * @return path
 */
function pc_locate_template( $template_name = '' ) {
    // Templates dir.
    $templates_dir = PICKLE_CALENDAR_PATH . 'templates/';

    // Trim off any slashes from the template name
    $template_name = ltrim( $template_name, '/' );
	
	// Check child theme first
	if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'pc/' . $template_name ) ) {
		$located = trailingslashit( get_stylesheet_directory() ) . 'pc/' . $template_name;

	// Check parent theme next
	} elseif ( file_exists( trailingslashit( get_template_directory() ) . 'pc/' . $template_name ) ) {
		$located = trailingslashit( get_template_directory() ) . 'pc/' . $template_name;

	// Check theme compatibility last
	} elseif ( file_exists( trailingslashit( $templates_dir ) . $template_name ) ) {
		$located = trailingslashit( $templates_dir ) . $template_name;
	}

    return $located;
}

