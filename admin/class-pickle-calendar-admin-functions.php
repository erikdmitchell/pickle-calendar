<?php
/**
 * Pickle Calendar admin functions
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

/**
 * Pickle_Calendar_Admin_Functions class.
 */
class Pickle_Calendar_Admin_Functions {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {}

    /**
     * Check remove taxonomy.
     *
     * @access public
     * @return void
     */
    public function check_remove_taxonomy() {
        // remove single taxonomy.
        $action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
        $slug = isset( $_GET['slug'] ) ? sanitize_text_field( wp_unslash( $_GET['slug'] ) ) : '';

        if ( ( 'delete' == $action ) && isset( $_GET['slug'] ) ) :
            $this->remove_taxonomy( $slug );
        endif;

        // bulk actions //
        if ( isset( $_POST['action'] ) ) :
            switch ( $_POST['action'] ) :
                case 'deleteall':
                    $this->taxonomy_bulk_delete( $_POST['pickle_calendar_taxonomy'] );
                    break;
            endswitch;
        endif;
    }

    /**
     * Remove taxonomy.
     *
     * @access public
     * @param string $tax_slug (default: '').
     * @return void
     */
    public function remove_taxonomy( $tax_slug = '' ) {
        $taxonomies = get_option( 'pickle_calendar_taxonomies' );

        foreach ( $taxonomies as $key => $taxonomy ) :
            if ( $taxonomy['slug'] == $tax_slug ) :
                unset( $taxonomies[ $key ] );

                break;
            endif;
        endforeach;

        $taxonomies = array_values( $taxonomies );

        update_option( 'pickle_calendar_taxonomies', $taxonomies );

        picklecalendar()->update_settings();
    }

    /**
     * Bulk delete taxonomies.
     *
     * @access protected
     * @param string $taxonomies (default: '').
     * @return void
     */
    protected function taxonomy_bulk_delete( $taxonomies = '' ) {
        if ( empty( $taxonomies ) ) {
            return;
        }

        foreach ( $taxonomies as $taxonomy ) :
            $this->remove_taxonomy( $taxonomy );
        endforeach;
    }

    /**
     * Pickle Calendar get taxonomy function.
     *
     * @access public
     * @param string $slug (default: '').
     * @return array
     */
    function pickle_calendar_get_taxonomy( $slug = '' ) {
        $default = array(
            'slug' => '',
            'label' => '',
            'label_plural' => '',
            'display' => 1,
            'display_type' => 'checkbox',
            'hide_all_tab' => 0,
        );
        $tax = array();

        foreach ( picklecalendar()->settings['taxonomies'] as $taxonomy ) :
            if ( $taxonomy['slug'] == $slug ) :
                $tax = $taxonomy;
                break;
            endif;
        endforeach;

        $tax = picklecalendar()->parse_args( $tax, $default );

        return $tax;
    }

}
