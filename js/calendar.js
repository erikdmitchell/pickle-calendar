jQuery(document).ready(function($) {

    // AJAX calendar navigation //
	$('body').on('click', '.pickle-calendar .cal-nav', function(e) {
		e.preventDefault();

		var data={
			'action' : 'bscal_nav',
			'month' : $(this).data('month'),
			'year' : $(this).data('year')
		};
	
		$.post(pickleCalOpts.ajax_url, data, function(response) {
			var $parent=$('.pickle-calendar').parent();
			
			$parent.html(''); // clear html
			$parent.append(response); // add calendar
			
			$(document).trigger('pickle_calendar_ajax_load', response);
			
			PickleCalendarRowSetup();
			
			pcalEqualHeight('.pickle-calendar .calendar-day');
		});
	});
	
	// calendar filters //
	$('body').on('change', '.pickle-calendar-filters .filter-term', function(e) {
    	var activeFilters=$('#pickle-calendar-filters').data('filters'); // get filters
    	var activeFiltersArr=activeFilters.split(','); // filters to arr

        // add or remove based on check //
    	if (this.checked) {
        	activeFiltersArr.push($(this).val());     	
    	} else {
            var index=activeFiltersArr.indexOf($(this).val());
            
            if (index > -1) {
                activeFiltersArr.splice(index, 1);
            }        	
    	}
    	
    	// remove empty arr elements //
    	activeFiltersArr=activeFiltersArr.filter(function(x) {
            return (x !== (undefined || null || ''));
        });

        // update filters //
    	$('#pickle-calendar-filters').data('filters', activeFiltersArr.join());
	});
	
});

jQuery(window).load(function() {
	PickleCalendarRowSetup();
	pcalEqualHeight('.pickle-calendar .calendar-day');	
});


jQuery(window).resize(function() {
	PickleCalendarRowSetup();
	pcalEqualHeight('.pickle-calendar .calendar-day');
});

/*
 * our equal heights function for calendar days
 */
(function($) {
	
	pcalEqualHeight = function(container) {
		var currentTallest = 0,
		currentRowStart = 0,
		rowDivs = new Array(),
		$el,
		topPosition = 0;
		
		$(container).each(function() {
		
			$el = $(this);
			$($el).height('auto')
			topPostion = $el.position().top;
			
			if (currentRowStart != topPostion) {
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
					rowDivs[currentDiv].height(currentTallest);
				}
				
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.height();
				rowDivs.push($el);
			} else {
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
			}
			
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
	
		});
	};
	
	$.fn.pcEventOffset=function() {
		var thisOffset=jQuery(this).offset();		
		var date=new Date(jQuery(this).data('eventDate') + 'T00:00:00');
		var newDate=new Date(jQuery(this).data('eventDate') + 'T00:00:00');
		
		newDate.setDate(newDate.getDate() - 1);
		
		var nd=new Date(newDate);
		var prevDay=('0' + nd.getDate()).slice(-2);
		var prevMonth=('0' + (nd.getMonth() + 1)).slice(-2);
		var prevDate=nd.getFullYear() + '-' + prevMonth + '-' + prevDay;
		var prevEvent=jQuery('.pickle-calendar').find(".pickle-calendar-event[data-event-date='" + prevDate + "'][data-event-id='" + jQuery(this).data('eventId') + "']");
		
		if (typeof prevEvent.offset() != 'undefined' && prevEvent.offset().top != thisOffset.top) {
			jQuery(this).css({
				'margin-top' : prevEvent.offset().top - thisOffset.top
			});
			
			// double check //
			if (prevEvent.offset().top != jQuery(this).offset().top) {
				jQuery(this).css({
					'margin-top' : parseInt(jQuery(this).css('margin-top')) + (prevEvent.offset().top - jQuery(this).offset().top)
				});			
			}
		}
				
	};	
})(jQuery);

function pcTextWidth(text, font) { 
    $fakeEl=jQuery('<span>').hide().appendTo(document.body);
    	
    $fakeEl.text(text).css('font', font);
    
    var width = $fakeEl.width();
    
    $fakeEl.remove();
    
    return width;
};

function PickleCalendarRowSetup() {
	jQuery('.pickle-calendar-event.multiday').each(function() {
		var eventTotalDays=jQuery(this).data('eventTotalDays');
	
		jQuery(this).css('width', eventTotalDays * 98 + '%'); // set width //
			
		jQuery(this).pcEventOffset(); // tweak margin	
	});
}