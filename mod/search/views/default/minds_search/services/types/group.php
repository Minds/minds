<?php
/**
 * Minds Search CC Group
 */
 
$group= get_entity($vars['group']['guid']);
if(!$group){
return false;
}

?>
<a href='<?php echo $group->getURL();?>'>
		<?php echo elgg_view('output/img', array('src'=>$group->getIconURL('large')));?>
		<h3><?php echo $group->name;?></h3>
		<p><b>minds group</b></p>
</a>
