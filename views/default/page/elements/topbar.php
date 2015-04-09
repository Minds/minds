<div class="left">
	<span title="Navigation" class="entypo tooltip n menu-toggle">&#57349;</span>
	<?php //echo elgg_view_menu('site',array('sort_by'=>'priority')); ?>
	<?php echo elgg_view('search/search_box'); ?>
	<?= elgg_view_menu('topbar') ?>
</div>

<div class="center">
	<div class="logo">
		<a href="<?php echo elgg_is_logged_in() ? elgg_get_site_url() .'newsfeed' : elgg_get_site_url();?>">
			  <?php if (elgg_get_plugin_setting('logo_override', 'minds_themeconfig')) { ?>
	 		          <img src="<?php echo elgg_get_site_url();?>themeicons/logo_topbar/<?php echo elgg_get_plugin_setting('logo_override_ts', 'minds_themeconfig'); ?>.png"/>
	        	<?php } elseif(Minds\Core\plugins::isActive('minds_themeconfig')) { ?>
	        		<h1><?= elgg_get_site_entity()->name ?></h1>
				<?php } else { ?>
					<img src="<?php echo elgg_get_site_url();?>_graphics/minds_2.png" class="minds-com"/>
			<?php 
				}
			
			if(elgg_is_admin_logged_in()){
				$href = elgg_get_site_url() . 'admin/appearance/logo';
				echo "<a class=\"tip tip-logo\" href=\"$href\"><span class=\"entypo\">&#9881;</span> change</a>";
			}
		?>
		</a>
	</div>

</div>

<?php $user = elgg_get_logged_in_user_entity();?>
<div class="right">
	<?php if(elgg_is_logged_in()){ ?>
	
	<div class="actions">
		<span class="notifications">
			<?php echo elgg_view_menu('notifications'); ?>
		</span>
		<?= elgg_view('page/elements/topbar/right/actions') ?>
	</div>
	
	<div class="owner_block">
		<a href="<?php echo $user->getUrl();?>">
			<span class="text">
				<h3><?php echo $user->name;?></h3>
			</span>
			<img src="<?php echo $user->getIconURL('small');?>"/>
		</a>
	</div>
	
	<?php } else { ?>

			<?php 
				//if(Minds\Core\plugins::isActive('minds_nodes'))
				//	echo elgg_view('output/url', array('text'=>'Create Site', 'href'=>elgg_get_site_url() .'nodes/launch', 'class'=> 'elgg-button minds-button-launch'));
				
				echo elgg_view('output/url', array('text'=>'Create Channel', 'href'=>elgg_get_site_url() .'register', 'class'=> 'elgg-button minds-button-register'));
				echo elgg_view('output/url', array('text'=>'Login', 'href'=>elgg_get_site_url() .'login', 'class'=> 'elgg-button minds-button-login'));
			?>

	<?php } ?>
</div>
