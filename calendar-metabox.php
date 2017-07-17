<?php

class Pickle_Calendar_Event_Details {

    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        if (is_admin()) :
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        endif;
    }

    /**
     * init_metabox function.
     * 
     * @access public
     * @return void
     */
    public function init_metabox() {
        add_action('add_meta_boxes', array($this, 'add_metabox' ));
        add_action('save_post', array($this, 'save_metabox'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'));
    }
    
    /**
     * admin_scripts_styles function.
     * 
     * @access public
     * @return void
     */
    public function admin_scripts_styles() {
	    wp_enqueue_script('flatpickr-script', PICKLE_CALENDAR_URL.'js/flatpickr.min.js', array('jquery'), '2.6.1', true);
	    wp_enqueue_script('bted-script', PICKLE_CALENDAR_URL.'js/event-details.js', array('flatpickr-script'), '0.1.0', true);
	    
	    wp_enqueue_style('flatpickr-style', PICKLE_CALENDAR_URL.'css/flatpickr.min.css', '', '2.6.1');
	    wp_enqueue_style('bted-style', PICKLE_CALENDAR_URL.'css/event-details.css', '', '0.1.0');
    }

    /**
     * add_metabox function.
     * 
     * @access public
     * @return void
     */
    public function add_metabox() {
        add_meta_box(
            'event-details',
            __('Event Details', 'pickle-calendar' ),
            array( $this, 'render_metabox' ),
            'pcevent',
            'advanced',
            'default'
        );
 
    }

    /**
     * render_metabox function.
     * 
     * @access public
     * @param mixed $post
     * @return void
     */
    public function render_metabox( $post ) {
        wp_nonce_field('update_settings', 'boomi_trust_admin');
        
        $html='';
        
        $html.='<div class="mb-row">';
        	$html.='<label for="event-date">Date</label>';
	        $html.='<input type="text" name="event[date]" id="event-date" class="bted-pickr" value="'.get_post_meta($post->ID, '_event_date', true).'" />';
	    $html.='</div>';
        
        echo $html;
    }

    /**
     * save_metabox function.
     * 
     * @access public
     * @param mixed $post_id
     * @param mixed $post
     * @return void
     */
    public function save_metabox( $post_id, $post ) {
        $nonce_name   = isset( $_POST['boomi_trust_admin'] ) ? $_POST['boomi_trust_admin'] : '';
        $nonce_action = 'update_settings';
 
        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        update_post_meta($post_id, '_event_date', $_POST['event']['date']);
    }
}
 
new Pickle_Calendar_Event_Details();
?>