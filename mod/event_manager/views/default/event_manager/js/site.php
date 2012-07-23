<?php ?>
//<script>
elgg.provide("elgg.event_manager");

(function(){

    var uid1 = 'D' + (+new Date()),
        uid2 = 'D' + (+new Date() + 1);

    jQuery.event.special.focus = {
        setup: function() {
            var _self = this,
                handler = function(e) {
                    e = jQuery.event.fix(e);
                    e.type = 'focus';
                    if (_self === document) {
                        jQuery.event.handle.call(_self, e);
                    }
                };

            jQuery(this).data(uid1, handler);

            if (_self === document) {
                /* Must be live() */
                if (_self.addEventListener) {
                    _self.addEventListener('focus', handler, true);
                } else {
                    _self.attachEvent('onfocusin', handler);
                }
            } else {
                return false;
            }

        },
        teardown: function() {
            var handler = jQuery(this).data(uid1);
            if (this === document) {
                if (this.removeEventListener) {
                    this.removeEventListener('focus', handler, true);
                } else {
                    this.detachEvent('onfocusin', handler);
                }
            }
        }
    };

    jQuery.event.special.blur = {
        setup: function() {
            var _self = this,
                handler = function(e) {
                    e = jQuery.event.fix(e);
                    e.type = 'blur';
                    if (_self === document) {
                        jQuery.event.handle.call(_self, e);
                    }
                };

            jQuery(this).data(uid2, handler);

            if (_self === document) {
                /* Must be live() */
                if (_self.addEventListener) {
                    _self.addEventListener('blur', handler, true);
                } else {
                    _self.attachEvent('onfocusout', handler);
                }
            } else {
                return false;
            }

        },
        teardown: function() {
            var handler = jQuery(this).data(uid2);
            if (this === document) {
                if (this.removeEventListener) {
                    this.removeEventListener('blur', handler, true);
                } else {
                    this.detachEvent('onfocusout', handler);
                }
            }
        }
    };

    // toggle drop down menu
    $(".event_manager_event_actions").live("click", function(event){
        if($(this).next().is(":hidden")){
            // only needed if the current menu is already dropped down
	    	$("body > .event_manager_event_actions_drop_down").remove();
			$("body").append($(this).next().clone());
			css_top = $(this).offset().top + $(this).height(); 
			css_left = $(this).offset().left; 
			$("body > .event_manager_event_actions_drop_down").css({top: css_top, left: css_left}).show();;
        }
        
		event.stopPropagation();
    });

    // hide drop down menu items
    $("body").live("click", function(){
    	$("body > .event_manager_event_actions_drop_down").remove();
    });

}());

function event_manager_program_add_day(form){
	$(form).find("input[type='submit']").hide();
	
	$.post('/events/proc/day/edit', $(form).serialize(), function(response) {
		if(response.valid) {
			$.fancybox.close();
			guid = response.guid;
			if(response.edit){
				$("#day_" + guid + " .event_manager_program_day_details").html(response.content_body);
				$("#event_manager_event_view_program a[rel='day_" + guid + "']").html(response.content_title).click();
			} else {
				$("#event_manager_event_view_program").after(response.content_body);
				$("#event_manager_event_view_program li:last").before(response.content_title);
				$("#event_manager_event_view_program a[rel='day_" + guid + "']").click();
			}
		} else {
			$(form).find("input[type='submit']").show();
		}
	}, 'json');
}

function event_manager_program_add_slot(form){
	$(form).find("input[type='submit']").hide();
	
	$.post('/events/proc/slot/edit', $(form).serialize(), function(response) {
		if(response.valid) {
			$.fancybox.close();
			
			guid = response.guid;
			parent_guid = response.parent_guid;
			if(response.edit){
				$("#" + guid).replaceWith(response.content);
			} else {
				$("#day_" + parent_guid).find("a.event_manager_program_slot_add").before(response.content);
			}
		} else {
			$(form).find("input[type='submit']").show();
		}
	}, 'json');
}

function event_manager_registrationform_add_field(form) {
	$(form).find("input[type='submit']").hide();
	
	$.post('/events/proc/question/edit', $(form).serialize(), function(response){
		if(response.valid) {
			$.fancybox.close();
			guid = response.guid;

			if(response.edit) {
				$('#question_' + guid).replaceWith(response.content);
			} else {
				$("#event_manager_registrationform_fields").append(response.content);
				
				save_registrationform_question_order();
			}
		} else {
			$(form).find("input[type='submit']").show();
		}
	}, 'json');
}

function event_manager_execute_search(){
	$("#event_manager_result_refreshing").show();

	form = $("#event_manager_search_form");
	form_elements = form[0].elements;
	advanced = $("#event_manager_event_search_advanced_container");
	advanced_elements = $("#event_manager_event_search_advanced_container")[0].children;
	
	elements = $(form_elements).not(advanced_elements);
	
	if($('#past_events').is(":hidden") == true)	{
		var formData = form.serialize();
	} else {
		var formData = elements.serialize();
	}

	map_data_only = false;
	if($("#event_manager_result_navigation li.elgg-state-selected a").attr("rel") == "onthemap"){
		map_data_only = true;
	}
	
	$.post('/events/proc/search/events', formData, function(response){
		if(response.valid){
		
			if(map_data_only) {

				if (event_manager_gmarkers) {
				    for (i in event_manager_gmarkers) {
				    	event_manager_gmarkers[i].setMap(null);
				    }
			  	}
					
				event_manager_gmarkers = [];
				if(response.markers) {
					$.each(response.markers, function(i, event) {
						var myLatlng = new google.maps.LatLng(event.lat, event.lng);
						
						marker = new google.maps.Marker({ 
							map: event_manager_gmap, 
							position: myLatlng,
							animation: google.maps.Animation.DROP,
							title: event.title
						});
						
						var infowindow = new google.maps.InfoWindow({
						    content: event.html
						});
						
						google.maps.event.addListener(marker, 'click', function() {
						  infowindow.open(event_manager_gmap,marker);
						});
											
						event_manager_gmarkers.push(marker);
					});
				}

				// make sidebar
				//getMarkersJson();
			} else {
				$('#event_manager_event_list_search_more').remove();
				$('#event_manager_event_listing').html(response.content);
				$("#event_manager_result_refreshing").hide();
			}	
		}
		
		$("#event_manager_result_refreshing").hide();
	}, 'json');
}

function save_registrationform_question_order() {
	var $sortableRegistrationForm = $('#event_manager_registrationform_fields');
	order = $sortableRegistrationForm.sortable('serialize');
	$.getJSON('/events/proc/question/saveorder', order, function(response){
		if(!response.valid)	{
			alert(elgg.echo('event_manager:registrationform:fieldorder:error'));
		}
	});
}


elgg.event_manager.init = function() {
	
	$('.event_manager_program_slot_delete').live('click', function() {
		if(confirm(elgg.echo('deleteconfirm'))) {
			slotGuid = $(this).parent().attr("rel");
			if(slotGuid) {
				$slotElement = $("#" + slotGuid); 
				$slotElement.hide();
				$.getJSON('/events/proc/slot/delete', {guid: slotGuid}, function(response) {
					if(response.valid) {
						$slotElement.remove();
					} else {
						$slotElement.show();
					}																					
				});
			}
		}
		return false;
		
	});

	$('.event_manager_program_day_delete').live('click', function(e) {
		if(confirm(elgg.echo('deleteconfirm'))) {
			dayGuid = $(this).parent().attr("rel");
			if(dayGuid) {
				$dayElements = $("#day_" + dayGuid + ", #event_manager_event_view_program li.elgg-state-selected"); 
				$dayElements.hide();
				$.getJSON('/events/proc/day/delete', {guid: dayGuid}, function(response) {
					if(response.valid) {
						// remove from DOM
						$dayElements.remove();
						if($("#event_manager_event_view_program li").length > 1){
							$("#event_manager_event_view_program li:first a").click();
						}						
					} else {
						// revert
						$dayElements.show();
					}																					
				});
			}
		}
		
		return false;
	});
	
	$('#event_manager_program_register').click(function() {
		$.getJSON('/events/proc/program/register', {event: $('#eventguid').val(), guids: guids.toSource()}, function(response) {
			if(response.valid) {
				$('#register_status').html(elgg.echo('event_manager:registration:program:success'));
			} else {
				$('#register_status').html(elgg.echo('event_manager:registration:program:fail'));
			}
		});
	});

	$('.event_manager_questions_delete').live('click', function(e) {
		if(confirm(elgg.echo('deleteconfirm'))) {
			questionGuid = $(this).attr("rel");
			if(questionGuid) {
				$questionElement = $(this); 
				$questionElement.parent().hide();
				$.getJSON('/events/proc/question/delete', {guid: questionGuid}, function(response) {
					if(response.valid) {
						// remove from DOM
						$questionElement.parent().remove();				
					} else {
						// revert
						$questionElement.parent().show();
					}																					
				});
			}
		}
		
		return false;
	});
	
	/* Event Manager Search Form */
	$('#event_manager_registrationform_fields').sortable({
		axis: 'y',
		tolerance: 'pointer',
		opacity: 0.8,
		forcePlaceholderSize: true,
		forceHelperSize: true,
		update: function(event, ui)	{
			save_registrationform_question_order();
		}
	});
	
	$('#event_manager_event_search_advanced_enable').click(function()
	{
		$('#event_manager_event_search_advanced_container, #past_events, #event_manager_event_search_advanced_enable span').toggle();

		if($('#past_events').is(":hidden"))
		{
			console.log('advanced');
			$('#advanced_search').val('1');
		}
		else
		{
			console.log('simple');
			$('#advanced_search').val('0');
		}
	});
	
	$('#event_manager_event_list_search_more').live('click', function()	{
		clickedElement = $(this);
		clickedElement.html('');
		clickedElement.addClass('event_manager_search_load');
		offset = parseInt($(this).attr('rel'), 10);
		
		$("#event_manager_result_refreshing").show();
		if($('#past_events').is(":hidden") == true) {
			var formData = $("#event_manager_search_form").serialize();
		} else {
			var formData = $($("#event_manager_search_form")[0].elements).not($("#event_manager_event_search_advanced_container")[0].children).serialize();
		}
		
		$.post('/events/proc/search/events?offset='+offset, formData, function(response) {
			if(response.valid) {
				$('#event_manager_event_list_search_more').remove();
				//$(response.content).insertAfter('.search_listing:last');
				$('#event_manager_event_listing').append(response.content);
			}
			$("#event_manager_result_refreshing").hide();
		}, 'json');
	});
	
	$('#event_manager_search_form').submit(function(e) {
		event_manager_execute_search();
		e.preventDefault();
	});
	
	$("#event_manager_result_navigation li a").click(function() {
		if(!($(this).parent().hasClass("elgg-state-selected"))){
			selected = $(this).attr("rel");

			$("#event_manager_result_navigation li").toggleClass("elgg-state-selected");
			$("#event_manager_event_map, #event_manager_event_listing").toggle();
			
			if(selected == "onthemap"){
				initMaps('event_manager_onthemap_canvas');
				initOnthemaps();
			} else {
				$("#event_manager_onthemap_sidebar").remove();
			}
			
			$('#search_type').val(selected);

			event_manager_execute_search();
		}
	});

	$('.event_manager_registration_approve').click(function() {
		regElmnt = $(this);
		regId = regElmnt.attr('rel');

		$.getJSON('/events/proc/registration/approve', {guid: regId}, function(response) {
			if(response.valid) {
				regElmnt.unbind('click');
				regElmnt.replaceWith('<img border="0" src="/mod/event_manager/_graphics/icons/check_icon.png" />');
			}
		});
	});
	
	$('.event_manager_program_day_add').live('click', function() {
		eventGuid = $(this).attr("rel");
		$.fancybox({
				'href': '/events/program/day?event_guid=' + eventGuid, 
				'onComplete'		: function() {
						elgg.ui.initDatePicker();
					}
			});
		
		return false;
	});

	$('.event_manager_program_day_edit').live('click', function() {
		guid = $(this).attr("rel");
		$.fancybox({
				'href': '/events/program/day?day_guid=' + guid,
				'onComplete'		: function() {
					elgg.ui.initDatePicker();
				}
			});
		
		return false;
	});
	
	$('.event_manager_program_slot_add').live('click', function() {
		dayGuid = $(this).attr("rel");
		$.fancybox({'href': '/events/program/slot?day_guid=' + dayGuid});
		
		return false;
	});

	$('.event_manager_program_slot_edit').live('click', function() {
		guid = $(this).attr("rel");
		$.fancybox({'href': '/events/program/slot?slot_guid=' + guid});
		
		return false;
	});
	
	$('#event_manager_questions_add').click(function() {
		eventGuid = $(this).attr("rel");
		$.fancybox({'href': '/events/registrationform/question?event_guid=' + eventGuid});

		return false;
	});

	$('.event_manager_questions_edit').live('click', function()	{
		guid = $(this).attr("rel");
		$.fancybox({'href': '/events/registrationform/question?question_guid=' + guid});
		
		return false;
	});
	
	$('#event_manager_registrationform_question_fieldtype').live('change', function() {
		if($('#event_manager_registrationform_question_fieldtype').val() == 'Radiobutton' || $('#event_manager_registrationform_question_fieldtype').val() == 'Dropdown') {
			$('#event_manager_registrationform_select_options').show();
		} else {
			$('#event_manager_registrationform_select_options').hide();
		}
	});

	$('#event_manager_event_register').submit(function() {
		guids = [];
		$.each($('.event_manager_program_participatetoslot'), function(i, value) {
			elementId = $(value).attr('id');
			if($(value).is(':checked')) {
				guids.push(elementId.substring(9, elementId.length));
			}
		});

		$('#event_manager_program_guids').val(guids.join(','));
	});
	
	$('#with_program').change(function() {
		if($(this).is(':checked')) {
			$('#event_manager_start_time_pulldown').css('display', 'none');
		} else {
			$('#event_manager_start_time_pulldown').css('display', 'table-row');
		}
	});
};

elgg.register_hook_handler('init', 'system', elgg.event_manager.init);