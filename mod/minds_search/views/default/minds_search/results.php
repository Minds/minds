<?php
/**
 * Elastic Search 
 *
 * @package elasticsearch
 */

$results = $vars['results'];

$body .= '<ul class="elgg-list search-list">';

foreach($results as $result){
	
	$view = elasticsearch_get_search_view(array('type'=>$result->getType(), 'subtype'=> $result->getSubtype()));
	
	$id = "elgg-{$result->getType()}-{$result->getGUID()}";
	$body .= "<li id=\"$id\" class=\"elgg-item\">";
	$body .= "<ul class='elgg-menu elgg-menu-entity elgg-menu-hz elgg-menu-entity-default'>";
		if($result->getType() == 'object') { $body .= "<li>" . elgg_echo($result->getSubtype())  ."</li>"; } else {$body .= "<li>" . elgg_echo($result->getType())  ."</li>";}
		if($result->license){ $body .= "<li>" . elgg_echo('minds:license:' . $result->license)  ."</li>"; }
	$body .= "</ul>";
	$body .= elgg_view($view, array(
			'item' => $result,
		));
	$body .= '</li>';
	
}

$body .= '</ul>';

echo $body;
