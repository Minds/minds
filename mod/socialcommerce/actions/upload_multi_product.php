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
	 * Elgg upload multi product action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	$dir_path =  $CONFIG->pluginspath.$CONFIG->pluginname."/upload_csv/";
	$redirect = $CONFIG->wwwroot . $CONFIG->pluginname . "/add_multiple_product";	
	if (($_FILES["fileToUpload"]["type"] == "text/csv") && ($_FILES["fileToUpload"]["size"] < 20000)){
		  if ($_FILES["fileToUpload"]["error"] > 0){
		  		register_error("Return Code: " .$_FILES["fileToUpload"]["error"]);
		  		$redirect = $CONFIG->wwwroot."{$CONFIG->pluginname}/upload_multiple";		    	
		  }else{
	    	if (file_exists($dir_path.$_SESSION['user']->username)){
	    		unlink($dir_path.$_SESSION['user']->username);
	      	}
          		chmod($dir_path, 0777);
	      		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$dir_path . $_SESSION['user']->username.".csv");
          		chmod($dir_path . $_SESSION['user']->username.".csv", 0777);
          		system_message(elgg_echo('stores:upload:multiple:success'));	          	
		   }
	}else{
	  	register_error("Invalid file");
	  	$redirect = $CONFIG->wwwroot."{$CONFIG->pluginname}/upload_multiple";
	}	
	forward($redirect);
?> 