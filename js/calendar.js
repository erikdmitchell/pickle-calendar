jQuery(document).ready(function($) {

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
	
	PickleCalendarRowSetup();
	
});

jQuery(window).load(function() {
	pcalEqualHeight('.pickle-calendar .calendar-day');
});


jQuery(window).resize(function(){
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
	
	$.fn.pcAdjustText=function() {
		var text=$(this).find('a').text();
		var textWidth=pcTextWidth(text, $(this).css('font'));
		var eleWidth=$(this).width();

		if (textWidth > eleWidth) {
			var eventID=$(this).data('eventId');
			var days=$("div[data-event-id='"+eventID+"']").length;
			var tmpID='tmp' + eventID + $(this).data('eventDate');
			var linkURL=$(this).find('a').attr('href');
		
			$(this).find('a').text('&nbsp;'); // hide existing text

			var $div='<div id="'+tmpID+'" class="'+$(this).attr('class')+'"><a href="'+linkURL+'">'+text+'</a></div>';
			
			$('.pickle-calendar').append($div);

			$('#'+tmpID).css({
				'position' : 'absolute',
				'top' : $(this).position().top,
				'left' : $(this).position().left,
				'font' : $(this).css('font')
			});		
		}			
	};
			
})(jQuery);

function pcTextWidth(text, font) { 
    $fakeEl = jQuery('<span>').hide().appendTo(document.body);
    	
    $fakeEl.text(text).css('font', font);
    
    return $fakeEl.width();
};

function PickleCalendarRowSetup() {
	jQuery('.pickle-calendar-event').each(function() {
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
		
		// adjust text //	
		jQuery(this).pcAdjustText();			
	});	
}