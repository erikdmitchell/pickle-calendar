jQuery(document).ready(function($) {
	
	if ($('input#include_details').is(':checked')) {
		$('.pickle-calendar-settings-form tr.details-box').show();	
	}
	
	// taxonomy display options onload.
    if ($("#tax-display input[type=radio]:checked").val() == 1) {
        $('#tax-display-type').show();
    }
    
	// taxonomy display options onchange.
    $('#tax-display input[type=radio]').change(function() {
        if ($(this).val() == 1) {
            $('#tax-display-type').slideDown('fast');                    	   
        } if ($(this).val() == 0) {
            $('#tax-display-type').slideUp('fast');
        }
    });
	
});
