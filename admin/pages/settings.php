<?php
/**
 * Settings admin page.
 *
 * @package PickleCalendar
 * @since   1.0.0
 */

?>

<h2>Settings</h2>

<form class="pickle-calendar-settings-form" action="" method="post">
    <?php wp_nonce_field( 'update_settings', 'pickle_calendar_admin', true ); ?>  
    
    <h2>General</h2>
    
    <table class="form-table">
        <tbody>
        
            <tr>
                <th scope="row"><label for="adminlabel">Admin Label</label></th>
                <td><input name="settings[adminlabel]" type="text" id="adminlabel" value="<?php echo esc_attr( picklecalendar()->settings['adminlabel'] ); ?>" class="regular-text"></td>
            </tr>
        
            <tr>
                <th scope="row"><label for="cpt_single">Post Type Label (single)</label></th>
                <td><input name="settings[cpt_single]" type="text" id="cpt_single" value="<?php echo esc_attr( picklecalendar()->settings['cpt_single'] ); ?>" class="regular-text"></td>
            </tr>
        
            <tr>
                <th scope="row"><label for="cpt_plural">Post Type Label (plural)</label></th>
                <td><input name="settings[cpt_plural]" type="text" id="cpt_plural" value="<?php echo esc_attr( picklecalendar()->settings['cpt_plural'] ); ?>" class="regular-text"></td>
            </tr>
        
        </tbody>                
    </table>    
    
    <h2>Post Details (metabox)</h2> 

    <table class="form-table">
        <tbody>
        
            <tr>
                <th scope="row"><label for="enable_editor">Enable Editor</label></th>
                <td><label for="enable_editor"><input name="settings[enable_editor]" type="checkbox" id="enable_editor" value="1" <?php checked( picklecalendar()->settings['enable_editor'], 1 ); ?>>Enable Editor</label></td>
            </tr>
        
            <tr>
                <th scope="row"><label for="show_start_date">Show Start Date</label></th>
                <td><label for="show_start_date"><input name="settings[show_start_date]" type="checkbox" id="show_start_date" value="1" <?php checked( picklecalendar()->settings['show_start_date'], 1 ); ?>>Show Start Date Box</label></td>
            </tr>
        
            <tr>
                <th scope="row"><label for="show_end_date">Show End Date</label></th>
                <td><label for="show_end_date"><input name="settings[show_end_date]" type="checkbox" id="show_end_date" value="1" <?php checked( picklecalendar()->settings['show_end_date'], 1 ); ?>>Show End Date Box</label></td>
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

