<?php
/**
 * Pickle Calendar functions
 *
 * @package PickleCalendar
 * @since   1.0.0
 */


/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_template');

function my_custom_template($single) {

    global $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'POST TYPE NAME' ) {
        if ( file_exists( PLUGIN_PATH . '/Custom_File.php' ) ) {
            return PLUGIN_PATH . '/Custom_File.php';
        }
    }

    return $single;

}