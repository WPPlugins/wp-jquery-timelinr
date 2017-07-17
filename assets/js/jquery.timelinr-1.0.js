/* ----------------------------------
jQuery Timelinr 0.9.52
tested with jQuery v1.6+

Copyright 2011, CSSLab.cl + Broobe
Free under the MIT license.
http://www.opensource.org/licenses/mit-license.php

instructions: http://www.csslab.cl/2011/08/18/jquery-timelinr/
---------------------------------- */

(function( $ ){	
	$.fn.timelinr = function(options){
		var settings = getSettings(options);
		
		var howManyDates = $(settings.containerDiv+' .dates li').length;
		var howManyIssues = $(settings.containerDiv+' .issues li').length;
		var currentDate = $(settings.containerDiv+' .dates').find('a.selected');
		var currentIssue = $(settings.containerDiv+' .issues').find('li.selected');
		var widthContainer = $(settings.containerDiv+' .timeline').width();
		var heightContainer = $(settings.containerDiv+' .timeline').height();
		var widthIssues = $(settings.containerDiv+' .issues').width();
		var heightIssues = $(settings.containerDiv+' .issues').height();
		var widthIssue = $(settings.containerDiv+' .issues li').width();
		var heightIssue = $(settings.containerDiv+' .issues li').height();
		var widthDates = $(settings.containerDiv+' .dates').width();
		var heightDates = $(settings.containerDiv+' .dates').height();
		var widthDate = $(settings.containerDiv+' .dates li').width();
		var heightDate = $(settings.containerDiv+' .dates li').height();

		// set positions!
		if(settings.orientation == 'horizontal') {	
			$(settings.containerDiv+' .issues').width(widthIssue*howManyIssues + 400);
			$(settings.containerDiv+' .dates').width(widthDate*howManyDates*2).css('marginLeft',(widthContainer/2-widthDate/2));
			var defaultPositionDates = parseInt($(settings.containerDiv+' .dates').css('marginLeft').substring(0,$(settings.containerDiv+' .dates').css('marginLeft').indexOf('px')));
		} else if(settings.orientation == 'vertical') {
			$(settings.containerDiv+' .issues').height(heightIssue*howManyIssues);
			$(settings.containerDiv+' .dates').height(heightDate*howManyDates).css('marginTop',heightContainer/2-heightDate/2);
			var defaultPositionDates = parseInt($(settings.containerDiv+' .dates').css('marginTop').substring(0,$(settings.containerDiv+' .dates').css('marginTop').indexOf('px')));
		}
		
		$(settings.containerDiv+' .dates a').click(function(event){
			event.preventDefault();
			// first vars
			var whichIssue = $(this).text();
			var currentIndex = $(this).parent().prevAll().length;
			var settings = getSettings(options);
			
			// moving the elements
			if(settings.orientation == 'horizontal') {
				$(settings.containerDiv+' .issues').animate({'marginLeft':-widthIssue*currentIndex},{queue:false, duration:settings.issuesSpeed});
			} else if(settings.orientation == 'vertical') {
				$(settings.containerDiv+' .issues').animate({'marginTop':-heightIssue*currentIndex},{queue:false, duration:settings.issuesSpeed});
			}
			$(settings.containerDiv+' .issues li').animate({'opacity':settings.issuesTransparency},{queue:false, duration:settings.issuesSpeed}).removeClass('selected').eq(currentIndex).addClass('selected').fadeTo(settings.issuesTransparencySpeed,1);
			// prev/next buttons now disappears on first/last issue | bugfix from 0.9.51: lower than 1 issue hide the arrows
			if(howManyDates == 1) {
				$(settings.containerDiv+" .prev,"+settings.containerDiv+" .next").fadeOut('fast');
			} else if(howManyDates == 2) {
				if($(settings.containerDiv+' .issues li:first-child').hasClass('selected')) {
					$(settings.containerDiv+" .prev").fadeOut('fast');
				 	$(settings.containerDiv+" .next").fadeIn('fast');
				} 
				else if($('.issues li:last-child').hasClass('selected')) {
					$(settings.containerDiv+" .next").fadeOut('fast');
					$(settings.containerDiv+" .prev").fadeIn('fast');
				}
			} else {
				if( $(settings.containerDiv+' .issues li:first-child').hasClass('selected') ) {
					$(settings.containerDiv+" .prev").fadeOut('fast');
				} 
				else if( $(settings.containerDiv+' .issues li:last-child').hasClass('selected') ) {
					$(settings.containerDiv+" .next").fadeOut('fast');
				}
				else {
					$(settings.containerDiv+" .next"+','+settings.containerDiv+" .prev").fadeIn('slow');
				}	
			}
			// now moving the dates
			$(settings.containerDiv+' .dates a').removeClass('selected');
			$(this).addClass('selected');
			if(settings.orientation == 'horizontal') {
				$(settings.containerDiv+' .dates').animate({'marginLeft':defaultPositionDates-(widthDate*currentIndex)},{queue:false, duration:settings.datesSpeed});
			} else if(settings.orientation == 'vertical') {
				$(settings.containerDiv+' .dates').animate({'marginTop':defaultPositionDates-(heightDate*currentIndex)},{queue:false, duration:settings.datesSpeed});
			}
		});

		$(settings.containerDiv+" .next").click(function(event){
			event.preventDefault();
			var settings = getSettings(options);
			
			if(settings.orientation == 'horizontal') {
				var currentPositionIssues = parseInt($(settings.containerDiv+' .issues').css('marginLeft').substring(0,$(settings.containerDiv+' .issues').css('marginLeft').indexOf('px')));
				var currentIssueIndex = currentPositionIssues/widthIssue;
				var currentPositionDates = parseInt($(settings.containerDiv+' .dates').css('marginLeft').substring(0,$(settings.containerDiv+' .dates').css('marginLeft').indexOf('px')));
				var currentIssueDate = currentPositionDates-widthDate;
				if(currentPositionIssues <= -(widthIssue*howManyIssues-(widthIssue))) {
					$(settings.containerDiv+' .issues').stop();
					$(settings.containerDiv+' .dates li:last-child a').click();
				} else {
					if (!$(settings.containerDiv+' .issues').is(':animated')) {
						$(settings.containerDiv+' .issues').animate({'marginLeft':currentPositionIssues-widthIssue},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .issues li').animate({'opacity':settings.issuesTransparency},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .issues li.selected').removeClass('selected').next().fadeTo(settings.issuesTransparencySpeed, 1).addClass('selected');
						$(settings.containerDiv+' .dates').animate({'marginLeft':currentIssueDate},{queue:false, duration:settings.datesSpeed});
						$(settings.containerDiv+' .dates a.selected').removeClass('selected').parent().next().children().addClass('selected');
					}
				}
			} else if(settings.orientation == 'vertical') {
				var currentPositionIssues = parseInt($(settings.containerDiv+' .issues').css('marginTop').substring(0,$(settings.containerDiv+' .issues').css('marginTop').indexOf('px')));
				var currentIssueIndex = currentPositionIssues/heightIssue;
				var currentPositionDates = parseInt($(settings.containerDiv+' .dates').css('marginTop').substring(0,$(settings.containerDiv+' .dates').css('marginTop').indexOf('px')));
				var currentIssueDate = currentPositionDates-heightDate;
				if(currentPositionIssues <= -(heightIssue*howManyIssues-(heightIssue))) {
					$(settings.containerDiv+' .issues').stop();
					$(settings.containerDiv+' .dates li:last-child a').click();
				} else {
					if (!$(settings.containerDiv+' .issues').is(':animated')) {
						$(settings.containerDiv+' .issues').animate({'marginTop':currentPositionIssues-heightIssue},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .issues li').animate({'opacity':settings.issuesTransparency},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .issues li.selected').removeClass('selected').next().fadeTo(settings.issuesTransparencySpeed, 1).addClass('selected');
						$(settings.containerDiv+' .dates').animate({'marginTop':currentIssueDate},{queue:false, duration:settings.datesSpeed});
						$(settings.containerDiv+' .dates a.selected').removeClass('selected').parent().next().children().addClass('selected');
					}
				}
			}
			// prev/next buttons now disappears on first/last issue | bugfix from 0.9.51: lower than 1 issue hide the arrows
			if(howManyDates == 1) {
				$(settings.containerDiv+" .prev,"+settings.containerDiv+" .next").fadeOut('fast');
			} else if(howManyDates == 2) {
				if($(settings.containerDiv+' .issues li:first-child').hasClass('selected')) {
					$(settings.containerDiv+" .prev").fadeOut('fast');
				 	$(settings.containerDiv+" .next").fadeIn('fast');
				} 
				else if($(settings.containerDiv+' .issues li:last-child').hasClass('selected')) {
					$(settings.containerDiv+" .next").fadeOut('fast');
					$(settings.containerDiv+" .prev").fadeIn('fast');
				}
			} else {
				if( $(settings.containerDiv+' .issues li:first-child').hasClass('selected') ) {
					$(settings.containerDiv+" .prev").fadeOut('fast');
				} 
				else if( $(settings.containerDiv+' .issues li:last-child').hasClass('selected') ) {
					$(settings.containerDiv+" .next").fadeOut('fast');
				}
				else {
					$(settings.containerDiv+" .next"+','+settings.containerDiv+" .prev").fadeIn('slow');
				}	
			}
		});

		$(settings.containerDiv+" .prev").click(function(event){
			event.preventDefault();
			var settings = getSettings(options);
			
			if(settings.orientation == 'horizontal') {
				var currentPositionIssues = parseInt($(settings.containerDiv+' .issues').css('marginLeft').substring(0,$(settings.containerDiv+' .issues').css('marginLeft').indexOf('px')));
				var currentIssueIndex = currentPositionIssues/widthIssue;
				var currentPositionDates = parseInt($(settings.containerDiv+' .dates').css('marginLeft').substring(0,$(settings.containerDiv+' .dates').css('marginLeft').indexOf('px')));
				var currentIssueDate = currentPositionDates+widthDate;
				if(currentPositionIssues >= 0) {
					$(settings.containerDiv+' .issues').stop();
					$(settings.containerDiv+' .dates li:first-child a').click();
				} else {
					if (!$(settings.containerDiv+' .issues').is(':animated')) {
						$(settings.containerDiv+' .issues').animate({'marginLeft':currentPositionIssues+widthIssue},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .issues li').animate({'opacity':settings.issuesTransparency},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .issues li.selected').removeClass('selected').prev().fadeTo(settings.issuesTransparencySpeed, 1).addClass('selected');
						$(settings.containerDiv+' .dates').animate({'marginLeft':currentIssueDate},{queue:false, duration:settings.datesSpeed});
						$(settings.containerDiv+' .dates a.selected').removeClass('selected').parent().prev().children().addClass('selected');
					}
				}
			} else if(settings.orientation == 'vertical') {
				var currentPositionIssues = parseInt($(settings.containerDiv+' .issues').css('marginTop').substring(0,$(settings.containerDiv+' .issues').css('marginTop').indexOf('px')));
				var currentIssueIndex = currentPositionIssues/heightIssue;
				var currentPositionDates = parseInt($(settings.containerDiv+' .dates').css('marginTop').substring(0,$(settings.containerDiv+' .dates').css('marginTop').indexOf('px')));
				var currentIssueDate = currentPositionDates+heightDate;
				if(currentPositionIssues >= 0) {
					$(settings.containerDiv+' .issues').stop();
					$(settings.containerDiv+' .dates li:first-child a').click();
				} else {
					if (!$(settings.containerDiv+' .issues').is(':animated')) {
						$(settings.containerDiv+' .issues').animate({'marginTop':currentPositionIssues+heightIssue},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .issues li').animate({'opacity':settings.issuesTransparency},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .issues li.selected').removeClass('selected').prev().fadeTo(options["issuesTransparencySpeed"], 1).addClass('selected');
						$(settings.containerDiv+' .dates').animate({'marginTop':currentIssueDate},{queue:false, duration:settings.datesSpeed},{queue:false, duration:settings.issuesSpeed});
						$(settings.containerDiv+' .dates a.selected').removeClass('selected').parent().prev().children().addClass('selected');
					}
				}
			}
			// prev/next buttons now disappears on first/last issue | bugfix from 0.9.51: lower than 1 issue hide the arrows
			if(howManyDates == 1) {
				$(settings.containerDiv+" .prev,"+settings.containerDiv+" .next").fadeOut('fast');
			} else if(howManyDates == 2) {
				if($(settings.containerDiv+' .issues li:first-child').hasClass('selected')) {
					$(settings.containerDiv+" .prev").fadeOut('fast');
				 	$(settings.containerDiv+" .next").fadeIn('fast');
				} 
				else if($(settings.containerDiv+' .issues li:last-child').hasClass('selected')) {
					$(settings.containerDiv+" .next").fadeOut('fast');
					$(settings.containerDiv+" .prev").fadeIn('fast');
				}
			} else {
				if( $(settings.containerDiv+' .issues li:first-child').hasClass('selected') ) {
					$(settings.containerDiv+" .prev").fadeOut('fast');
				} 
				else if( $(settings.containerDiv+' .issues li:last-child').hasClass('selected') ) {
					$(settings.containerDiv+" .next").fadeOut('fast');
				}
				else {
					$(settings.containerDiv+" .next,"+settings.containerDiv+" .prev").fadeIn('slow');
				}	
			}
		});
		// keyboard navigation, added since 0.9.1
		if(options["arrowKeys"]=='true') {
			if(settings.orientation=='horizontal') {
				$(document).keydown(function(event){
					if (event.keyCode == 39) { 
				       $(settings.containerDiv+" .next").click();
				    }
					if (event.keyCode == 37) { 
				       $(settings.containerDiv+" .prev").click();
				    }
				});
			} else if(settings.orientation=='vertical') {
				$(document).keydown(function(event){
					if (event.keyCode == 40) { 
				       $(settings.containerDiv+" .next").click();
				    }
					if (event.keyCode == 38) { 
				       $(settings.containerDiv+" .prev").click();
				    }
				});
			}
		}
		// default position startAt, added since 0.9.3
		$(settings.containerDiv+' .dates li').eq(settings.startAt-1).find('a').trigger('click');
		// autoPlay, added since 0.9.4
		if(settings.autoPlay == 'true') { 
			setInterval('autoPlay("'+settings.containerDiv+'","'+settings.autoPlayDirection+'")', settings.autoPlayPause);
		}
	};
	
	// getSettings, added since 1.0
	function getSettings(options){
		settings = jQuery.extend({
			containerDiv: 				'',					// value: any HTML tag or #id, default empty
			orientation: 				'horizontal',		// value: horizontal | vertical, default to horizontal	
			datesSpeed: 				'normal',			// value: integer between 100 and 1000 (recommended) or 'slow', 'normal' or 'fast'; default to normal
			issuesSpeed: 				'fast',				// value: integer between 100 and 1000 (recommended) or 'slow', 'normal' or 'fast'; default to fast
			issuesTransparency: 		0.2,				// value: integer between 0 and 1 (recommended), default to 0.2
			issuesTransparencySpeed: 	500,				// value: integer between 100 and 1000 (recommended), default to 500 (normal)
			arrowKeys: 					'true',				// value: true | false, default to true
			startAt: 					1,					// value: integer, default to 1 (first)
			autoPlay: 					'false',			// value: true | false, default to false
			autoPlayDirection: 			'forward',			// value: forward | backward, default to forward
			autoPlayPause: 				2000				// value: integer (1000 = 1 seg), default to 2000 (2segs)
		}, options);
		
		(settings.containerDiv != "") ? settings.containerDiv = "."+options["containerDiv"] : settings.containerDiv = "";
		
		return settings;
	}
})( jQuery );

// autoPlay, added since 0.9.4
function autoPlay(containerDiv, autoPlayDirection){
	var currentDate = jQuery(containerDiv+' .dates').find('a.selected');
	if(autoPlayDirection == 'forward') {
		if(currentDate.parent().is('li:last-child')) {
			jQuery('.dates li:first-child').find('a').trigger('click');
		} else {
			currentDate.parent().next().find('a').trigger('click');
		}
	} else if(autoPlayDirection == 'backward') {
		if(currentDate.parent().is('li:first-child')) {
			jQuery('.dates li:last-child').find('a').trigger('click');
		} else {
			currentDate.parent().prev().find('a').trigger('click');
		}
	}
}