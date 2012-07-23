<?php
	/*****************************************************************************\
	+-----------------------------------------------------------------------------+
	| Elgg Socialcommerce Plugin                                                  |
	| Copyright (c) 2009-20010 Cubet Technologies <socialcommerce@cubettech.com>  |
	| All rights reserved.                                                        |
	+-----------------------------------------------------------------------------+
	| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
	| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
	| AT THE FOLLOWING URL: http://socialcommerce.elgg.in/license.html            |
	|                                                                             |
	| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
	| THIS  SOFTWARE   PROGRAM  AND   ASSOCIATED   DOCUMENTATION    THAT  CUBET   |
	| TECHNOLOGIES (hereinafter referred as "THE AUTHOR") IS FURNISHING OR MAKING |
	| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
	| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
	| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
	| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
	| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
	| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
	| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
	| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
	| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
	| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
	| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
	|                                                                             |
	+-----------------------------------------------------------------------------+
	\*****************************************************************************/

	/**
	 * Elgg view - rating
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$path = $CONFIG->wwwroot."mod/{$CONFIG->pluginname}/";
	$rater = <<<EOF
		<script>
			var base_url = "{$CONFIG->wwwroot}";
		</script>
		<script type="text/javascript" language="javascript" src="{$path}js/behavior.js"></script>
		<script type="text/javascript" language="javascript" src="{$path}js/rating.js"></script>
EOF;
	
	$id = $vars['id'];
	$units = $vars['units'];
	$static = $vars['static'];
	$context = elgg_get_context();
	elgg_set_context('add_order');
	//set some variables
	$user_guid = $_SESSION['user']->guid;
	if (!$units) {$units = 5;}
	if (!$static) {$static = FALSE;}
	$rating_unitwidth = 16;
	$product = get_entity($id);
	//Depricated function replace
	$options = array(	'metadata_name_value_pairs'	=>	array('product_guid' => $id),
					'types'				=>	"object",
					'subtypes'			=>	"rating",
					
				);
	$rating_val = elgg_get_entities_from_metadata($options);
	//$rating_val = get_entities_from_metadata('product_guid',$id,'object','rating');
	if(!$rating_val){
		$rating_val = new ElggObject();
		
		$rating_val->subtype="rating";
		$rating_val->access_id = 2;
		$rating_val->owner_guid = $product->owner_guid;
		$rating_val->container_guid = $product->owner_guid;
		$rating_val->product_guid = $id;
		$rating_val->description = '';
		$rating_val->total_votes = 0;
		$rating_val->total_value = 0;
	}else{
		$rating_val = $rating_val[0];
	}
	if ($rating_val->total_votes < 1) {
		$count = 0;
	} else {
		$count = $rating_val->total_votes; //how many votes total
	}
	$current_rating=$rating_val->total_value; //total number of rating added together and stored
	$tense=($count==1) ? "vote" : "votes"; //plural form votes/vote
	
	if($user_guid > 0){
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('product_guid' => $id),
						'types'				=>	"object",
						'subtypes'			=>	"rating",
						
					);
		$numbers = elgg_get_entities_from_metadata($options);
		//$numbers = get_entities_from_metadata('product_guid',$id,'object','rating');
		if($numbers){
			$numbers = $numbers[0];
			if(strstr($numbers->description,'"'.$user_guid.'"')){
				$voted = $numbers;
			}
		}
	}else{
		$voted = true;
	}
	// now draw the rating bar
	$rating_width = @number_format($current_rating/$count,2)*$rating_unitwidth;
	$rating1 = @number_format($current_rating/$count,1);
	$rating2 = @number_format($current_rating/$count,2);
	
	if ($static == 'static') {
	
		$static_rater = array();
		$static_rater[] .= "\n".'<div class="ratingblock">';
		$static_rater[] .= '<div id="unit_long'.$id.'">';
		$static_rater[] .= '<ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
		$static_rater[] .= '<li class="current-rating" style="width:'.$rating_width.'px;">Currently '.$rating2.'/'.$units.'</li>';
		$static_rater[] .= '</ul>';
		$static_rater[] .= '<p class="static">'.$id.'. <strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.') <em>This is \'static\'.</em></p>';
		$static_rater[] .= '</div>';
		$static_rater[] .= '</div>'."\n\n";
		$rater .= join("\n", $static_rater);
	} else {
	
	      $rater .='';
	      $rater.='<div class="ratingblock">';
	
	      $rater.='<div id="unit_long'.$id.'">';
	      $rater.='  <ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
	      $rater.='     <li class="current-rating" style="width:'.$rating_width.'px;">Currently '.$rating2.'/'.$units.'</li>';
	
	      for ($ncount = 1; $ncount <= $units; $ncount++) { // loop from 1 to the number of units
	           if(!$voted) { // if the user hasn't yet voted, draw the voting stars
	              $rater.='<li><a href="db.php?j='.$ncount.'&amp;q='.$id.'&amp;t='.$user_guid.'&amp;c='.$units.'" title="'.$ncount.' out of '.$units.'" class="r'.$ncount.'-unit rater" rel="nofollow">'.$ncount.'</a></li>';
	           }
	      }
	      $ncount=0; // resets the count
	
	      $rater.='  </ul>';
	      $rater.='  <p';
	      if($voted){ $rater.=' class="voted"'; }
	      $rater.='> <strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.')';
	      $rater.='</p>';
	      $rater.='</div>';
	      $rater.='</div>';
	}
	echo $rater;
	elgg_set_context($context);
?>