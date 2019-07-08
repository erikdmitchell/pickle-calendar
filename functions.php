<?php
/**
 * Pickle Calendar functions
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

/**
 * Get event details.
 *
 * @access public
 * @param string $the_post (default: '').
 * @return array
 */
function pc_get_event_details( $the_post = '' ) {
    global $post;

    if ( is_int( $the_post ) ) :
        // get the post object of the passed ID.
        $event_post = get_post( $the_post );
    elseif ( is_object( $the_post ) ) :
        $event_post = $the_post;
    endif;

    $dates = array();
    $meta = get_post_meta( $event_post->ID );

    foreach ( $meta as $key => $value ) :
        if ( strpos( $key, '_start_date_' ) !== false ) :
            if ( isset( $value[0] ) ) :
                preg_match( '/([0-9]+)/', $key, $matches );
                $dates[ $matches[1] ]['start_date'] = $value[0];
            endif;
            elseif ( strpos( $key, '_end_date_' ) !== false ) :
                if ( isset( $value[0] ) ) :
                    preg_match( '/([0-9]+)/', $key, $matches );
                    $dates[ $matches[1] ]['end_date'] = $value[0];
                endif;
        endif;
    endforeach;

    return $dates;
}

/**
 * PC get posts.
 *
 * @access public
 * @param mixed $args (default: null).
 * @return array
 */
function pc_get_posts( $args = null ) {
    global $wpdb;

    $posts = array();
    $today = date( 'Y-m-d' );
    $defaults = array(
        'events' => 5,
        'orderby' => 'start_date',
        'order' => 'ASC',
        'start_date' => '',
        'end_date' => '',
    );

    $args = wp_parse_args( $args, $defaults );
    $db_from = "FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id ) INNER JOIN $wpdb->postmeta AS mt1 ON ( $wpdb->posts.ID = mt1.post_id )";
    $where_data = array( '1=1', "AND $wpdb->posts.post_type = 'pcevent'", "AND (($wpdb->posts.post_status = 'publish'))", "AND ( REPLACE($wpdb->postmeta.meta_key, '_start_date_', '') = REPLACE(mt1.meta_key, '_end_date_', '') )" );

    // this is where our "options are run".
    // anything from today onwards (default) -- CAN SET DATE FOR START DATE.
    if ( ! empty( $args['start_date'] ) && empty( $args['end_date'] ) ) :
        $where_data[] = "AND ( ( mt1.meta_key LIKE '_end_date_%' AND CAST(mt1.meta_value AS DATE) >= {$args['start_date']} ) )"; // anything from start date onwards.
    elseif ( ! empty( $args['end_date'] ) && empty( $args['start_date'] ) ) :
        $where_data[] = "AND ( ( mt1.meta_key LIKE '_end_date_%' AND CAST(mt1.meta_value AS DATE) <= $today ) )"; // anything before today.
    elseif ( ! empty( $args['start_date'] ) && ! empty( $args['end_date'] ) ) :
        $where_data[] = "AND ( ( $wpdb->postmeta.meta_key LIKE '_start_date_%' AND CAST($wpdb->postmeta.meta_value AS DATE) >= {$args['start_date']} ) AND ( mt1.meta_key LIKE '_end_date_%' AND CAST(mt1.meta_value AS DATE) <= {$args['end_date']} ) )"; // between two dates.
    else :
        $where_data[] = "AND ( ( mt1.meta_key LIKE '_end_date_%' AND CAST(mt1.meta_value AS DATE) >= $today ) )"; // anything from today onwards (default).
    endif;

    $where = implode( ' ', $where_data );
    $post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT wp_posts.ID %s WHERE %s GROUP BY $wpdb->posts.ID ORDER BY $wpdb->postmeta.meta_value %s LIMIT %s", $db_from, $where, $args['order'], $args['events'] ) );

    foreach ( $post_ids as $post_id ) :
        $post = get_post( $post_id );
        $post->event_dates = pc_get_event_dates( $post_id );

        $posts[] = $post;
    endforeach;

    return $posts;
}

/**
 * Get event dates.
 *
 * @access public
 * @param int $post_id (default: 0).
 * @return array
 */
function pc_get_event_dates( $post_id = 0 ) {
    $dates = array();
    $meta = get_post_meta( $post_id );

    foreach ( $meta as $key => $value ) :
        if ( strpos( $key, '_start_date_' ) !== false ) :
            if ( isset( $value[0] ) ) :
                preg_match( '/([0-9]+)/', $key, $matches );
                $dates[ $matches[1] ]['start_date'] = $value[0];
            endif;
            elseif ( strpos( $key, '_end_date_' ) !== false ) :
                if ( isset( $value[0] ) ) :
                    preg_match( '/([0-9]+)/', $key, $matches );
                    $dates[ $matches[1] ]['end_date'] = $value[0];
                endif;
        endif;
    endforeach;

    return $dates;
}

/**
 * Sanitize array.
 *
 * @access public
 * @param array $data (default: array()).
 * @return array
 */
function pc_sanitize_array( $data = array() ) {
    if ( ! is_array( $data ) || ! count( $data ) ) {
        return array();
    }

    foreach ( $data as $k => $v ) {
        if ( ! is_array( $v ) && ! is_object( $v ) ) {
            $data[ $k ] = sanitize_text_field( $v );
        }

        if ( is_array( $v ) ) {
            $data[ $k ] = pc_sanitize_array( $v );
        }
    }

    return $data;
}
