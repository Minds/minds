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
	 * Elgg webservicex currency api - actions
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	function get_exchange_rate($from_code, $to_code){
		$post_fields = "FromCurrency=" . urlencode(strtoupper($from_code)) . "&ToCurrency=" . urlencode(strtoupper($to_code));
		$target_url = "http://www.webservicex.net/CurrencyConvertor.asmx/ConversionRate";
		$rtn = get_response_from_api($target_url, $post_fields,60);
		
		if (!$rtn) {
			return elgg_echo('provider:request:unavailable');
		}
		
		$xml = @simplexml_load_string($rtn);
		if(!is_object($xml)) {
			return elgg_echo('provider:request:Invalid:code');
		}

		if (count($xml) === 0) {
			return (double)$xml;
		} else {
			return (double)$xml[0];
		}
	}
	
	function get_response_from_api($Path, $Vars="", $timeout=60){
		$result = null;
		//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"splugin_settings",
						'owner_guids'		=>	$_SESSION['user']->guid,
					);
		$splugin_settings = elgg_get_entities($options);
		//$splugin_settings = get_entities('object','splugin_settings',$_SESSION['user']->guid);
		if($splugin_settings){
			$splugin_settings = $splugin_settings[0];
		}

		if(function_exists("curl_exec")) {
			$ch = curl_init($Path);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if (!ISC_SAFEMODE && ini_get('open_basedir') == '') {
				@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			}
			if($timeout > 0 && $timeout !== false) {
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			}
			
			// Setup the proxy settings if there are any
			if ($splugin_settings->http_proxy_server) {
				curl_setopt($ch, CURLOPT_PROXY, $splugin_settings->http_proxy_server);
				if ($splugin_settings->http_proxy_port) {
					curl_setopt($ch, CURLOPT_PROXYPORT, $splugin_settings->http_proxy_port);
				}
			}
			if($splugin_settings->http_varify_ssl)
				$http_varify_ssl = 1;
			else 
				$http_varify_ssl = 0;
				
			if ($http_varify_ssl == 0) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			}
			define('ISC_SAFEMODE', 10102);
			
			if (defined('CURLOPT_ENCODING')) {
				curl_setopt($ch, CURLOPT_ENCODING, '');
			}

			if($Vars != "") {
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $Vars);
			}
			$result = curl_exec($ch);
		} else {
			// Use fsockopen instead
			$Path = @parse_url($Path);
			if(!isset($Path['host']) || $Path['host'] == '') {
				return null;
			}
			if(!isset($Path['port'])) {
				$Path['port'] = 80;
			}
			if(!isset($Path['path'])) {
				$Path['path'] = '/';
			}
			if(isset($Path['query'])) {
				$Path['path'] .= "?".$Path['query'];
			}

			if(isset($Path['scheme']) && strtolower($Path['scheme']) == 'https') {
				$socketHost = 'ssl://'.$Path['host'];
				$Path['port'] = 443;
			}
			else {
				$socketHost = $Path['host'];
			}

			$fp = @fsockopen($Path['host'], $Path['port'], $errorNo, $error, 10);
			if($timeout > 0 && $timeout !== false) {
				@stream_set_timeout($fp, 10);
			}
			if(!$fp) {
				return null;
			}

			$headers = array();

			// If we have one or more variables, perform a post request
			if($Vars != '') {
				$headers[] = "POST ".$Path['path']." HTTP/1.0";
				$headers[] = "Content-Length: ".strlen($Vars);
				$headers[] = "Content-Type: application/x-www-form-urlencoded";
			}
			// Otherwise, let's get.
			else {
				$headers[] = "GET ".$Path['path']." HTTP/1.0";
			}
			$headers[] = "Host: ".$Path['host'];
			$headers[] = "Connection: Close";
			$headers[] = ""; // Extra CRLF to indicate the start of the data transmission

			if($Vars != '') {
				$headers[] = $Vars;
			}

			if(!fwrite($fp, implode("\r\n", $headers))) {
				return false;
			}
			while(!feof($fp)) {
				$result .= @fgets($fp, 12800);
			}
			@fclose($fp);

			// Strip off the headers. Content starts at a double CRLF.
			$result = explode("\r\n\r\n", $result, 2);
			$result = $result[1];
		}
		return $result;
	}
?>