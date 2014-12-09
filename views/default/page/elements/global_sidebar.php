<?php 
$class = '';

if(elgg_is_logged_in() && (elgg_get_context() == 'admin' || elgg_get_context() == 'anypage' || (isset($_COOKIE['sidebarOpen']) && $_COOKIE['sidebarOpen'] == 'true'))){
	$class	= 'show show-default';
}
?>
<div class="global-sidebar <?php echo $class; ?>">
		<?php echo elgg_view_menu('site',array('sort_by'=>'priority'));  ?>
    
		<?= elgg_view('minds_nodes/switcher', array('entities' => elgg_get_entities(array(
		    'type' => 'object',
		    'subtype' => 'node',
		    'limit' => 999,
		    'owner_guid' => elgg_get_logged_in_user_guid()
		)))); ?>	
		
		<!-- Admin Links -->
		<?php 
			if(elgg_is_admin_logged_in()){
				$default_context = elgg_get_context();
				elgg_set_context('admin');
				echo elgg_view_menu('admin');
				elgg_set_context($default_context);
			}
		?>
		
		<!-- User action links -->
		<?php echo elgg_view_menu('actions'); ?>
		
		<!-- We have a footer too.. -->
		<?php //echo elgg_view('page/elements/global_sidebar_footer'); ?>
</div>
