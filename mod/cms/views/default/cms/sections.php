<?php

$sections = $vars['sections'];
usort($sections, function($a, $b){
	return $a->time_created - $b->time_created;
});
?>

<div class="cms-sections" data-group="<?= $vars['group'] ?>">

<?php foreach($sections as $section): echo elgg_view('cms/sections/section', array('section'=>$section)); endforeach; ?>

</div>