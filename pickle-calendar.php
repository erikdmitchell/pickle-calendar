<?php
/*
 * Plugin Name: Pickle Calendar
 * Plugin URI: 
 * Description: Pickle Calendar
 * Version: 1.2.0-beta.4
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

if (!defined('PICKLE_CALENDAR_PLUGIN_FILE')) {
	define('PICKLE_CALENDAR_PLUGIN_FILE', __FILE__);
}

// our main class //

final class PickleCalendar {

	public $version='1.2.0-beta.4';
	
	public $settings='';
	
	public $calendar='';
	
	public $import_export_events='';
	
	public $admin='';

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
		$this->init();

		do_action('pickle_calendar_loaded');
	}

	private function define_constants() {
		$this->define('PICKLE_CALENDAR_PATH', plugin_dir_path(__FILE__));
		$this->define('PICKLE_CALENDAR_URL', plugin_dir_url(__FILE__));
		$this->define('PICKLE_CALENDAR_VERSION', $this->version);
		$this->define('PICKLE_CALENDAR_REQUIRES', '3.8');
		$this->define('PICKLE_CALENDAR_TESTED', '4.9.5');				
	}

	private function define($name, $value) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function includes() {
		include_once(PICKLE_CALENDAR_PATH.'update-functions.php');
		include_once(PICKLE_CALENDAR_PATH.'install.php');
		include_once(PICKLE_CALENDAR_PATH.'functions.php');
		include_once(PICKLE_CALENDAR_PATH.'admin/admin.php');
		include_once(PICKLE_CALENDAR_PATH.'admin/functions.php');
		include_once(PICKLE_CALENDAR_PATH.'calendar.php');
		include_once(PICKLE_CALENDAR_PATH.'metabox.php');
		include_once(PICKLE_CALENDAR_PATH.'post-type.php');
		include_once(PICKLE_CALENDAR_PATH.'import-export.php');
		include_once(PICKLE_CALENDAR_PATH.'updater/updater.php');
		
		if (is_admin())
		    $this->admin=new Pickle_Calendar_Admin_Functions();
	}
	
	private function init_hooks() {
		register_activation_hook(PICKLE_CALENDAR_PLUGIN_FILE, array('Pickle_Calendar_Install', 'install'));
		
		add_action('admin_init', array($this, 'update_plugin'));
	}

	public function init() {		
		$this->settings=$this->settings();
		$this->calendar=new Pickle_Calendar();
		$this->import_export_events=new Pickle_Calendar_Import_Export_Events();

		do_action('pickle_calendar_init');
	}

	public function settings() {		
		$default_settings=array(
			'adminlabel' => 'Events',
			'cpt_single' => 'Event',
			'cpt_plural' => 'Events',
			'disable_editor' => false,
			'include_details' => true,
			'detail_options' => array(
				'start_date' => true,
				'end_date' => true,	
			),
		);
		
		$db_settings=get_option('pickle_calendar_settings', '');
		
		$settings=$this->parse_args($db_settings, $default_settings);
		
		$settings['taxonomies']=get_option('pickle_calendar_taxonomies', '');
		
		return $settings;
	}
	
	public function update_settings() {
		$this->settings=$this->settings();
	}

    public function update_plugin() {    
    	$this->define( 'PICKLE_CALENDAR_GITHUB_FORCE_UPDATE', true );
    	
		$username='erikdmitchell';
		$repo_name='pickle-calendar';
		$folder_name='pickle-calendar';    	
    
    	if ( is_admin() ) :
    
    		$config = array(
    			'slug' => plugin_basename( __FILE__ ),
    			'proper_folder_name' => $folder_name,
    			'api_url' => 'https://api.github.com/repos/'.$username.'/'.$repo_name,
    			'raw_url' => 'https://raw.github.com/'.$username.'/'.$repo_name.'/master',
    			'github_url' => 'https://github.com/'.$username.'/'.$repo_name,
    			'zip_url' => 'https://github.com/'.$username.'/'.$repo_name.'/zipball/master',
    			'sslverify' => true,
    			'requires' => PICKLE_CALENDAR_REQUIRES,
    			'tested' => PICKLE_CALENDAR_TESTED,
    			'readme' => 'readme.txt',
    		);   		
    
    		new Pickle_Calender_GitHub_Updater( $config );
    	endif;
    }
	
	public function parse_args(&$a, $b) {
		$a = (array) $a;
		$b = (array) $b;
		$result = $b;
		
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = $this->parse_args($v, $result[ $k ]);
			} else {
				$result[ $k ] = $v;
			}
		}
		
		return $result;
	}

}

function picklecalendar() {
	return new PickleCalendar();
}

// Global for backwards compatibility.
$GLOBALS['picklecalendar'] = picklecalendar();