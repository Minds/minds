<?php

use minds\plugin\payments;

gatekeeper();

$user = elgg_get_logged_in_user_entity();

$node_guid = get_input('node_guid');
$node = new MindsNode($node_guid);

try{
$card = new payments\entities\card();
$c = $card->create(array(
		'type' => $_POST['card_type'],
		'number' => (int) $_POST['number'],
		'month' => $_POST['month'],
		'year' => $_POST['year'],
		'sec' => $_POST['sec'],
		'name' => $_POST['name'],
		'name2' => $_POST['name2']
	));
}catch(\Exception $e){
	\register_error($e->getMessage());
	forward($node->getURL());
	return false;
}


if(!$c)
	return forward(REFERRER);

$card->save();

$node->tier_guid = get_input('tier_guid');
$node->nextcharge = time() + 2592000;
//configure the details
$transaction_id = payments\start::createPayment("Node upgrade for $node->domain", $node->getTier()->price, $c->id);

$node->save();

system_message('Success!');
forward('/nodes/manage');
