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
		});
	});
	
/*
	$('.start.overwrap-text').each(function() {
		$(this).overwrapText();
	});
*/
	
});

jQuery(window).load(function() {
	bscalEqualHeight('.pickle-calendar .calendar-day');
});


jQuery(window).resize(function(){
	bscalEqualHeight('.pickle-calendar .calendar-day');
});

/*
 * our equal heights function for calendar days
 */
(function($) {
	
	bscalEqualHeight = function(container) {
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
	
})(jQuery);

/*
 * event overwrap
 */
(function($) {
	
	$.fn.overwrapText = function() {
//console.log('over wrap text');		
//console.log(this.data('eventId'));
		
		if (this.hasClass('start')) {
			var text=this.find('a').text();	
			var textfontSize=$(this).find('a').css('font-size');
			var eventID=this.data('eventId');
			var textWidth=getTextWidth(text, textfontSize);
			
console.log(textWidth);
//console.log($(this).find('a').css('font-size'));			
			//var textWidth=$(this).find('a').text().width();
			var divWidth=this.width();
		
		//console.log(this.children('a').width());
		//var t=$(text);	
			
//console.log(divWidth + ' | ' + textWidth);

			// explode text //
			text=text.split('');
			
			text2=[];
			

while (textWidth > divWidth) {
	
    //remove last character
    lastChar = text.pop();

    //prepend to p2 text
    text2.unshift(lastChar);

    //reassemble p1 text
    p1temp = text.join('');
//console.log(p1temp);
//console.log(text2);
    //place back to p1
    //this.text(p1temp);

    //re-evaluate height
    textWidth = getTextWidth(text, textfontSize);

    //loop	
		
}
this.text(p1temp);
p2text=text2.join('');

console.log(p2text);

//if less than, assemble p2 text and render to p2 container
//p2.text(p2text.join(''));​
$('.event-10547:eq(1)').text(p2text);

		}	
	};




	
})(jQuery);

function getTextWidth(text, fontSize) {
	var $fakeEl = jQuery('<span>'+text+'</span>').css({
		'font-size' : fontSize
	}).hide().appendTo(document.body);
	var textWidth = $fakeEl.width();
			
	$fakeEl.remove();
	
	return textWidth;
}