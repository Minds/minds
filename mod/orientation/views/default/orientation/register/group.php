<?php 
$user = elgg_get_logged_in_user_entity();
?>

<div class="blurb">
		Want to start an open community? Maybe a secret one? Groups allow you to share media with people in a more focused environment. 
	</div>

<div class="orientation-table">
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Name
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'name',  'placeholder'=> 'eg. My Group Name')); ?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			Description
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/text', array('name'=>'desciption',  'placeholder'=> 'eg. A group about stuff.')); ?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			<?php echo elgg_echo('groups:membership'); ?>
		</div>
		<div class="orientation-table-cell">
			<?php echo elgg_view('input/dropdown', array(
				'name' => 'membership',
				'options_values' => array(
					ACCESS_PRIVATE => elgg_echo('groups:access:private'),
					ACCESS_PUBLIC => elgg_echo('groups:access:public')
				)
			));
			?>
		</div>
	</div>
	<div class="orientation-table-row">
		<div class="orientation-table-cell label">
			<?php echo elgg_echo('groups:visibility'); ?>
		</div>
		<div class="orientation-table-cell">
			
			<?php echo elgg_view('input/access', array(
				'name' => 'vis',
				'options_values' => array(
					AACCESS_PRIVATE => elgg_echo('groups:access:group'),
					ACCESS_PUBLIC => elgg_echo("PUBLIC")
				)
			));
			?>
		</div>
	</div>
</div>