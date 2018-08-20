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
        if ( file_exists( PICKLE_CALENDAR_PATH . '/templates/single-pcevent.php' ) ) {
            return PICKLE_CALENDAR_PATH . '/templates/single-pcevent.php';
        }
    }

    return $single;
}

add_filter('single_template', 'pc_single_event_template');