<?php

class Pickle_Calendar_Import_Export_Events {
	
	protected $post_type='pcevent';
	
	protected $taxonomy='pctype';
	
	public function construct() {
		
	}
	
	public function export() {
		$events_export=array(
			'events' => $this->get_events(),
			'event_types' => $this->get_event_types(),
		);

		ignore_user_abort(true);
		
		nocache_headers();
		
		header('Content-Type: application/json; charset=utf-8');
		header('Content-Disposition: attachment; filename=pickle-calendar-events-export-'.date('m-d-Y').'.json');
		header("Expires: 0");
		
		echo json_encode($events_export);
		
		exit;			
	}
	
	protected function get_events() {
		global $wpdb;
		
		$events=$wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = '$this->post_type'");
		
		// get event dates and types //
		foreach ($events as $event) :
			$event->dates=picklecalendar()->calendar->get_event_dates($event->ID);
			$event->event_types=wp_get_post_terms($event->ID, $this->taxonomy); // object
		endforeach;
	
		return $events;
	}

	protected function get_event_types() {
		$tax_terms=(array) get_terms($this->taxonomy, array('get' => 'all'));
		$terms=array();

		if (!empty($tax_terms)) :       		
	       	// put terms in order with no child going before its parent
			while ($t = array_shift($tax_terms)) :
				if ( $t->parent == 0 || isset( $terms[$t->parent] ) ) :
					$terms[$t->term_id] = $t;
				else :
					$tax_terms[] = $t;
				endif;
			endwhile;
		endif;
		
		return $terms;
	}

	public function import($import_arr='') {
		if (empty($import_arr))
			return false;
		
		$events='';
		$event_types='';
echo "import<br>";	
		if (isset($import_arr->events))
			$events=$import_arr->events;
			
		if (isset($import_arr->event_types))
			$event_types=$import_arr->event_types;
			
		if (!empty($events)) :
			foreach ($events as $event) :
				$post=get_page_by_title($event->post_title, OBJECT, $this->post_type);
				$event_dates=$event->dates;
				$events_event_types=$event->event_types;
				
				unset($event->dates, $event->event_types);

				if ($post!=null) :
					$updated_post=$this->parse_object_args($event, $post);
					wp_update_post($updated_post);
					
					$post_id=$post->ID;
				else :
					$post_id=wp_insert_post(get_object_vars($event));
				endif;

				// use post id for event_dates and event_types
				$this->update_event_dates($post_id, $event_dates);
				$this->update_event_types($post_id, $events_event_types);
			endforeach;
		endif;			

		if (!empty($event_types)) :
			foreach ($event_types as $event_type) :
				$term_exists=term_exists($event_type->slug, $this->taxonomy, $event_type->parent);
				$clean_term=$this->setup_term($event_type);
				
				if (isset($term_exists['term_id'])) :		
					wp_update_term($event_type->term_id, $this->taxonomy, $clean_term);
		
				else :
					wp_insert_term($event_type->name, $this->taxonomy, $clean_term);
				endif;
			endforeach;
		endif;
exit;
		return true;				
	}
	
	protected function parse_object_args($args, $defaults='') {
	    if (is_object($args)) :
	        $r = get_object_vars( $args );
	    elseif ( is_array( $args ) ) :
	        $r =& $args;
	    else :
	        wp_parse_str( $args, $r );
	    endif;
	 
	    $defaults=get_object_vars($defaults);
	    
	    return array_merge($defaults, $r);
	}
	
	protected function update_event_dates($event_id=0, $event_dates=array()) {
		// from our save metabox code //
		global $wpdb;
		
		// delete all existing dates //
		$wpdb->query($wpdb->prepare(
			"DELETE FROM $wpdb->postmeta WHERE post_id = %d AND ( meta_key LIKE %s OR meta_key LIKE %s )",
			$event_id,
			'_start_date_%',
			'_end_date_%'
		));

		foreach ($event_dates as $key => $dates) :
			add_post_meta($event_id, '_start_date_'.sanitize_key($key), $dates->start_date);
			add_post_meta($event_id, '_end_date_'.sanitize_key($key), $dates->end_date);
		endforeach;
	}

	protected function update_event_types($event_id=0, $event_types=array()) {
		// remove existing terms //
		wp_set_object_terms($event_id, NULL, $this->taxonomy);
		
		foreach ($event_types as $event_type) :
			wp_set_object_terms($event_id, $event_type->term_id, $this->taxonomy, true);
		endforeach;
	}
	
	protected function setup_term($term='') {
		if (empty($term))
			return;
			
		$term=get_object_vars($term);
		
		unset($term['term_id'], $term['term_taxonomy_id'], $term['filter']);
		
		return $term;
	}
}	
?>