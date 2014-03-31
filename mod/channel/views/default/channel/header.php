<?php
$user = $vars['user'];
?>

<div class="channel-header">

	<div class="avatar">
		<?php echo elgg_view('output/img', array('src'=>$user->getIconURL('large'))); ?>
		<?php if(elgg_get_logged_in_user_guid() == $user->guid){ ?>
			<a class="avatar-edit" href="<?php echo elgg_get_site_url();?>channel/<?php echo $user->username;?>/avatar">
				Edit
			</a>
		<?php } ?>
	</div>
	<div class="owner-block">
		<h1><?php echo $user->name;?></h1>
		<h3><?php echo minds_filter($user->briefdescription);?></h3>
		<?php echo  elgg_view_form('deck_river/post',  
						array(	'action'=>'action/deck_river/post/add', 
								'name'=>'elgg-wall-news', 
								'enctype' => 'multipart/form-data'
						),
						array(	'to_guid'=> $user->guid, 
						 	//	'access_id'=> ACCESS_PRIVATE, 
						 		'hide_accounts'=>true
						)
					); ?>	</div>
	<div class="actions">
		<?php echo $user->guid != elgg_get_logged_in_user_guid() ? elgg_view('channel/subscribe', array('entity'=>$user)) : '';?>
		<?php if(elgg_get_logged_in_user_guid() == $user->guid){ ?>
				<div class="edit-button tooltip n" title="edit your channel">
					<?php echo elgg_view('output/url', array('href'=>"channel/$user->username/custom",'class'=>'elgg-button elgg-button-action', 'text'=>elgg_echo('channel:customise')));?>
				</div>
		<?php } ?>
	</div>
	 <?php echo elgg_view('channel/filter', array('user'=>$user, 'selected'=>$vars['selected'])); ?>
</div>
