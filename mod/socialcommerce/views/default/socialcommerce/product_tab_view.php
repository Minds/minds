<?PHP
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
	 * Elgg view - product tab
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	$base_view = $vars['base_view'];
	
	if(elgg_is_admin_logged_in()){
		$filter = $vars['filter'];
		$baseurl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$replace_url = preg_replace('/deleted\/*/',"",$baseurl);
		$replace_url = preg_replace('/active\/*/',"",$replace_url);
		$url = preg_replace('/[\&\?]offset\=[0-9]*/',"",$replace_url);
		if (substr($url, -1) == '/') {
			$separator .= "";
		} else {
			$separator .= "/";
		}
?>
		<div id="product_nav">
			<ul>
				<?php if($filter == "deleted"){?>
					<li><a href="<?php echo $url.$separator; ?>active"><?php echo elgg_echo('active:products'); ?></a></li>
				<?php }else{?>
					<li><a href="<?php echo $url.$separator; ?>deleted"><?php echo elgg_echo('deleted:products'); ?></a></li>
				<?php }?>
			</ul>
			<div class="clear"></div>
		</div>
<?php 
	}
	echo $base_view; 
?>