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
	 * Elgg form - category
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$ftp_host_url = trim(get_input('ftp_host_url'));
	$ftp_port = trim(get_input('ftp_port'));
	$ftp_user = trim(get_input('ftp_user'));
	$ftp_password = trim(get_input('ftp_password'));
	$ftp_upload_dir = trim(get_input('ftp_upload_dir'));
	$ftp_http_path = trim(get_input('ftp_http_path'));
	$ftp_base_path = trim(get_input('ftp_base_path'));
	//$destDir = $CONFIG->ftp_base_path.$CONFIG->ftp_upload_dir;
		
	$ftp = new PS_FTPClass('', '', $ftp_port, $ftp_host_url, $ftp_user, $ftp_password);
	$dir_list ="Directory listing failer";
	if($ftp){
		$root_lists =  $ftp->f_nlist();		
		//$root_lists =  $ftp->ftp_rawlist();
		if(count($root_lists)>0)
			foreach($root_lists as $root_list){
				if(!strstr($root_list, '.'))
					$dir_radio .= '<input type="radio" name="base_dir" onclick="javascript:get_dir(this);" value="'.$root_list.'">'.$root_list.'</input><br />';
			}
			$label = elgg_echo('Select directory');
				$dir_list = <<<EOF
					<div class="padding-top_ftp">										
						<div class="settings_field_left" style="width:100px;">
							<div class="left">
								{$label}:
							</div>
						</div>		
						<div id="root_directories" class="left" style="width: 500px;">								
							{$dir_radio}
						</div>	
					</div>
EOF;
											
	}
	$ftp->f_close();
	echo $dir_list;
	exit;
	
?>