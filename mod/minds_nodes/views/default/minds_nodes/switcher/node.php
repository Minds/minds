<?php
    $node = $vars['entity'];

    $url = elgg_get_site_url() . 'nodes/manage';
    $icon = elgg_get_site_url() . '_graphics/icons/default/small.png';
    if ($node->launched) {
	$url = $node->getURL();
	$icon = $node->getIcon('small');
    }
?>
<div class="node">
    <div class="icon">
	<a href="<?= $url; ?>" target="_blank"><img src="<?= $icon; ?>" /></a>
    </div>
    <h1><?= $node->launched ? elgg_view('output/url', array('target' => '_blank', 'text'=>$node->domain,'href'=>$url)) : elgg_view('output/url', array('text'=>'New node','href'=>$url)); ?></h1>
</div>