<?php

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
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'));
		add_action('admin_init', array($this, 'process_events_export'));
		add_action('admin_init', array($this, 'process_events_import'));				
		add_action('admin_init', array($this, 'process_settings_export'));		
		add_action('admin_init', array($this, 'process_settings_import'));
		add_action('admin_init', array($this, 'update_settings'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_notices', array($this, 'admin_notices'));
	}

	/**
	 * admin_scripts_styles function.
	 * 
	 * @access public
	 * @return void
	 */
	public function admin_scripts_styles() {
		wp_enqueue_script('pickle-calendar-admin-script', PICKLE_CALENDAR_URL.'js/admin.js', array('jquery'), picklecalendar()->version, true);
				
		wp_enqueue_style('pickle-calendar-admin-css', PICKLE_CALENDAR_URL.'css/admin.css', '', picklecalendar()->version);
	}
	
	/**
	 * admin_menu function.
	 * 
	 * @access public
	 * @return void
	 */
	public function admin_menu() {
		add_options_page('Pickle Calendar', 'Pickle Calendar', 'manage_options', 'pickle-calendar', array($this, 'settings_page'));
	}
	
	/**
	 * settings_page function.
	 * 
	 * @access public
	 * @return void
	 */
	public function settings_page() {
		?>

		<div class="wrap">
			<h1>Pickle Calendar</h1>
			
			<form class="pickle-calendar-settings-form" action="" method="post">
				<?php wp_nonce_field('update_settings', 'pickle_calendar_admin', true); ?>	
				
				<h2>General</h2>
				
				<table class="form-table">
					<tbody>
					
						<tr>
							<th scope="row"><label for="adminlabel">Admin Label</label></th>
							<td><input name="settings[adminlabel]" type="text" id="adminlabel" value="<?php echo picklecalendar()->settings['adminlabel']; ?>" class="regular-text"></td>
						</tr>
					
						<tr>
							<th scope="row"><label for="cpt_single">Post Type Label (single)</label></th>
							<td><input name="settings[cpt_single]" type="text" id="cpt_single" value="<?php echo picklecalendar()->settings['cpt_single']; ?>" class="regular-text"></td>
						</tr>
					
						<tr>
							<th scope="row"><label for="cpt_plural">Post Type Label (plural)</label></th>
							<td><input name="settings[cpt_plural]" type="text" id="cpt_plural" value="<?php echo picklecalendar()->settings['cpt_plural']; ?>" class="regular-text"></td>
						</tr>

						<tr>
							<th scope="row"><label for="tax_single">Taxonomy Label (single)</label></th>
							<td><input name="settings[tax_single]" type="text" id="tax_single" value="<?php echo picklecalendar()->settings['tax_single']; ?>" class="regular-text"></td>
						</tr>	

						<tr>
							<th scope="row"><label for="tax_plural">Taxonomy Label (plural)</label></th>
							<td><input name="settings[tax_plural]" type="text" id="tax_plural" value="<?php echo picklecalendar()->settings['tax_plural']; ?>" class="regular-text"></td>
						</tr>											
					
					</tbody>				
				</table>	
				
				<h2>Post Details (metabox)</h2>	

				<table class="form-table">
					<tbody>
					
						<tr>
							<th scope="row"><label for="disable_editor">Disable Editor</label></th>
							<td><label for="disable_editor"><input name="settings[disable_editor]" type="checkbox" id="disable_editor" value="1" <?php checked(picklecalendar()->settings['disable_editor'], 1); ?>>Disable Editor</label>
						</tr>
					
						<tr>
							<th scope="row"><label for="include_details">Details Box</label></th>
							<td><label for="include_details"><input name="settings[include_details]" type="checkbox" id="include_details" value="1" <?php checked(picklecalendar()->settings['include_details'], 1); ?>>Show Details Box</label>
						</tr>
					
						<tr class="details-box">
							<th scope="row"><label for="start_date">Show Start Date</label></th>
							<td><label for="start_date"><input name="settings[detail_options][start_date]" type="checkbox" id="start_date" value="1" <?php checked(picklecalendar()->settings['detail_options']['start_date'], 1); ?>>Show Start Date Box</label>
						</tr>
					
						<tr class="details-box">
							<th scope="row"><label for="end_date">Show End Date</label></th>
							<td><label for="end_date"><input name="settings[detail_options][end_date]" type="checkbox" id="end_date" value="1" <?php checked(picklecalendar()->settings['detail_options']['end_date'], 1); ?>>Show End Date Box</label>
						</tr>											
					
					</tbody>				
				</table>	
				
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
				
			</form>

			<div class="metabox-holder">
				<div class="postbox">
					<h3><span><?php _e('Export Events'); ?></span></h3>
					<div class="inside">
						<p><?php _e( 'Export events for this site as a .json file. This allows you to easily import the events into another site.' ); ?></p>
						<form method="post">
							<p><input type="hidden" name="pc_action" value="export_events" /></p>
							<p>
								<?php wp_nonce_field('pickle_calendar_export_events_nonce', 'pickle_calendar_export_events_nonce'); ?>
								<?php submit_button( __( 'Export Events' ), 'secondary', 'submit', false ); ?>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- .postbox -->
	
				<div class="postbox">
					<h3><span><?php _e('Import Events'); ?></span></h3>
					<div class="inside">
						<p><?php _e( 'Import the events from a .json file. This file can be obtained by exporting the events on another site using the form above.' ); ?></p>
						<form method="post" enctype="multipart/form-data">
							<p>
								<input type="file" name="import_file"/>
							</p>
							<p>
								<input type="hidden" name="pc_action" value="import_events" />
								<?php wp_nonce_field('pickle_calendar_import_events_nonce', 'pickle_calendar_import_events_nonce' ); ?>
								<?php submit_button( __('Import Events'), 'secondary', 'submit', false ); ?>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- .postbox -->
			</div><!-- .metabox-holder -->
			
			<div class="metabox-holder">
				<div class="postbox">
					<h3><span><?php _e( 'Export Settings' ); ?></span></h3>
					<div class="inside">
						<p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.' ); ?></p>
						<form method="post">
							<p><input type="hidden" name="pc_action" value="export_settings" /></p>
							<p>
								<?php wp_nonce_field('pickle_calendar_export_nonce', 'pickle_calendar_export_nonce'); ?>
								<?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- .postbox -->
	
				<div class="postbox">
					<h3><span><?php _e( 'Import Settings' ); ?></span></h3>
					<div class="inside">
						<p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></p>
						<form method="post" enctype="multipart/form-data">
							<p>
								<input type="file" name="import_file"/>
							</p>
							<p>
								<input type="hidden" name="pc_action" value="import_settings" />
								<?php wp_nonce_field( 'pickle_calendar_import_nonce', 'pickle_calendar_import_nonce' ); ?>
								<?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- .postbox -->
			</div><!-- .metabox-holder -->

		</div><!-- wrap -->
		
		<?php
	}
	
	/**
	 * update_settings function.
	 * 
	 * @access public
	 * @return void
	 */
	public function update_settings() {
		if (!isset($_POST['pickle_calendar_admin']) || !wp_verify_nonce($_POST['pickle_calendar_admin'], 'update_settings'))
			return false; 

		$new_settings=picklecalendar()->parse_args($_POST['settings'], picklecalendar()->settings);
		
		// for checkboxes //
		foreach ($new_settings as $key => $value) :
			if (is_array($value)) :
				foreach ($value as $sub_key => $sub_value) :
					if (!isset($_POST['settings'][$key][$sub_key])) :
						$new_settings[$key][$sub_key]=0;
					endif;
				endforeach;
			else :		
				if (!isset($_POST['settings'][$key])) :
					$new_settings[$key]=0;
				endif;
			endif;
		endforeach;
		
		update_option('pickle_calendar_settings', $new_settings);
		
		wp_redirect(site_url($_POST['_wp_http_referer']));
		exit;
	}
	
	/**
	 * process_settings_export function.
	 * 
	 * @access public
	 * @return void
	 */
	public function process_settings_export() {
		if (empty($_POST['pc_action']) || $_POST['pc_action']!='export_settings')
			return;
					
		if (!isset($_POST['pickle_calendar_export_nonce']) || !wp_verify_nonce($_POST['pickle_calendar_export_nonce'], 'pickle_calendar_export_nonce'))
			return;

		if (!current_user_can('manage_options'))
			return;
			
		$settings=get_option('pickle_calendar_settings');
		
		ignore_user_abort(true);
		
		nocache_headers();
		
		header('Content-Type: application/json; charset=utf-8');
		header('Content-Disposition: attachment; filename=pickle-calendar-settings-export-'.date('m-d-Y').'.json');
		header("Expires: 0");
		
		echo json_encode($settings);
		
		exit;
	}	

	/**
	 * process_settings_import function.
	 * 
	 * @access public
	 * @return void
	 */
	public function process_settings_import() {
		if (empty($_POST['pc_action']) || $_POST['pc_action']!='import_settings')
			return;
					
		if (!isset($_POST['pickle_calendar_import_nonce']) || !wp_verify_nonce($_POST['pickle_calendar_import_nonce'], 'pickle_calendar_import_nonce'))
			return;

		if (!current_user_can('manage_options'))
			return;
			
		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );
		
		if ( $extension != 'json' ) {
			wp_die( __( 'Please upload a valid .json file' ) );
		}
		
		$import_file = $_FILES['import_file']['tmp_name'];
		
		if ( empty( $import_file ) ) {
			wp_die( __( 'Please upload a file to import' ) );
		}
		
		// Retrieve the settings from the file and convert the json object to an array.
		$settings=json_decode(file_get_contents($import_file), true);
		
		update_option('pickle_calendar_settings', $settings);
		
		wp_safe_redirect(admin_url('options-general.php?page=pickle-calendar')); 
		
		exit;
	}

	/**
	 * process_events_export function.
	 * 
	 * @access public
	 * @return void
	 */
	public function process_events_export() {
		if (empty($_POST['pc_action']) || $_POST['pc_action']!='export_events')
			return;
					
		if (!isset($_POST['pickle_calendar_export_events_nonce']) || !wp_verify_nonce($_POST['pickle_calendar_export_events_nonce'], 'pickle_calendar_export_events_nonce'))
			return;

		if (!current_user_can('manage_options'))
			return;
			
		picklecalendar()->import_export_events->export();
	}

	public function process_events_import() {
		if (empty($_POST['pc_action']) || $_POST['pc_action']!='import_events')
			return;
					
		if (!isset($_POST['pickle_calendar_import_events_nonce']) || !wp_verify_nonce($_POST['pickle_calendar_import_events_nonce'], 'pickle_calendar_import_events_nonce'))
			return;

		if (!current_user_can('manage_options'))
			return;
			
		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );
		
		if ( $extension != 'json' ) {
			wp_die( __( 'Please upload a valid .json file' ) );
		}
		
		$import_file = $_FILES['import_file']['tmp_name'];
		
		if ( empty( $import_file ) ) {
			wp_die( __( 'Please upload a file to import' ) );
		}
		
		// Retrieve the settings from the file.
		$events=json_decode(file_get_contents($import_file));
		
		$imported=picklecalendar()->import_export_events->import($events);
		
		if ($imported) :
			$imported_url_var=1;
		else :
			$imported_url_var=0;
		endif;
		
		wp_safe_redirect(admin_url('options-general.php?page=pickle-calendar&import-events='.$imported_url_var)); 
		
		exit;
	}
	
	public function admin_notices() {
		$html='';
		$screen=get_current_screen();
		
		if ($screen->id !== 'settings_page_pickle-calendar')
			return;

		
		if (isset($_GET['import-events'])) :
			
			if ($_GET['import-events'] === 'true') :
				
				$html.='<div class="notice notice-success is-dismissible">';
					$html.='<p>'._('Events successfully imported.', 'pickle-calendar').'</p>';
				$html.='</div>';
				
			else :
				
				$html.='<div class="notice notice-error is-dismissible">';
					$html.='<p>'._('Events not imported.', 'pickle-calendar').'</p>';
				$html.='</div>';
				
			endif;
		
		endif;
		
		echo $html;	
	}
	
}	

new Pickle_Calendar_Admin();
?>