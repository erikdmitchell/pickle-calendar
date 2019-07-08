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
function pc_single_event_template( $single ) {
    global $post;

    /* Checks for single template by post type */
    if ( 'pcevent' === $post->post_type ) {
        return pc_locate_template( 'single-pcevent.php' );
    }

    return $single;
}

add_filter( 'single_template', 'pc_single_event_template' );

/**
 * Locate template.
 *
 * @access public
 * @param mixed $template_name string.
 * @param bool  $load (default: false).
 * @param bool  $require_once (default: true).
 * @return file
 */
function pc_locate_template( $template_name, $load = false, $require_once = true ) {
    // Templates dir.
    $templates_dir = PICKLE_CALENDAR_PATH . 'templates/';

    // Trim off any slashes from the template name.
    $template_name = ltrim( $template_name, '/' );

    // Check child theme first.
    if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'pc/' . $template_name ) ) {
        $located = trailingslashit( get_stylesheet_directory() ) . 'pc/' . $template_name;

        // Check parent theme next.
    } elseif ( file_exists( trailingslashit( get_template_directory() ) . 'pc/' . $template_name ) ) {
        $located = trailingslashit( get_template_directory() ) . 'pc/' . $template_name;

        // Check theme compatibility last.
    } elseif ( file_exists( trailingslashit( $templates_dir ) . $template_name ) ) {
        $located = trailingslashit( $templates_dir ) . $template_name;
    }

    if ( ( true == $load ) && ! empty( $located ) ) {
        load_template( $located, $require_once );
    }

    return $located;
}

/**
 * Get template part.
 *
 * @access public
 * @param mixed $slug (slug).
 * @param mixed $name (default: null).
 * @param bool  $load (default: true).
 * @return template
 */
function pc_get_template_part( $slug, $name = null, $load = true ) {
    // Execute code for this part.
    do_action( 'get_template_part_' . $slug, $slug, $name );

    // Setup possible parts.
    if ( isset( $name ) ) :
        $template = $slug . '-' . $name . '.php';
    else :
        $template = $slug . '.php';
    endif;

    // Allow template parts to be filtered.
    $template = apply_filters( 'pc_get_template_part', $template, $slug, $name );

    // Return the part that is found.
    return pc_locate_template( $template, $load, false );
}
