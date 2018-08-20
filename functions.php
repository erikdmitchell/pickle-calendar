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
        // get the post object of the passed ID
        $post = get_post( $the_post );
    elseif ( is_object( $the_post ) ) :
        $post = $the_post;
    endif;

    $dates = array();
    $meta = get_post_meta( $post->ID );

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

function pc_get_posts( $args = null ) {
    global $wpdb;

    $defaults = array(
        'numberposts' => 5,
        'category' => 0,
        'orderby' => 'date',
        'order' => 'DESC',
        'include' => array(),
        'exclude' => array(),
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'post',
        'suppress_filters' => true,
    );

    $r = wp_parse_args( $args, $defaults );

    /*
    $today = date('Y-m-d');
    $posts = $wpdb->get_results("
        SELECT wp_posts.ID,
        wp_posts.post_title,
        wp_postmeta.*
        FROM $wpdb->posts
        INNER JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id )
        WHERE $wpdb->posts.post_type = 'pcevent'
        AND $wpdb->posts.post_status = 'publish'
        AND ( $wpdb->postmeta.meta_key LIKE '_start_date_%' AND CAST($wpdb->postmeta.meta_value AS DATE) >= '$today')
        ORDER BY $wpdb->postmeta.meta_value ASC
    ");
    */

    /*
        $post_ids = $wpdb->get_col(
            $wpdb->prepare(
                "
            SELECT wp_posts.ID
            FROM $wpdb->posts
            INNER JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id )
            INNER JOIN $wpdb->postmeta AS mt1 ON ( $wpdb->posts.ID = mt1.post_id )
            WHERE 1=1
                AND ( ( $wpdb->postmeta.meta_key LIKE %s AND CAST($wpdb->postmeta.meta_value AS DATE) <= %s ) AND ( mt1.meta_key LIKE %s AND CAST(mt1.meta_value AS DATE) >= %s ) )
                AND ( REPLACE($wpdb->postmeta.meta_key, '_start_date_', '') = REPLACE(mt1.meta_key, '_end_date_', '') )
                AND $wpdb->posts.post_type = 'pcevent'
                AND (($wpdb->posts.post_status = 'publish'))
            GROUP BY $wpdb->posts.ID
            ORDER BY $wpdb->postmeta.meta_value ASC
            ",
                '_start_date_%',
                $date,
                '_end_date_%',
                $date
            )
        );
    */

    return $posts;
}
