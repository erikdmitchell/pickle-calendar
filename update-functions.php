<?php
/**
 * Pickle Calendar update functions
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

/**
 * Update version 1.2.0.
 *
 * @access public
 * @return void
 */
function pcl_update_120_taxonomies() {
    $taxonomies = array(
        array(
            'slug' => 'pctype',
            'label' => 'type',
            'label_plural' => 'types',
        ),
    );

    add_option( 'pickle_calendar_taxonomies', $taxonomies );
}

