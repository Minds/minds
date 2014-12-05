<?php
    $node = $vars['entity'];

?>
<div class="node">
    <div class="icon">
	
    </div>
    <h1><?= $node->launched ? elgg_view('output/url', array('target' => '_blank', 'text'=>$node->domain,'href'=>$node->getURL())) : 'New node'; ?></h1>
</div>