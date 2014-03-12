<div class="left">
	<?php echo elgg_view_menu('site',array('sort_by'=>'priority')); ?>
</div>

<div class="center">
	<a href="<?php echo elgg_get_site_url();?>" class="logo">
		  <?php if (elgg_get_plugin_setting('logo_override', 'minds_themeconfig')) { ?>
 		          <img src="<?php echo elgg_get_site_url();?>themeicons/logo_topbar/<?php echo elgg_get_plugin_setting('logo_override_ts', 'minds_themeconfig'); ?>.png"/>
        	<?php } else { ?>

			<img src="<?php echo elgg_get_site_url();?>/_graphics/minds_2.png"/>
		<?php } ?>
	</a>
	<?php echo elgg_view('search/search_box'); ?>
</div>

<?php $user = elgg_get_logged_in_user_entity();?>
<div class="right">
	<?php if(elgg_is_logged_in()){ ?>
	
	<div class="actions">
		<span class="notifications">
			<?php echo elgg_view_menu('notifications'); ?>
		</span>
	</div>
	
	<div class="owner_block">
		<a href="<?php echo $user->getUrl();?>">
			<span class="text">
				<h3><?php echo $user->name;?></h3>
				<i><?php echo $user->username;?></i>
			</span>
			<img src="<?php echo $user->getIconURL('small');?>"/>
		</a>
	</div>
	
	<span class="more">
		<?php //if user is admin
		if(elgg_is_admin_logged_in()){
		?>
		<a href="<?php echo elgg_get_site_url();?>admin">admin panel</a> |
		<?php } ?>
		<a href="<?php echo elgg_get_site_url();?>settings/user/<?php echo $user->username;?>">Settings</a> | <a href="<?php echo elgg_get_site_url();?>action/logout">Exit</a>
	</span>	
	<?php } else { ?>
		<?php echo elgg_view_form('login'); ?>
		
	<?php } ?>
</div>
