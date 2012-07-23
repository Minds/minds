<?php 
	$slotGuid = $vars['slot']->getGUID();
	$dayGuid = $vars['dayguid'];
	
	if($vars['registered'] != true)
	{
		$checked = 'checked';
	}
	elseif(check_entity_relationship(elgg_get_logged_in_user_guid(), EVENT_MANAGER_RELATION_SLOT_REGISTRATION, $vars['slot']->getGUID()))
	{
		$checked = 'checked';
		?>
		<script type="text/javascript">
		$(function()
		{
			$('#slotguid_<?php echo $vars['slot']->getGUID();?>').parent().parent().find('.event_manager_program_day input[type=checkbox]').attr('checked', true);
		});
		</script>
		<?php 
	}
?><div class="event_manager_program_slot_view">
	<div class="event_manager_program_slot_view_time">
		<?php echo $vars['slot']->start_time;?> -  <?php echo $vars['slot']->end_time;?>
	</div>
	<div class="event_manager_program_slot_view_info">
		<div class="event_manager_program_slot_view_info_location"><?php echo $vars['slot']->location;?></div>
		<?php echo $vars['slot']->title;?><br /><span><?php echo nl2br($vars['slot']->description);?></span>
	</div>
	<input id="slotguid_<?php echo $vars['slot']->getGUID();?>" name="guid" type="checkbox" class="event_manager_program_slot_select" value="<?php echo $vars['slot']->getGUID();?>" <?php echo $checked;?> />
	<div class="clearfloat"></div>
</div>