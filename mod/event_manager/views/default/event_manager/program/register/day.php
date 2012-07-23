<div class="event_manager_program" id="dayguid_<?php echo $vars['eventday']->getGUID();?>">
	<div class="event_manager_program_slots">
		<div class="event_manager_program_day">
			<?php
				echo $vars['eventday']->title.' ('.date(EVENT_MANAGER_FORMAT_DATE_EVENTDAY, $vars['eventday']->date).')';
				
				if($vars['registered'] != true)
				{
					$checked = 'checked';
				}
			?> <input <?php echo $checked;?> type="checkbox" class="event_manager_program_day_select" value="<?php echo $vars['eventday']->getGUID();?>" />
		</div>
		<div class="clearfloat"></div>
		<?php 
		$eventDaySlots = $vars['eventday']->getEventSlots();
		if($eventDaySlots)
		{
			foreach($eventDaySlots as $eventSlot)
			{
				if(!empty($eventSlot))
				{
					echo elgg_view('event_manager/program/register/slot', array('slot' => $eventSlot, 'dayguid' => $vars['eventday']->getGUID(), 'registered' => $vars['registered']));
				}
			}
		}?>
	</div>
</div>