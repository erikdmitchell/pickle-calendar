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
	
}	
?>