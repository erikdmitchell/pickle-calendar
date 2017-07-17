<?php

class Pickle_Calendar_Admin {
	
	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'));
		add_action('admin_menu', array($this, 'admin_menu'));
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
							$html.='<th scope="row"><label for="adminlabel_single">Admin Label (single)</label></th>';
							$html.='<td><input name="adminlabel_single" type="text" id="adminlabel_single" value="'.picklecalendar()->settings['adminlabel_single'].'" class="regular-text"></td>';
						$html.='</tr>';
					
						$html.='<tr>';
							$html.='<th scope="row"><label for="adminlabel_plural">Admin Label (plural)</label></th>';
							$html.='<td><input name="adminlabel_plural" type="text" id="adminlabel_plural" value="'.picklecalendar()->settings['adminlabel_plural'].'" class="regular-text"></td>';
						$html.='</tr>';
					
					$html.='</tbody>';				
				$html.='</table>';		
				
				$html.='<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>';
				
			$html.='</form>';
			
		$html.='</div>';
		
		echo $html;
	}
	
}	

new Pickle_Calendar_Admin();
?>