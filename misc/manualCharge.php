<?php

require('/var/www/elgg/engine/start.php');
elgg_set_ignore_access(true);
$db = new minds\core\data\call('entities_by_time');

$nodes = minds\core\entities::get(array('subtype'=>'node', 'limit'=>10000));
foreach($nodes as $node){

	if($node->allowedDomain()){
		if(!$node->nextcharge)
			$node->nextcharge = $node->time_created + 2592000; //30 days from creation
		
		$time = date('d/m',time());
		$created = date('d/m', $node->time_created);
		$next = date('d/m', $node->nextcharge);
		//echo "NOW: $time | Created: $created | Next: $next\n";
		if($node->nextcharge < time()){
			echo "Initializing payment for $node->domain \n";
			if($node->domain == 'www.warriorcapital.us' || $node->domain == 'social.markharding.co'){

				$cards = minds\core\entities::get(array('subtype'=>'card', 'owner_guid'=>$node->owner_guid));
                   	    	foreach($cards as $card){
                        	        $added = date('d/m', $card->time_created);
						if(!$card->card_id)
							continue;
						$id = minds\plugin\payments\start::createPayment('Hosting for '.$node->domain, $node->getTier()->price, $card->card_id);
						if($id){
							$node->nextcharge = time() + 2592000;
							$node->save();
							break;
						}
				}			

			} else {
				$node->nextcharge = ((((60 * 60) * 60) * 24) * 365); //one year
				$node->save();
			}
		}		

	}
}
