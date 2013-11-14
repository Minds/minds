<?php

$node = $vars['entity'];

if(!elgg_instanceof($node,'object', 'node')){
return false;
}

if($node->paid()){
	if($node->launched){
		//display stats
		$link =  elgg_view('output/url', array('text'=>'Go to my node', 'href'=>$node->getURL(), 'class'=>'elgg-button elgg-button-action'));
                $content = $link;
	} else {
		//link to setup the node
		$setup_link =  elgg_view('output/url', array('text'=>'Setup', 'href'=>'nodes/node/'.$node->guid, 'class'=>'elgg-button elgg-button-action'));
		$content = $setup_link;
	}
} else {
	//promt for payment
	$order = $node->getOrder();
	if(!$order){ return false; }
	$order_link = elgg_view('output/url', array('text'=>'click here', 'href'=>$order->getURL()));
	$content = "We are still awaiting payment from you. Please $order_link for more.";
}

$tier = $node->getTier()->title;
$own_domain = $node->allowedDomain();
$content .= "<div class='stats'><p><b>Tier: </b>$tier</p>
		<p><b>Domain?: </b> $owner_domain</p>
</div>";

$params = array(
	'entity' => $node,
	'title' => $node->launched ? elgg_view('output/url', array('text'=>$node->domain,'href'=>$node->getURL())) : 'New node',
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => $content,
);

$params = $params + $vars;
$list_body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($owner_icon, $list_body);
