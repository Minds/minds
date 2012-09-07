//<script type="text/javascript">
elgg.provide('elgg.event_calendar');

elgg.event_calendar.init = function () {
	$('.event_calendar_paged_checkbox').click(elgg.event_calendar.handlePagedPersonalCalendarToggle);
	$('.event-calendar-personal-calendar-toggle').click(elgg.event_calendar.handleDisplayPagePersonalCalendarToggle);
	$('#event-calendar-region').change(elgg.event_calendar.handleRegionChange);
	$('#event-calendar-ical-link').click(elgg.event_calendar.handleIcalPopup);
	$('.event-calendar-repeating-unselected').each(elgg.event_calendar.setRepeatingClass);
	$('.event-calendar-repeating-unselected').live('click',elgg.event_calendar.handleRepeatingSelect);
	$('.event-calendar-repeating-selected').live('click',elgg.event_calendar.handleRepeatingUnselect);
	$('#event-calendar-edit').submit(elgg.event_calendar.handleEditFormSubmit);
	$('#event-calendar-edit-schedule-type').click(elgg.event_calendar.handleScheduleType);
	elgg.event_calendar.handleScheduleType();

	var all_day_field = $('[name="all_day"][type="checkbox"]');
	if (all_day_field.is(':checked')) {
		//$('[name="start_time"]').val(0);
		$('#event-calendar-start-time-wrapper').attr('disabled','disabled');
		//$('[name="end_time"]').val(0);
		$('#event-calendar-end-time-wrapper').attr('disabled','disabled');
	}
	all_day_field.change(elgg.event_calendar.handleAllDayField);
}

elgg.event_calendar.handleScheduleType = function(e) {
	var st = $('#event-calendar-edit-schedule-type:checked').val();
	if (st == 'poll') {
		$(".event-calendar-edit-date-wrapper").hide();
		$(".event-calendar-edit-reminder-wrapper").hide();
		$(".event-calendar-edit-form-membership-block").hide();
		$(".event-calendar-edit-form-share-block").hide();
	} else {
		$(".event-calendar-edit-date-wrapper").show();
		$(".event-calendar-edit-reminder-wrapper").show();
		$(".event-calendar-edit-form-membership-block").show();
		$(".event-calendar-edit-form-share-block").show();
		if (st == 'all_day') {
			$("#event-calendar-start-time-wrapper").hide();
			$("#event-calendar-end-time-wrapper").hide();
		} else {
			$("#event-calendar-start-time-wrapper").show();
			$("#event-calendar-end-time-wrapper").show();
		}
	}
}

elgg.event_calendar.handleAllDayField = function(e) {
	var field = $('[name="start_time"]');
	if (field.attr('disabled') == 'disabled') {
		field.removeAttr('disabled');
	} else {
		field.attr('disabled','disabled');
	}

	field = $('[name="end_time"]');
	if (field.attr('disabled') == 'disabled') {
		field.removeAttr('disabled');
	} else {
		field.attr('disabled','disabled');
	}
}

elgg.event_calendar.handleEditFormSubmit = function(e) {
	if ($.trim($('[name="title"]').val()) == '') {
		alert(elgg.echo('event_calendar:edit_form:error:missing_title'));
		e.preventDefault();
	}
}

elgg.event_calendar.setRepeatingClass = function(e) {
	var id = $(this).attr('id');
	if ($("[name='"+id+"-value']").val() == 1) {
		$(this).removeClass('event-calendar-repeating-unselected');
		$(this).addClass('event-calendar-repeating-selected');
	}
}

elgg.event_calendar.handleRepeatingSelect = function(e) {
	$(this).removeClass('event-calendar-repeating-unselected');
	$(this).addClass('event-calendar-repeating-selected');
	var id = $(this).attr('id');
	$("[name='"+id+"-value']").val(1);
}

elgg.event_calendar.handleRepeatingUnselect = function(e) {
	$(this).removeClass('event-calendar-repeating-selected');
	$(this).addClass('event-calendar-repeating-unselected');
	var id = $(this).attr('id');
	$("[name='"+id+"-value']").val(0);
}

elgg.event_calendar.handleRegionChange = function(e) {
	url = $('#event-calendar-region-url-start').val()+"/"+escape($('#event-calendar-region').val());
	elgg.forward(url);
}

elgg.event_calendar.handleIcalPopup = function(e) {
	var message = elgg.echo('event_calendar:ical_popup_message')+"\n"+this.href;
	alert(message);
	return false;
}

elgg.event_calendar.handlePagedPersonalCalendarToggle = function() {
	guid = parseInt($(this).attr('id').substring('event_calendar_paged_checkbox_'.length));
	elgg.event_calendar.togglePagedPersonalCalendar(guid);
}
elgg.event_calendar.togglePagedPersonalCalendar = function(guid) {
	elgg.action('event_calendar/toggle_personal_calendar',
			{
				data: {event_guid: guid},
				success: function (res) {
							var success = res.success;
							var msg = res.message;
							if (success) {
								elgg.system_message(msg,2000);
							} else {
								elgg.register_error(msg,2000);
							}
							//$('#event_calendar_paged_messages').html(msg);
							if (!success) {
								// action failed so toggle checkbox
								$("#event_calendar_paged_checkbox_"+guid).attr('checked',!$("#event_calendar_paged_checkbox_"+guid).attr('checked'));
							}
					    }
			}
	);
}

elgg.event_calendar.handleDisplayPagePersonalCalendarToggle = function() {
	var guidBit = $(this).attr('id').substring('event_calendar_user_data_'.length);
	var guids = guidBit.split('_');
	var event_guid = parseInt(guids[0]);
	var user_guid = parseInt(guids[1]);
	elgg.event_calendar.toggleDisplayPagePersonalCalendar(event_guid,user_guid);
}

elgg.event_calendar.toggleDisplayPagePersonalCalendar = function(event_guid,user_guid) {
	elgg.action('event_calendar/toggle_personal_calendar',
			{
				data: {event_guid: event_guid,user_guid: user_guid, other: 'yes'},
				success: function (res) {
							var success = res.success;
							var msg = res.message;
							if (success) {
								var button_text = res.button_text;
								$('#event_calendar_user_data_'+event_guid+'_'+user_guid).val(button_text);
								//elgg.system_message(msg,2000);
							} else {
								elgg.register_error(msg,2000);
							}
					    }
			}
	);
}

elgg.register_hook_handler('init', 'system', elgg.event_calendar.init);
//</script>