<?php
$event = $vars['event'];
$fd = $vars['form_data'];
if ($fd['repeats'] == 'yes') {
	echo elgg_view('input/checkbox',array('name'=>'repeats','value'=>'yes','checked'=>'checked'));
} else {
	echo elgg_view('input/checkbox',array('name'=>'repeats','value'=>'yes'));
}
echo elgg_echo('event_calendar:repeat_interval_label').' ';
echo elgg_view('input/dropdown',array('name'=>'repeat_interval','value'=>$fd['repeat_interval'],'options_values'=>array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8')));
echo ' '.elgg_echo('event_calendar:repeat_weeks');
echo ' '.elgg_echo('event_calendar:on_these_days');
?>
<div class="event-calendar-repeating-wrapper">
<a id="event-calendar-repeating-monday" href="javascript:void(0);" class="event-calendar-repeating-unselected">
	<?php echo elgg_echo('event_calendar:day_abbrev:monday'); ?>
</a>
<a id="event-calendar-repeating-tuesday" href="javascript:void(0);" class="event-calendar-repeating-unselected">
	<?php echo elgg_echo('event_calendar:day_abbrev:tuesday'); ?>
</a>
<a id="event-calendar-repeating-wednesday" href="javascript:void(0);" class="event-calendar-repeating-unselected">
	<?php echo elgg_echo('event_calendar:day_abbrev:wednesday'); ?>
</a>
<a id="event-calendar-repeating-thursday" href="javascript:void(0);" class="event-calendar-repeating-unselected">
	<?php echo elgg_echo('event_calendar:day_abbrev:thursday'); ?>
</a>
<a id="event-calendar-repeating-friday" href="javascript:void(0);" class="event-calendar-repeating-unselected">
	<?php echo elgg_echo('event_calendar:day_abbrev:friday'); ?>
</a>
<a id="event-calendar-repeating-saturday" href="javascript:void(0);" class="event-calendar-repeating-unselected">
	<?php echo elgg_echo('event_calendar:day_abbrev:saturday'); ?>
</a>
<a id="event-calendar-repeating-sunday" href="javascript:void(0);" class="event-calendar-repeating-unselected">
	<?php echo elgg_echo('event_calendar:day_abbrev:sunday'); ?>
</a>
</div>
<input type="hidden" name="event-calendar-repeating-monday-value" value="<?php echo $fd['event-calendar-repeating-monday-value']; ?>">
<input type="hidden" name="event-calendar-repeating-tuesday-value" value="<?php echo $fd['event-calendar-repeating-tuesday-value']; ?>">
<input type="hidden" name="event-calendar-repeating-wednesday-value" value="<?php echo $fd['event-calendar-repeating-wednesday-value']; ?>">
<input type="hidden" name="event-calendar-repeating-thursday-value" value="<?php echo $fd['event-calendar-repeating-thursday-value']; ?>">
<input type="hidden" name="event-calendar-repeating-friday-value" value="<?php echo $fd['event-calendar-repeating-friday-value']; ?>">
<input type="hidden" name="event-calendar-repeating-saturday-value" value="<?php echo $fd['event-calendar-repeating-saturday-value']; ?>">
<input type="hidden" name="event-calendar-repeating-sunday-value" value="<?php echo $fd['event-calendar-repeating-sunday-value']; ?>">
