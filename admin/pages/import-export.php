<h2>Import/Export</h2>

<div class="metabox-holder">
    <div class="postbox">
        <h3><span><?php _e( 'Export Events' ); ?></span></h3>
        <div class="inside">
            <p><?php _e( 'Export events for this site as a .json file. This allows you to easily import the events into another site.' ); ?></p>
            <form method="post">
                <p><input type="hidden" name="pc_action" value="export_events" /></p>
                <p>
                    <?php wp_nonce_field( 'pickle_calendar_export_events_nonce', 'pickle_calendar_export_events_nonce' ); ?>
                    <?php submit_button( __( 'Export Events' ), 'secondary', 'submit', false ); ?>
                </p>
            </form>
        </div><!-- .inside -->
    </div><!-- .postbox -->

    <div class="postbox">
        <h3><span><?php _e( 'Import Events' ); ?></span></h3>
        <div class="inside">
            <p><?php _e( 'Import the events from a .json file. This file can be obtained by exporting the events on another site using the form above.' ); ?></p>
            <form method="post" enctype="multipart/form-data">
                <p>
                    <input type="file" name="import_file"/>
                </p>
                <p>
                    <input type="hidden" name="pc_action" value="import_events" />
                    <?php wp_nonce_field( 'pickle_calendar_import_events_nonce', 'pickle_calendar_import_events_nonce' ); ?>
                    <?php submit_button( __( 'Import Events' ), 'secondary', 'submit', false ); ?>
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
                    <?php wp_nonce_field( 'pickle_calendar_export_nonce', 'pickle_calendar_export_nonce' ); ?>
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
