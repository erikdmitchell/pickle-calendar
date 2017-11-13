<?php
function pickle_calendar_get_taxonomy($slug='') {
	$default=array(
		'slug' => '',
		'label' => '',
		'label_plural' => '',
	);
	$tax=array();
	
	foreach (picklecalendar()->settings['taxonomies'] as $taxonomy) :
		if ($taxonomy['slug']==$slug) :
			$tax=$taxonomy;
			break;
		endif;
	endforeach;
	
	$tax=picklecalendar()->parse_args($tax, $default);
	
	return $tax;
}	
?>