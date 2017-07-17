<?php
/*
 * Plugin Name: Pickle Calendar
 * Plugin URI: 
 * Description: Pickle Calendar
 * Version: 1.0.0
 * Author: Erik Mitchell
 * Author URI: 
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: pickle-calendar
 * Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// our main class //

final class PickleCalendar {

	public $version='1.0.0';
	
	public $settings='';

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init();

		do_action( 'pickle_calendar_loaded' );
	}

	private function define_constants() {
		$this->define('PICKLE_CALENDAR_PATH', plugin_dir_path(__FILE__));
		$this->define('PICKLE_CALENDAR_URL', plugin_dir_url(__FILE__));
	}

	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function includes() {
		include_once(PICKLE_CALENDAR_PATH.'admin.php');
		include_once(PICKLE_CALENDAR_PATH.'calendar.php');
		include_once(PICKLE_CALENDAR_PATH.'calendar-metabox.php');
	}

	public function init() {
		$this->settings=$this->settings();

		do_action( 'pickle_calendar_init' );
	}


	public function settings($settings='') {
		$default_settings=array(
			'adminlabel_single' => 'Class',
			'adminlabel_plural' => 'Classes',
		);
		$settings=wp_parse_args($settings, $default_settings);
		
		return $settings;
	}

}

function picklecalendar() {
	return new PickleCalendar();
}

// Global for backwards compatibility.
$GLOBALS['picklecalendar'] = picklecalendar();
?>
