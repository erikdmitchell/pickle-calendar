<?php
/**
 * Pickle Calendar Admin class
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

/**
 * Pickle_Calendar_Admin class.
 */
class Pickle_Calendar_Admin {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles' ) );
        add_action( 'admin_init', array( $this, 'process_events_export' ) );
        add_action( 'admin_init', array( $this, 'process_events_import' ) );
        add_action( 'admin_init', array( $this, 'process_settings_export' ) );
        add_action( 'admin_init', array( $this, 'process_settings_import' ) );
        add_action( 'admin_init', array( $this, 'update_settings' ) );
        add_action( 'admin_init', array( $this, 'update_taxonomy' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    /**
     * Load admin scripts and styles.
     *
     * @access public
     * @return void
     */
    public function admin_scripts_styles() {
        wp_enqueue_script( 'pickle-calendar-admin-script', PICKLE_CALENDAR_URL . 'admin/js/admin.js', array( 'jquery' ), picklecalendar()->version, true );

        wp_enqueue_style( 'pickle-calendar-admin-css', PICKLE_CALENDAR_URL . 'admin/css/admin.css', '', picklecalendar()->version );
    }

    /**
     * Add page to menu.
     *
     * @access public
     * @return void
     */
    public function admin_menu() {
        add_options_page( 'Pickle Calendar', 'Pickle Calendar', 'manage_options', 'pickle-calendar', array( $this, 'admin_page' ) );
    }

    /**
     * Admin page.
     *
     * @access public
     * @return void
     */
    public function admin_page() {
        $html = null;
        $tabs = array(
            'settings' => 'Settings',
            'taxonomies' => 'Categories',
            'import-export' => 'Import/Export',
        );
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'settings';

        $html .= '<div class="wrap pickle-calendar-admin">';
            $html .= '<h1>Pickle Calendar</h1>';

            $html .= '<h2 class="nav-tab-wrapper">';
        foreach ( $tabs as $key => $name ) :
            if ( $active_tab == $key ) :
                $class = 'nav-tab-active';
            else :
                $class = null;
            endif;

            $html .= '<a href="?page=pickle-calendar&tab=' . $key . '" class="nav-tab ' . $class . '">' . $name . '</a>';
                endforeach;
            $html .= '</h2>';

        switch ( $active_tab ) :
            case 'taxonomies':
                if ( isset( $_GET['action'] ) && 'edit' == $_GET['action'] ) :
                    $html .= $this->get_admin_page( 'taxonomies-single' );
                    else :
                        $html .= $this->get_admin_page( 'taxonomies' );
                    endif;
                break;
            case 'import-export':
                $html .= $this->get_admin_page( 'import-export' );
                break;
            default:
                $html .= $this->get_admin_page( 'settings' );
            endswitch;

        $html .= '</div>';

        echo $html; // phpcs:ignore
    }

    /**
     * Update settings.
     *
     * @access public
     * @return bool
     */
    public function update_settings() {
        if ( ! isset( $_POST['pickle_calendar_admin'] ) || ! wp_verify_nonce( sanitize_key( $_POST['pickle_calendar_admin'] ), 'update_settings' ) ) {
            return false;
        }

        $post_settings = isset( $_POST['settings'] ) ? pc_sanitize_array( wp_unslash( $_POST['settings'] ) ) : '';
        $new_settings = picklecalendar()->parse_args( $post_settings, picklecalendar()->settings );

        // for checkboxes.
        foreach ( $new_settings as $key => $value ) :
            if ( ! isset( $_POST['settings'][ $key ] ) ) :
                $new_settings[ $key ] = 0;
            endif;
        endforeach;

        update_option( 'pickle_calendar_settings', $new_settings );

        $wp_http_referer = isset( $_POST['_wp_http_referer'] ) ? sanitize_text_field( wp_unslash( $_POST['_wp_http_referer'] ) ) : '';

        wp_redirect( site_url( $wp_http_referer ) );
        exit;
    }

    /**
     * Update taxonomy.
     *
     * @access public
     * @return bool
     */
    public function update_taxonomy() {
        if ( ! isset( $_POST['pickle_calendar_admin'] ) || ! wp_verify_nonce( sanitize_key( $_POST['pickle_calendar_admin'] ), 'update_taxonomy' ) ) {
            return false;
        }

        $taxonomies = get_option( 'pickle_calendar_taxonomies' );
        $exists = false;
        $post_tax_details_slug = isset( $_POST['tax_details']['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['tax_details']['slug'] ) ) : '';
        $post_tax_details = isset( $_POST['tax_details'] ) ? sanitize_text_field( wp_unslash( $_POST['tax_details'] ) ) : '';

        // we added a check if tax exists, fixes a bug and provides some built in clean up.
        if ( ! empty( $post_tax_details_slug ) ) :
            // search for existing, update or add.
            foreach ( $taxonomies as $key => $tax_details ) :
                if ( $post_tax_details_slug == $tax_details['slug'] ) :
                    $taxonomies[ $key ] = $post_tax_details;
                    $exists = true;
                endif;
            endforeach;

            // remove dups.
            $taxonomies = array_map( 'unserialize', array_unique( array_map( 'serialize', $taxonomies ) ) );

            // update if not exists.
            if ( ! $exists ) {
                $taxonomies[] = $post_tax_details;
            }
        endif;

        update_option( 'pickle_calendar_taxonomies', $taxonomies );

        picklecalendar()->update_settings();

        wp_redirect( admin_url( 'options-general.php?page=pickle-calendar&tab=taxonomies&action=edit&slug=' . $post_tax_details_slug ) );
        exit;
    }

    /**
     * Process settings export.
     *
     * @access public
     * @return void
     */
    public function process_settings_export() {
        $pc_action = isset( $_POST['pc_action'] ) ? sanitize_text_field( wp_unslash( $_POST['pc_action'] ) ) : '';

        if ( empty( $pc_action ) || 'export_settings' != $pc_action ) {
            return;
        }

        if ( ! isset( $_POST['pickle_calendar_export_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['pickle_calendar_export_nonce'] ), 'pickle_calendar_export_nonce' ) ) {
            return false;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $settings = get_option( 'pickle_calendar_settings' );

        ignore_user_abort( true );

        nocache_headers();

        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=pickle-calendar-settings-export-' . date( 'm-d-Y' ) . '.json' );
        header( 'Expires: 0' );

        echo json_encode( $settings );

        exit;
    }

    /**
     * Process settings import.
     *
     * @access public
     * @return void
     */
    public function process_settings_import() {
        $pc_action = isset( $_POST['pc_action'] ) ? sanitize_text_field( wp_unslash( $_POST['pc_action'] ) ) : '';

        if ( empty( $pc_action ) || 'import_settings' != $pc_action ) {
            return;
        }

        if ( ! isset( $_POST['pickle_calendar_import_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['pickle_calendar_import_nonce'] ), 'pickle_calendar_import_nonce' ) ) {
            return false;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $import_file_name = isset( $_FILES['import_file']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['import_file']['name'] ) ) : '';
        $extension = end( explode( '.', $import_file_name ) );

        if ( 'json' != $extension ) {
            wp_die( 'Please upload a valid .json file.' );
        }

        $import_file = isset( $_FILES['import_file']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['import_file']['tmp_name'] ) ) : '';

        if ( empty( $import_file ) ) {
            wp_die( 'Please upload a file to import.' );
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $settings = json_decode( file_get_contents( $import_file ), true );

        update_option( 'pickle_calendar_settings', $settings );

        wp_safe_redirect( admin_url( 'options-general.php?page=pickle-calendar' ) );

        exit;
    }

    /**
     * Process events export.
     *
     * @access public
     * @return bool
     */
    public function process_events_export() {
        $pc_action = isset( $_POST['pc_action'] ) ? sanitize_text_field( wp_unslash( $_POST['pc_action'] ) ) : '';

        if ( empty( $pc_action ) || 'export_events' != $pc_action ) {
            return;
        }

        if ( ! isset( $_POST['pickle_calendar_export_events_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['pickle_calendar_export_events_nonce'] ), 'pickle_calendar_export_events_nonce' ) ) {
            return false;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        picklecalendar()->import_export_events->export();
    }

    /**
     * Process events import.
     *
     * @access public
     * @return bool
     */
    public function process_events_import() {
        $pc_action = isset( $_POST['pc_action'] ) ? sanitize_text_field( wp_unslash( $_POST['pc_action'] ) ) : '';

        if ( empty( $pc_action ) || 'import_events' != $pc_action ) {
            return;
        }

        if ( ! isset( $_POST['pickle_calendar_import_events_nonce'] ) && ! wp_verify_nonce( sanitize_key( $_POST['pickle_calendar_import_events_nonce'] ), 'pickle_calendar_import_events_nonce' ) ) {
            return false;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $import_file_name = isset( $_FILES['import_file']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['import_file']['name'] ) ) : '';
        $extension = end( explode( '.', $import_file_name ) );

        if ( 'json' != $extension ) {
            wp_die( 'Please upload a valid .json file.' );
        }

        $import_file = isset( $_FILES['import_file']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['import_file']['tmp_name'] ) ) : '';

        if ( empty( $import_file ) ) {
            wp_die( 'Please upload a file to import.' );
        }

        // Retrieve the settings from the file.
        $events = json_decode( file_get_contents( $import_file ) );

        $imported = picklecalendar()->import_export_events->import( $events );

        if ( $imported ) :
            $imported_url_var = 1;
        else :
            $imported_url_var = 0;
        endif;

        wp_safe_redirect( admin_url( 'options-general.php?page=pickle-calendar&import-events=' . $imported_url_var ) );

        exit;
    }

    /**
     * Display admin notices.
     *
     * @access public
     * @return void
     */
    public function admin_notices() {
        $html = '';
        $screen = get_current_screen();

        if ( 'settings_page_pickle-calendar' !== $screen->id ) {
            return;
        }

        if ( ! isset( $_GET['import-events'] ) ) {
            return;
        }

        $import_events = isset( $_GET['import-events'] ) ? sanitize_text_field( wp_unslash( $_GET['import-events'] ) ) : '';

        if ( $import_events ) :

            $html .= '<div class="notice notice-success is-dismissible">';
                $html .= '<p>' . __( 'Events successfully imported.', 'pickle-calendar' ) . '</p>';
            $html .= '</div>';

        else :

            $html .= '<div class="notice notice-error is-dismissible">';
                $html .= '<p>' . __( 'Events not imported.', 'pickle-calendar' ) . '</p>';
            $html .= '</div>';

        endif;

        echo $html; // phpcs:ignore
    }

    /**
     * Get admin page.
     *
     * @access public
     * @param bool $template_name (default: false).
     * @return string
     */
    public function get_admin_page( $template_name = false ) {
        if ( ! $template_name ) {
            return false;
        }

        ob_start();

        do_action( 'pickle_calendar_before_admin_' . $template_name );

        include( PICKLE_CALENDAR_PATH . 'admin/pages/' . $template_name . '.php' );

        do_action( 'pickle_calendar_after_admin_' . $template_name );

        $html = ob_get_contents();

        ob_end_clean();

        return $html;
    }

}

new Pickle_Calendar_Admin();
