<?php
/**
 * Batch changes the tiers table
 */
    
$tiers = array();

foreach($_REQUEST as $k=>$v){
	
	foreach(minds_tiers_get_features() as $feature){
	
		if(strpos($k, ":$feature") !== FALSE){
			$tier_guid = str_replace(":$feature", '', $k);
			if(!isset($tiers[$tier_guid])){
				$tiers[$tier_guid] = new MindsTier($tier_guid);
			}
			$tiers[$tier_guid]->$feature = $v;
		}
	}
	
}
foreach($tiers as $tier){
	$tier->save();
}


forward(elgg_get_site_url() . 'admin/minds_tiers/manage');
