<h2>Settings</h2>

<form class="pickle-calendar-settings-form" action="" method="post">
    <?php wp_nonce_field( 'update_settings', 'pickle_calendar_admin', true ); ?>  
    
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
        
        </tbody>                
    </table>    
    
    <h2>Post Details (metabox)</h2> 

    <table class="form-table">
        <tbody>
        
            <tr>
                <th scope="row"><label for="disable_editor">Disable Editor</label></th>
                <td><label for="disable_editor"><input name="settings[disable_editor]" type="checkbox" id="disable_editor" value="1" <?php checked( picklecalendar()->settings['disable_editor'], 1 ); ?>>Disable Editor</label></td>
            </tr>
        
            <tr>
                <th scope="row"><label for="include_details">Details Box</label></th>
                <td><label for="include_details"><input name="settings[include_details]" type="checkbox" id="include_details" value="1" <?php checked( picklecalendar()->settings['include_details'], 1 ); ?>>Show Details Box</label></td>
            </tr>
        
            <tr class="details-box">
                <th scope="row"><label for="start_date">Show Start Date</label></th>
                <td><label for="start_date"><input name="settings[detail_options][start_date]" type="checkbox" id="start_date" value="1" <?php checked( picklecalendar()->settings['detail_options']['start_date'], 1 ); ?>>Show Start Date Box</label></td>
            </tr>
        
            <tr class="details-box">
                <th scope="row"><label for="end_date">Show End Date</label></th>
                <td><label for="end_date"><input name="settings[detail_options][end_date]" type="checkbox" id="end_date" value="1" <?php checked( picklecalendar()->settings['detail_options']['end_date'], 1 ); ?>>Show End Date Box</label></td>
            </tr>                                           
        
        </tbody>                
    </table>    
    
    <h2>Calendar Settings</h2> 

    <table class="form-table">
        <tbody>
        
            <tr>
                <th scope="row"><label for="hide_weekends">Hide Weekends</label></th>
                <td>
                    <label for="hide_weekends"><input name="settings[hide_weekends]" type="checkbox" id="hide_weekends" value="1" <?php checked( picklecalendar()->settings['hide_weekends'], 1 ); ?>>Hide Weekends</label>
                    <p class="description">This will show only weekdays (Monday thru Friday) on the calendar.</p>
                </td>
            </tr>                                          
        
        </tbody>                
    </table> 
        
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
    
</form>

