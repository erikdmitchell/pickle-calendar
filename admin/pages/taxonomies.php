<h2>Taxonomies</h2>

<a href="#" class="page-title-action">Add New</a>




<table class="wp-list-table widefat fixed striped pickle-calendar-taxonomies">
	<thead>
		<tr>
			<td id="cb" class="manage-column column-cb check-column">
				<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
				<input id="cb-select-all-1" type="checkbox">
			</td>
			<th scope="col" id="title" class="manage-column column-title column-primary">
				<a href="#"><span>Title</span></a>
			</th>
		</tr>
	</thead>

	<tbody id="the-list">
		<tr id="post-1086" class="iedit author-other level-0 post-1086 type-page status-publish hentry">
			<th scope="row" class="check-column">
				<label class="screen-reader-text" for="cb-select-1086">Select About</label>
				<input id="cb-select-1086" type="checkbox" name="post[]" value="1086">
			</th>
			<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
				<strong><a class="row-title" href="http://plugins.dev/wp-admin/post.php?post=1086&amp;action=edit" aria-label="“About” (Edit)">About</a></strong>
			</td>
		</tr>
	</tbody>
</table>



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