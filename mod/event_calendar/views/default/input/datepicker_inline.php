<?php

/**
 * JQuery data picker(inline version)
 * 
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2008 -2011
 * @link http://radagast.biz/
 * 
 */
if ($vars['group_guid']) {
	$link_bit = $vars['url']."event_calendar/group/{$vars['group_guid']}/%s/{$vars['mode']}";
} else {
	$link_bit = $vars['url']."event_calendar/list/%s/{$vars['mode']}/{$vars['filter']}";
}

if ($vars['mode'] == 'week') {
	$selected_week = date('W',strtotime($vars['start_date'].' UTC'))+1;
} else {
	$selected_week = '';
}

if ($vars['mode']) {
	$wrapper_class = "event-calendar-filter-period-".$vars['mode'];
} else {
	$wrapper_class = "event-calendar-filter-period-month";
}
// TODO - figure out how to move this JavaScript
?>

<script language="javascript">
var selectedWeek = "<?php echo $selected_week; ?>";
highlightWeek = function(d) {
	if (!selectedWeek) { return [true,''];}
	//var date = $(this).datepicker('getDate');
    var dayOfWeek = d.getUTCDay();
    var weekNumber = $.datepicker.iso8601Week(d);
    if (dayOfWeek == 6) {
        weekNumber += 1;
    }
	
	if (selectedWeek == weekNumber) {
        return [true,'week-highlight'];   
  	}    
    return [true,''];   
}
$(document).ready(function(){
var done_loading = false;
$("#<?php echo $vars['name']; ?>").datepicker({ 
	onChangeMonthYear: function(year, month, inst) {
 		if(inst.onChangeToday){
 			day=inst.selectedDay;
 		}else{
 			day=1;
 		}
		if (done_loading) {
			// in this case the mode is forced to month
			document.location.href = "<?php echo $link_bit; ?>".replace('%s', year+'-'+month+'-1');
		}
	},
    onSelect: function(date) {
		// jump to the new page
        document.location.href = "<?php echo $link_bit; ?>".replace('%s', date.substring(0,10));
    },
    dateFormat: "yy-mm-dd",
    defaultDate: "<?php echo $vars['start_date'] .' - '.$vars['end_date']; ?>",
    beforeShowDay: highlightWeek
});
var start_date = $.datepicker.parseDate("yy-mm-dd", "<?php echo $vars['start_date']; ?>");
var end_date = $.datepicker.parseDate("yy-mm-dd", "<?php echo $vars['end_date']; ?>");
// not sure why this is necessary, but it seems to be
if ("<?php echo $vars['mode'] ?>" == "month") {
	end_date += 1;
}
$("#<?php echo $vars['name']; ?>").datepicker("setDate", start_date, end_date);
done_loading = true;
});

</script>
<div style="position:relative;" id="<?php echo $vars['name']; ?>" class="<?php echo $wrapper_class; ?>" ></div>
<p style="clear: both;"><!-- See day-by-day example for highlighting days code --></p>