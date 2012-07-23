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
	 * Elgg version - manage version
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	$entity = $vars['entity'];
	$stores_guid = $entity->guid;
	
	$add_version_url = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/version_edit/'.$stores_guid;
?>
<div class="version_details">
	<div class="version_heading">
		Version Details
	</div>
	<?php 
	if($CONFIG->allow_multiple_version_digit_product){
	?>
		<div class= "new_versions">
			<a href="<?php echo $add_version_url;?>"><?php echo elgg_echo('socialcommerce:multi_prod_ver:add_new_version');?></a>	
		</div>
	<?php 
	}
	?>
	<div class="clear"></div>
	<div class="all_versions">
		<?php 
		$options = array('relationship' => 'version_release',
						 'relationship_guid' => $entity->guid);
		$versions = elgg_get_entities_from_relationship($options);
		if($versions){
			foreach($versions as $version)
			{
				$version_id = $version->guid;
				$releaded_version =  $version->version_release;
				$summary = $version->version_summary;				
				$version_url = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/version_edit/'.$stores_guid.'/'.$version_id;				
				$ts = time();
				$token = generate_action_token($ts);
				$version_del_url = 	$CONFIG->wwwroot.'action/'.$CONFIG->pluginname.'/version_delete?ver_id='.$version_id.'&store_id='.$stores_guid.'&__elgg_token='.$token.'&__elgg_ts='.$ts;
			?>
				<a href="<?php echo $version_url;?>"><?php echo $releaded_version;?></a>
				<a style="margin-left:8px;" onclick="return confirm('<?php echo elgg_echo('socialcommerce:multi_prod_ver:delete:confirm');?>');" href="<?php echo $version_del_url;?>"><?php echo elgg_echo('socialcommerce:multi_prod_ver:delete_version');?></a>
				<br>
		<?php 
			}
		}
		?>
	</div>
</div>