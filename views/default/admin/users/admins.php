<?php
//$admins = elgg_list_entities(array(), 'elgg_get_admins');
$users = elgg_get_entities(array(
    'type' => 'user',
    'limit' => 9999,
));
   
$admins = array();
foreach ($users as $user) {
    if ($user->isAdmin())
        $admins[] = $user;
}
 
$admins = elgg_view_entity_list($admins);
	
?>
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('admin:statistics:label:admins'); ?></h3>
	</div>
	<div class="elgg-body">
		<?php echo $admins; ?>
	</div>
</div>
