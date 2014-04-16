<?php
/**
 * Minds Search CC User View
 * 
 */
 
$user= get_entity($vars['source']['guid'],'user');
if($user instanceof ElggUser){

} else {
	return false;
}
?>
<a href='<?php echo $user->getURL();?>'>
		<?php echo elgg_view('output/img', array('src'=>$user->getIconURL('large')));?>
		<h3><?php echo $user->name;?></h3>
		<p><b>minds channel</b></p>
</a>
