<?php

$node = $vars['entity'];

if(isset($vars['directory_output']) || elgg_get_context() == 'channel'){ ?>

	<a href="http://<?= $node->domain ?>">
		<img src="//<?= $node->domain ?>/themeicons/logo_main/<?= time() ?>.png" class="node_logo" onerror="this.src='<?=elgg_get_site_url()?>_graphics/minds_2.png'"/>
		<h3><?= $node->domain ?></h3>
	</a>
<?php
	return true;	

}

$tier = $node->getTier();
$own_domain = $node->allowedDomain() ? 'yes' : 'no';
$expire = $node->expires();
$stats= "<div class='stats'><p><b>Tier: </b>$$tier->price</p>
                <p><b>Domain?: </b> $own_domain</p>
                <p><b>Expires: </b> $expire days</p>
";

if (!$vars['hide_buttons']) {
   // if($node->paid()){
            if($node->launched){
                    //display stats
                    $link =  elgg_view('output/url', array('text'=>'Load', 'href'=>$node->getURL(), 'class'=>'elgg-button elgg-button-action node-button'));
                    $link .= " ". elgg_view('output/url', array('text'=>'Edit', 'href'=>'nodes/node/'.$node->guid, 'class'=>'elgg-button elgg-button-action node-button'));
					$link .= " ". elgg_view('output/confirmlink', array('text'=>'Delete', 'href'=>elgg_add_action_tokens_to_url('action/node/delete/?guid='.$node->guid), 'class'=>'elgg-button elgg-button-action node-button'));
                    $content = $link;
            } else {
                    //link to setup the node
                    $setup_link =  elgg_view('output/url', array('text'=>'Setup', 'href'=>'nodes/node/'.$node->guid, 'class'=>'elgg-button elgg-button-action'));
                    $content = $setup_link;
            }
            
			if($node->getTier()->price == 0){
				//upgrade icon
				$content .= " ". elgg_view('output/url', array('text'=>'Upgrade', 'href'=>'nodes/upgrade/'.$node->guid, 'class'=>'elgg-button elgg-button-action node-button'));
			}
   // } else {
            //promt for payment
          //  $order = $node->getOrder();
           
           // $order_link = elgg_view('output/url', array('text'=>'click here', 'href'=>$order->getURL()));
           // $content = "We are still awaiting payment from you. Please $order_link for more.";
  //  }
}

$params = array(
	'entity' => $node,
	'title' => $node->launched ? elgg_view('output/url', array('text'=>$node->domain,'href'=>$node->getURL())) : 'New node',
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => $stats . $content,
);

$params = $params + $vars;
$list_body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($owner_icon, $list_body);
