<?php

class Pickle_Calendar_Admin {
	
	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'update_settings'));
	}

	public function admin_scripts_styles() {
		wp_enqueue_script('pickle-calendar-admin-script', PICKLE_CALENDAR_URL.'js/admin.js', array('jquery'), picklecalendar()->version, true);
		
		wp_enqueue_style('pickle-calendar-admin-css', PICKLE_CALENDAR_URL.'css/admin.css', '', picklecalendar()->version);
	}
	
	public function admin_menu() {
		add_options_page('Pickle Calendar', 'Pickle Calendar', 'manage_options', 'pickle-calendar', array($this, 'settings_page'));
	}
	
	public function settings_page() {
		$html='';

		$html.='<div class="wrap">';
			$html.='<h1>Pickle Calendar</h1>';
			
			$html.='<form class="pickle-calendar-settings-form" action="" method="post">';
				$html.=wp_nonce_field('update_settings', 'pickle_calendar_admin', true, false);	
				
				$html.='<h2>General</h2>';
				
				$html.='<table class="form-table">';
					$html.='<tbody>';
					
						$html.='<tr>';
							$html.='<th scope="row"><label for="adminlabel">Admin Label</label></th>';
							$html.='<td><input name="settings[adminlabel]" type="text" id="adminlabel" value="'.picklecalendar()->settings['adminlabel'].'" class="regular-text"></td>';
						$html.='</tr>';
					
						$html.='<tr>';
							$html.='<th scope="row"><label for="cpt_single">Post Type Label (single)</label></th>';
							$html.='<td><input name="settings[cpt_single]" type="text" id="cpt_single" value="'.picklecalendar()->settings['cpt_single'].'" class="regular-text"></td>';
						$html.='</tr>';
					
						$html.='<tr>';
							$html.='<th scope="row"><label for="cpt_plural">Post Type Label (plural)</label></th>';
							$html.='<td><input name="settings[cpt_plural]" type="text" id="cpt_plural" value="'.picklecalendar()->settings['cpt_plural'].'" class="regular-text"></td>';
						$html.='</tr>';

						$html.='<tr>';
							$html.='<th scope="row"><label for="tax_single">Taxonomy Label (single)</label></th>';
							$html.='<td><input name="settings[tax_single]" type="text" id="tax_single" value="'.picklecalendar()->settings['tax_single'].'" class="regular-text"></td>';
						$html.='</tr>';	

						$html.='<tr>';
							$html.='<th scope="row"><label for="tax_plural">Taxonomy Label (plural)</label></th>';
							$html.='<td><input name="settings[tax_plural]" type="text" id="tax_plural" value="'.picklecalendar()->settings['tax_plural'].'" class="regular-text"></td>';
						$html.='</tr>';											
					
					$html.='</tbody>';				
				$html.='</table>';	
				
				$html.='<h2>Post Details (metabox)</h2>';	

				$html.='<table class="form-table">';
					$html.='<tbody>';
					
						$html.='<tr>';
							$html.='<th scope="row"><label for="include_details">Details Box</label></th>';
							$html.='<td><label for="include_details"><input name="settings[include_details]" type="checkbox" id="include_details" value="1" '.checked(picklecalendar()->settings['include_details'], 1, false).'>Show Details Box</label>';
						$html.='</tr>';
					
						$html.='<tr class="details-box">';
							$html.='<th scope="row"><label for="start_date">Show Start Date</label></th>';
							$html.='<td><label for="start_date"><input name="settings[detail_options][start_date]" type="checkbox" id="start_date" value="1" '.checked(picklecalendar()->settings['detail_options']['start_date'], 1, false).'>Show Start Date Box</label>';
						$html.='</tr>';
					
						$html.='<tr class="details-box">';
							$html.='<th scope="row"><label for="end_date">Show End Date</label></th>';
							$html.='<td><label for="end_date"><input name="settings[detail_options][end_date]" type="checkbox" id="end_date" value="1" '.checked(picklecalendar()->settings['detail_options']['end_date'], 1, false).'>Show End Date Box</label>';
						$html.='</tr>';											
					
					$html.='</tbody>';				
				$html.='</table>';	
				
				$html.='<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>';
				
			$html.='</form>';
			
		$html.='</div>';
echo '<pre>';
print_r(picklecalendar()->settings);
echo '</pre>';		
		echo $html;
	}
	
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
	
}	

new Pickle_Calendar_Admin();
?>