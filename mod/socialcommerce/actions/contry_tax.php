<?php
	global $CONFIG;
	$country_code = trim(get_input('code'));
	//Depricated function replace
	$options = array(	'metadata_name_value_pairs'	=>	array('tax_country' => $country_code),
						'types'				=>	"object",
						'subtypes'			=>	"addtax_country",
						'limit'				=>	1,
					);
	$tax_entity = elgg_get_entities_from_metadata($options);
	//$tax_entity = get_entities_from_metadata('tax_country',$country_code,'object','addtax_country','',1);
	if(!$tax_entity) {
		$tax_rate = '';
	}else {	
		foreach($tax_entity as $tax_entitys)
		{
				$tax_rate = $tax_entitys->taxrate;
		}
	}
	echo $tax_rate;
	die();
?>