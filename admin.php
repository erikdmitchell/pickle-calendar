<?php

class Pickle_Calendar_Admin {
	
	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'update_settings'));
	}

	public function admin_scripts_styles() {

	}
	
	public function admin_menu() {
		add_options_page('Pickle Calendar', 'Pickle Calendar', 'manage_options', 'pickle-calendar', array($this, 'settings_page'));
	}
	
	public function settings_page() {
		$html='';

		$html.='<div class="wrap">';
			$html.='<h1>Pickle Calendar</h1>';
			
			$html.='<form action="" method="post">';
				$html.=wp_nonce_field('update_settings', 'pickle_calendar_admin', true, false);	
				
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

		$new_settings=wp_parse_args($_POST['settings'], picklecalendar()->settings);
		
		update_option('pickle_calendar_settings', $new_settings);
		
		wp_redirect(site_url($_POST['_wp_http_referer']));
		exit;
	}
	
}	

new Pickle_Calendar_Admin();
?>