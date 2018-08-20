<?php
/**
 * Main Pickle Calendar class
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

/**
 * Final PickleCalendar class.
 *
 * @final
 */
final class PickleCalendar {

    /**
     * Version
     *
     * @var string
     * @access public
     */
    public $version = '1.3.0';

    /**
     * Settings.
     *
     * (default value: '').
     *
     * @var string
     * @access public
     */
    public $settings = '';

    /**
     * Calendar.
     *
     * (default value: '').
     *
     * @var string
     * @access public
     */
    public $calendar = '';

    /**
     * Import/Export events.
     *
     * (default value: '').
     *
     * @var string
     * @access public
     */
    public $import_export_events = '';

    /**
     * Admin
     *
     * (default value: '')
     *
     * @var string
     * @access public
     */
    public $admin = '';

    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->init();

        do_action( 'pickle_calendar_loaded' );
    }

    /**
     * Define constants function.
     *
     * @access private
     * @return void
     */
    private function define_constants() {
        $this->define( 'PICKLE_CALENDAR_PATH', plugin_dir_path( __FILE__ ) );
        $this->define( 'PICKLE_CALENDAR_URL', plugin_dir_url( __FILE__ ) );
        $this->define( 'PICKLE_CALENDAR_VERSION', $this->version );
        $this->define( 'PICKLE_CALENDAR_REQUIRES', '3.8' );
        $this->define( 'PICKLE_CALENDAR_TESTED', '4.9.5' );
    }

    /**
     * Define function.
     *
     * @access private
     * @param mixed $name (name).
     * @param mixed $value (value).
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Includes function.
     *
     * @access public
     * @return void
     */
    public function includes() {
        include_once( PICKLE_CALENDAR_PATH . 'update-functions.php' );
        include_once( PICKLE_CALENDAR_PATH . 'class-pickle-calendar-install.php' );
        include_once( PICKLE_CALENDAR_PATH . 'functions.php' );
        include_once( PICKLE_CALENDAR_PATH . 'admin/admin.php' );
        include_once( PICKLE_CALENDAR_PATH . 'admin/functions.php' );
        include_once( PICKLE_CALENDAR_PATH . 'class-pickle-calendar.php' );
        include_once( PICKLE_CALENDAR_PATH . 'admin/class-pickle-calendar-event-details.php' );
        include_once( PICKLE_CALENDAR_PATH . 'class-pickle-calendar-post-types.php' );
        include_once( PICKLE_CALENDAR_PATH . 'class-pickle-calendar-import-export-events.php' );

        if ( is_admin() ) {
            $this->admin = new Pickle_Calendar_Admin_Functions();
        }
    }

    /**
     * Init hooks function.
     *
     * @access private
     * @return void
     */
    private function init_hooks() {
        register_activation_hook( PICKLE_CALENDAR_PLUGIN_FILE, array( 'Pickle_Calendar_Install', 'install' ) );
    }

    /**
     * Init function.
     *
     * @access public
     * @return void
     */
    public function init() {
        $this->settings = $this->settings();
        $this->calendar = new Pickle_Calendar();
        $this->import_export_events = new Pickle_Calendar_Import_Export_Events();

        do_action( 'pickle_calendar_init' );
    }

    /**
     * Settings function.
     *
     * @access public
     * @return array
     */
    public function settings() {
        $default_settings = array(
            'adminlabel' => 'Events',
            'cpt_single' => 'Event',
            'cpt_plural' => 'Events',
            'enable_editor' => false,
            'show_start_date' => true,
            'show_end_date' => true,            
            'hide_weekends' => false,
        );

        $db_settings = get_option( 'pickle_calendar_settings', '' );

        $settings = $this->parse_args( $db_settings, $default_settings );

        $settings['taxonomies'] = get_option( 'pickle_calendar_taxonomies', '' );

        return $settings;
    }

    /**
     * Update settings function.
     *
     * @access public
     * @return void
     */
    public function update_settings() {
        $this->settings = $this->settings();
    }

    /**
     * Parse args function.
     *
     * @access public
     * @param mixed $a (array).
     * @param mixed $b (array).
     * @return array
     */
    public function parse_args( &$a, $b ) {
        $a = (array) $a;
        $b = (array) $b;
        $result = $b;

        foreach ( $a as $k => &$v ) {
            if ( is_array( $v ) && isset( $result[ $k ] ) ) {
                $result[ $k ] = $this->parse_args( $v, $result[ $k ] );
            } else {
                $result[ $k ] = $v;
            }
        }

        return $result;
    }

}

/**
 * Main function.
 *
 * @access public
 * @return class
 */
function picklecalendar() {
    return new PickleCalendar();
}

// Global for backwards compatibility.
$GLOBALS['picklecalendar'] = picklecalendar();
