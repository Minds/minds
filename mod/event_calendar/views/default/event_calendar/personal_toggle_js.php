<?php
// TODO: put the rest into a JS function

$elgg_ts = time();
$elgg_token = generate_action_token($elgg_ts);
$tokens = "&__elgg_ts=$elgg_ts&__elgg_token=$elgg_token";
?>
<script type="text/javascript">
function event_calendar_personal_toggle(event_id,user_id) {
	
	var link = "<?php echo $vars['url']; ?>action/event_calendar/toggle_personal_calendar?";
	link += "user_id="+user_id+"&event_id="+event_id+"&other=true";
	link += "<?php echo $tokens; ?>";
	$.get(link,
		function (res) {
			$('#event_calendar_user_data_'+user_id).html(res);
		}
	);
}
</script>