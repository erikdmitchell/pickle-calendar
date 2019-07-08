<?php
/**
 * Import/Export admin page.
 *
 * @package PickleCalendar
 * @since   1.1.0
 */

?>

<h2>Import/Export</h2>

<div class="metabox-holder">
    <div class="postbox">
        <h3><span><?php esc_html_e( 'Export Events', 'pickle-calendar' ); ?></span></h3>
        <div class="inside">
            <p><?php esc_html_e( 'Export events for this site as a .json file. This allows you to easily import the events into another site.', 'pickle-calendar' ); ?></p>
            <form method="post">
                <p><input type="hidden" name="pc_action" value="exportesc_html_events" /></p>
                <p>
                    <?php wp_nonce_field( 'pickle_calendaresc_html_exportesc_html_events_nonce', 'pickle_calendaresc_html_exportesc_html_events_nonce' ); ?>
                    <?php submit_button( __( 'Export Events', 'pickle-calendar' ), 'secondary', 'submit', false ); ?>
                </p>
            </form>
        </div><!-- .inside -->
    </div><!-- .postbox -->

    <div class="postbox">
        <h3><span><?php esc_html_e( 'Import Events', 'pickle-calendar' ); ?></span></h3>
        <div class="inside">
            <p><?php esc_html_e( 'Import the events from a .json file. This file can be obtained by exporting the events on another site using the form above.', 'pickle-calendar' ); ?></p>
            <form method="post" enctype="multipart/form-data">
                <p>
                    <input type="file" name="import_file"/>
                </p>
                <p>
                    <input type="hidden" name="pc_action" value="importesc_html_events" />
                    <?php wp_nonce_field( 'pickle_calendar_importesc_html_events_nonce', 'pickle_calendar_importesc_html_events_nonce' ); ?>
                    <?php submit_button( __( 'Import Events', 'pickle-calendar' ), 'secondary', 'submit', false ); ?>
                </p>
            </form>
        </div><!-- .inside -->
    </div><!-- .postbox -->
</div><!-- .metabox-holder -->

<div class="metabox-holder">
    <div class="postbox">
        <h3><span><?php esc_html_e( 'Export Settings', 'pickle-calendar' ); ?></span></h3>
        <div class="inside">
            <p><?php esc_html_e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'pickle-calendar' ); ?></p>
            <form method="post">
                <p><input type="hidden" name="pc_action" value="export_settings" /></p>
                <p>
                    <?php wp_nonce_field( 'pickle_calendaresc_html_export_nonce', 'pickle_calendaresc_html_export_nonce' ); ?>
                    <?php submit_button( __( 'Export', 'pickle-calendar' ), 'secondary', 'submit', false ); ?>
                </p>
            </form>
        </div><!-- .inside -->
    </div><!-- .postbox -->

    <div class="postbox">
        <h3><span><?php esc_html_e( 'Import Settings', 'pickle-calendar' ); ?></span></h3>
        <div class="inside">
            <p><?php esc_html_e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'pickle-calendar' ); ?></p>
            <form method="post" enctype="multipart/form-data">
                <p>
                    <input type="file" name="import_file"/>
                </p>
                <p>
                    <input type="hidden" name="pc_action" value="import_settings" />
                    <?php wp_nonce_field( 'pickle_calendar_import_nonce', 'pickle_calendar_import_nonce' ); ?>
                    <?php submit_button( __( 'Import', 'pickle-calendar' ), 'secondary', 'submit', false ); ?>
                </p>
            </form>
        </div><!-- .inside -->
    </div><!-- .postbox -->
</div><!-- .metabox-holder -->
