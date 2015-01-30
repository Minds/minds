<?php

$sections =  $vars['sections'] ?: array();
usort($sections, function($a, $b){
	return $a->position - $b->position;
});
?>

<div class="cms-sections <?= elgg_is_admin_logged_in() ? 'cms-sections-editable' :''?>" data-group="<?= $vars['group'] ?>">

    <i class="arrow-down entypo">&#59417;</i>

<?php 
	if(!empty($sections)){
		foreach($sections as $section): 
			echo elgg_view('cms/sections/section', array('section'=>$section)); 
		endforeach; 
	}?>

</div>
