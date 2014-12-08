<?php
    $node = $vars['entity'];

    $url = elgg_get_site_url() . 'nodes/node/'.$node->guid;
    $icon = $node->getIcon('small');
    if ($node->launched) {
	$url = $node->getURL();
    }
?>
<div class="node">
    <div class="icon">
	<a href="<?= $url; ?>" target="_blank"><img src="<?= $icon; ?>" /></a>
    </div>
    <h1><?= $node->launched ? elgg_view('output/url', array('target' => '_blank', 'text'=>$node->domain,'href'=>$url)) : elgg_view('output/url', array('text'=>'New node','href'=>$url)); ?></h1>
</div>