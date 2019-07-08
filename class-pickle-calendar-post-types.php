<?php
/**
 * Pickle Calendar Event Details
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

/**
 * Pickle_Calendar_Post_Types class.
 */
class Pickle_Calendar_Post_Types {

    /**
     * Init.
     *
     * @access public
     * @static
     * @return void
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );

        add_filter( 'post_updated_messages', array( __CLASS__, 'updated_messages' ) );

        add_action( 'wp_print_scripts', array( __CLASS__, 'pcevents_dequeue_wp_seo_scripts' ), 100 );
        add_action( 'add_meta_boxes', array( __CLASS__, 'pcevents_remove_wp_seo_meta_box' ), 100 );
        // potential rewrite flush rules hook/action here.
    }

    /**
     * Register post types.
     *
     * @access public
     * @static
     * @return void
     */
    public static function register_post_types() {
        if ( post_type_exists( 'pcevent' ) ) {
            return;
        }

        $supports = array( 'title' );

        if ( picklecalendar()->settings['enable_editor'] ) {
            $supports[] = 'editor';
        }

        $cpt_plural = picklecalendar()->settings['cpt_plural'];
        $cpt_single = picklecalendar()->settings['cpt_single'];

        register_post_type(
            'pcevent',
            array(
                'labels'            => array(
                    'name'                => $cpt_plural,
                    'singular_name'       => $cpt_single,
                    'all_items'           => 'All ' . $cpt_plural,
                    'new_item'            => 'New ' . $cpt_single,
                    'add_new'             => __( 'Add New', 'pickle-calendar' ),
                    'add_new_item'        => 'Add New ' . $cpt_single,
                    'edit_item'           => 'Edit ' . $cpt_single,
                    'view_item'           => 'View ' . $cpt_single,
                    'search_items'        => 'Search ' . $cpt_plural,
                    'not_found'           => 'No ' . $cpt_plural . ' found',
                    'not_found_in_trash'  => 'No ' . $cpt_plural . ' found in trash',
                    'parent_item_colon'   => 'Parent ' . $cpt_single,
                    'menu_name'           => _picklecalendar()->settings['adminlabel'],
                ),
                'public'            => true,
                'hierarchical'      => false,
                'show_ui'           => true,
                'show_in_nav_menus' => true,
                'supports'          => $supports,
                'has_archive'       => true,
                'rewrite'           => true,
                'query_var'         => true,
                'menu_icon'         => 'dashicons-calendar-alt',
                'show_in_rest'      => true,
                'rest_base'         => 'pcevent',
                'rest_controller_class' => 'WP_REST_Posts_Controller',
            )
        );

    }

    /**
     * Register taxonomies.
     *
     * @access public
     * @static
     * @return void
     */
    public static function register_taxonomies() {
        foreach ( picklecalendar()->settings['taxonomies'] as $taxonomy ) :
            self::generate_taxonomy( $taxonomy );
        endforeach;
    }

    /**
     * Generate taxonomies.
     *
     * @access protected
     * @static
     * @param array $taxonomy (default: array()).
     * @return bool
     */
    protected static function generate_taxonomy( $taxonomy = array() ) {
        if ( empty( $taxonomy ) ) {
            return;
        }

        $defaults = array(
            'post_type' => 'pcevent',
            'slug' => '',
        );
        $args = wp_parse_args( $taxonomy, $defaults );

        if ( empty( $args['slug'] ) ) {
            return;
        }

        if ( empty( $args['label'] ) ) :
            preg_replace( '/_|-/', ' ', strtolower( $args['slug'] ) );
        endif;

        if ( empty( $args['label_plural'] ) ) :
            preg_replace( '/_|-/', ' ', strtolower( $args['slug'] ) );
        endif;

        register_taxonomy(
            $args['slug'],
            array( $args['post_type'] ),
            array(
                'hierarchical'      => true,
                'public'            => true,
                'show_in_nav_menus' => true,
                'show_ui'           => true,
                'show_admin_column' => false,
                'query_var'         => true,
                'rewrite'           => true,
                'capabilities'      => array(
                    'manage_terms'  => 'edit_posts',
                    'edit_terms'    => 'edit_posts',
                    'delete_terms'  => 'edit_posts',
                    'assign_terms'  => 'edit_posts',
                ),
                'labels'            => array(
                    'name'                       => ucwords( $args['label_plural'] ),
                    'singular_name'              => ucwords( $args['label'] ),
                    'search_items'               => 'Search ' . ucwords( $args['label_plural'] ),
                    'popular_items'              => 'Popular ' . ucwords( $args['label_plural'] ),
                    'all_items'                  => 'All ' . ucwords( $args['label_plural'] ),
                    'parent_item'                => 'Parent ' . ucwords( $args['label'] ),
                    'parent_item_colon'          => 'Parent ' . ucwords( $args['label'] ) . ':',
                    'edit_item'                  => 'Edit ' . ucwords( $args['label'] ),
                    'update_item'                => 'Update ' . ucwords( $args['label'] ),
                    'add_new_item'               => 'New ' . ucwords( $args['label'] ),
                    'new_item_name'              => 'New ' . ucwords( $args['label'] ),
                    'separate_items_with_commas' => 'Separate ' . ucwords( $args['label_plural'] ) . ' with commas',
                    'add_or_remove_items'        => 'Add or remove ' . ucwords( $args['label_plural'] ),
                    'choose_from_most_used'      => 'Choose from the most used ' . ucwords( $args['label_plural'] ),
                    'not_found'                  => 'No ' . ucwords( $args['label_plural'] ) . ' found.',
                    'menu_name'                  => ucwords( $args['label_plural'] ),
                ),
                'show_in_rest'      => true,
                'rest_base'         => $args['slug'],
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            )
        );
    }

    /**
     * Update admin messages.
     *
     * @access public
     * @static
     * @param mixed $messages (array).
     * @return array
     */
    public static function updated_messages( $messages ) {
        global $post;

        $permalink = get_permalink( $post );

        $messages['pcevent'] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( '%1$s updated. <a target="_blank" href="%2$s">View %3$s</a>', picklecalendar()->settings['cpt_single'], esc_url( $permalink ), picklecalendar()->settings['cpt_single'] ),
            2 => __( 'Custom field updated.', 'pickle-calendar' ),
            3 => __( 'Custom field deleted.', 'pickle-calendar' ),
            4 => picklecalendar()->settings['cpt_single'] . ' updated.',
            /* translators: %s: date and time of the revision */
            5 => isset( $_GET['revision'] ) ? sprintf( '%1$s restored to revision from %2$s', picklecalendar()->settings['cpt_single'], wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( '%1$s published. <a href="%2$s">View %3$s</a>', picklecalendar()->settings['cpt_single'], esc_url( $permalink ), picklecalendar()->settings['cpt_single'] ),
            7 => sprintf( '%1$s saved.', picklecalendar()->settings['cpt_single'] ),
            8 => sprintf( '%1$s submitted. <a target="_blank" href="%2$s">Preview %3$s</a>', picklecalendar()->settings['cpt_single'], esc_url( add_query_arg( 'preview', 'true', $permalink ) ), picklecalendar()->settings['cpt_single'] ),
            9 => sprintf(
                '%1$s scheduled for: <strong>%2$s</strong>. <a target="_blank" href="%3$s">Preview %4$s</a>',
                picklecalendar()->settings['cpt_single'],
                // translators: Publish box date format, see http://php.net/date.
                date_i18n( __( 'M j, Y @ G:i', 'pickle-calendar' ), strtotime( $post->post_date ) ),
                esc_url( $permalink ),
                picklecalendar()->settings['cpt_single']
            ),
            10 => sprintf(
                '%1$s draft updated. <a target="_blank" href="%2$s">Preview %3$s</a>',
                picklecalendar()->settings['cpt_single'],
                esc_url( add_query_arg( 'preview', 'true', $permalink ) )
            ),
            picklecalendar()->settings['cpt_single'],
        );

        return $messages;
    }

    /**
     * Removed Yoast SEO scripts.
     *
     * @access public
     * @static
     * @return void
     */
    public static function pcevents_dequeue_wp_seo_scripts() {
        global $post;

        if ( isset( $post->post_type ) && 'pcevents' != $post->post_type ) {
            return;
        }

        wp_dequeue_script( 'yoast-seo-post-scraper' );
    }
    /**
     * Removed Yoast SEO meta box.
     *
     * @access public
     * @static
     * @return void
     */
    public static function pcevents_remove_wp_seo_meta_box() {
        global $post;

        if ( 'pcevents' != $post->post_type ) {
            return;
        }

        remove_meta_box( 'wpseo_meta', 'pcevent', 'normal' );
    }

    /**
     * Flush rewrite rules.
     *
     * @access public
     * @static
     * @return void
     */
    public static function flush_rewrite_rules() {
        flush_rewrite_rules();
    }

}

Pickle_Calendar_Post_Types::init();
