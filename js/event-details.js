jQuery(document).ready(function($) {
	
	var fpConf={
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
	};

	$('.pcdetail-pickr').datepicker(fpConf);
	
	$('.pc-repeater').on('click', function(e) {
		e.preventDefault();
		
		var el=$(this).data('field');
		
		repeatField({
			container: el,
			datePicker: fpConf
		});
	});
	
	$('body').on('click', '.pc-remove-row', function(e) {
		e.preventDefault();
		
		var rowID=$(this).parent().data('rowId');
		
		$(this).parent().remove();
		
		resetIDs('.event-date-wrap');
	});	
	
});