<?php
/*
$Id: fedexdc.php,v 1.3 2004/08/12 12:45:18 jay.powers Exp $
Copyright (c) 2004 Vermonster LLC
All rights reserved.

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


--------------------------------------------------------------------
FedEx-DirectConnect - PHP interface to FedEx Direct Connect API

This class has been developed to send transactions to FedEx's
Ship Manager Direct API.  It can be used for all transactions
the API can support.  For more detailed information please
referrer to FedEx's documentation located at their website.
http://www.fedex.com/us/solutions/wis/.  Here you will be able
to download "TagTransGuide.pdf" which outlines all the FedEx
codes needed to send calls to their API.

This class requires you have PHP CURL support.

To submit a transaction to FedEx's Gateway server you must have a valid
FedEx Account Number and a FedEx Meter Number.  To gain access
and receive a Meter Number you must send a subscribe () request to
FedEx containing your FedEx account number and contact information.

Questions, Comments

Jay Powers
jay@vermonster.com

Vermonster LLC
312 Stuart St.
Boston, MA 02116

*/
// Contact FedEx and register for the API address.  It will look like
// https://xxx.xxx.com/GatewayDC
define('FEDEX_URI', 'https://gatewaybeta.fedex.com/GatewayDC', true);

// HTTP timeout
define('REQUEST_TIMEOUT', 15, true);

// two methods available CURL | HTTP_Request
define('REQUEST_TYPE', 'CURL', true);
global $CONFIG;
require_once($CONFIG->path.'mod/'.$CONFIG->pluginname.'/modules/shipping/fedex/fedex-tags.php');

class FedExDC extends FedExTags{

        var $VERSION = '1.02';
        var $NAME = 'FedExDC';
        var $ERROR_STR = false;

        //this will be the field returned by FedEx
        //containing the binary image data
        var $image_key;

        // FedEx API URI
        var $fedex_uri;

        // set the timeout
        var $timeout;
        
        // If you do want to fedex-tags array set this to false
        var $strict_tag;
        
        // Request params
        var $request_params = array();
        
        // request type - currently CURL and HTTP_Request (must install pear HTTP_Request)
        var $request_type;
        // Array of data from FedEx
        
        var $rHash = array();
        
        // Debug String
        var $debug_str = '';

        /**
        * constructor: loads account# and meter#
        *
        * @param    int $account FedEx Account number
        * @param    int $meter FedEx meter number
        * @param    array $params Associative array of parameters listed:
        *                       fedex_uri: FedEx API URI
                                fedex_host: Host for FedEx
                                referer: Referering Host
                                timeout: Connection timeout in seconds.
        *
        * @access public
        */
        function FedExDC ($account='', $meter='', $params = array()) {
            
            $this->FedExTags();

            $this->account  = $account;
            $this->meter    = $meter;
            $this->time_start = $this->getmicrotime();

            // param defaults
            $this->fedex_uri    = FEDEX_URI;
            $this->timeout      = REQUEST_TIMEOUT;
            $this->request_type = REQUEST_TYPE;
            $this->image_key =  188;
            $this->strict_tag = true;
            $this->request_params = array();
            
            foreach ($params as $key => $value) {
                $this->{$key} = $value;
            }
        }

        /**
        * Sets debug information
        *
        * @param    string $string debug data
        * @access   private
        */
        function debug($string){
            $this->debug_str .= get_class($this).": $string\n";
        }

        /**
        * returns error string if present
        *
        * @return   boolean string
        * @access   public
        */
        function getError(){
            if($this->ERROR_STR != ""){
                return $this->ERROR_STR;
            }
            return false;
        }

        /**
        * sets error string
        *
        * @param    string $str
        * @access   private
        */
        function setError($str){
            $this->ERROR_STR .= $str;
        }

        /**
        * microtime
        *
        * @return   float
        * @access   private
        */
        function getmicrotime(){
            list($usec, $sec) = explode(" ",microtime());
            return ((float)$usec + (float)$sec);
        }

        /**
        * creates FedEx buffer string
        *
        * @param    int $uti FedEx transaction UTI
        * @param    array $vals values to send to FedEx
        * @return   string
        * @access   public
        */
        function setData($meth, $vals) {
            $this->sBuf = '';
            if (empty($vals[0]))    $this->sBuf .= '0,"' . $this->FE_TT[$meth][0] . '"';
            if (empty($vals[3025])) $this->sBuf .= '3025,"' . $this->FE_TT[$meth][1] . '"';
            if (empty($vals[10]) and isset($this->account)) $this->sBuf .= '10,"' . $this->account . '"';
            if (empty($vals[498]) and isset($this->meter))  $this->sBuf .= '498,"' .$this->meter. '"';

            foreach ($vals as $key => $val) {

                // Get rid of the junk
                $key = trim($key);
                
                if ($this->strict_tag) {
                    $key = $this->fieldNameToTag($key);
                }
                
                // Empty value should not be sent (except for 99).
                if (empty($val)) continue;
                
                // Get rid of the junk
                $val = trim($val);
                
                // %-escape
                $val = preg_replace('/([%"\x00])/', "chr(hexdec($1))", $val);
                
                $this->sBuf .= "$key,\"$val\"";

            }
            $time = $this->getmicrotime() - $this->time_start;
            $this->debug('setData: build FedEx string ('. $time.')');
            return $this->sBuf .= '99,""';
        }

        /**
        * parses FedEx return string into assoc array
        *
        * @return   array FedEx return values
        * @access   public
        */
        function _splitData(){

            // Match all the data elements
            if (!preg_match_all('/(0|[1-9]\d*(?:-\d*)*),"([^"]*)"/', $this->httpBody, $aData)) {
                 $this->setError("Invalid FedEx transaction data at `$this->httpBody'");
                return;
            }

            foreach ($aData[1] as $numKey => $keyVal) {

                $dataVal = $aData[2][$numKey];
                
                // Duplicate Key Something is wrong
                if (isset($this->rHash[$keyVal])) {
                    $this->setError("Duplicate key $keyVal in FedEx transaction");
                    return;
                }
                
                // Apparently FedEx does send back data with null values???
                // There docs say only field 99 can be empty but thats not true.
                
                // Look for empty values in data
                //if (empty($dataVal) and $keyVal != '99') {
                //    $this->setError("Empty value for key $keyVal in FedEx transaction");
                //    return;
                //}

                $this->rHash[$keyVal] = trim($dataVal);
            }
            
            $time = $this->getmicrotime() - $this->time_start;
            $this->debug('_splitData: Parse FedEx response ('. $time.')');            
            if (isset($this->rHash[2])) {
                $this->setError("FedEx Return Error ". $this->rHash[2]." : ".$this->rHash[3]);
                return;
            }
            return $this->rHash;
        }

        /**
        * decode binary label data
        *
        * @param    string $label_file file to save label on disk
        * @return   mixed
        * @access   public
        */
        function label($label_file=false) {
            $this->httpLabel =  $this->rHash[$this->image_key];
            if ($this->httpLabel = preg_replace('/%([0-9][0-9])/e', "chr(hexdec($1))", $this->httpLabel)) {
                    $this->debug('separate binary image data');
                    $this->debug('decoded binary label data');
            }
            if ($label_file) {
                $this->debug('label: trying to write out label to '. $label_file);
                $FH = fopen ($label_file, "w+b");
                 if (!fwrite($FH, $this->httpLabel)) {
                    $this->setError("Can't write to file $label_file");
                    return false;
                 }
                 fclose($FH);
            } else {
                return $this->httpLabel;
            }

        }
        
        /**
        * lookup a value from FedEx response
        *
        * @param    string $code item you are looking for.  Can be either a field name or tag
        * @return   string
        * @access   public
        */
        function lookup($code) {
            $code = $this->fieldNameToTag($code);
            return $this->rHash[$code];
        }

        /**
        * prepares and sends request to FedEx API
        *
        * @param    string $buf pre-formatted FedEx buffer
        * @return   mixed
        * @access   public
        */
        function transaction($buf=false) {
                if ($buf) $this->sBuf = $buf;

                // Future design to allow different types of requests
                switch (REQUEST_TYPE) {
                    case 'CURL':
                        $meth = '_sendCurl';
                        break;
                    case 'HTTP_Request':
                        $meth = '_sendHTTP';
                        break;
                }
                $this->debug('Using request method: '.$meth);               
                if ($this->$meth()) {
                        $this->_splitData();
                        return $this->rHash;
                } else {
                        return false;
                }
        }
        
        /**
        * set HTTP_Request params (proxy_host, proxy_user ...)
        * for more params look at HTTP_Request.php 
        *
        * @return   string
        * @access   private
        */
        function set_request_params($params) {
            $this->request_params = $params;
        }       
        
        /**
        * sends a request to FedEx using cUrl
        *
        * @return   string
        * @access   private
        */
        function _sendCurl() {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->fedex_uri);
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                     'User-Agent: '. $this->NAME .'-'. $this->VERSION . ' class ( http://vermonster.com/ )'
                    ,'Accept: image/gif, image/jpeg, image/pjpeg, text/plain, text/html, */*'
                    ,'Content-Type: image/gif'
                    ,'Content-Length: '. strlen($this->sBuf)
                    ));
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->sBuf );
                $this->debug('Sending to FedEx with data length: '.strlen($this->sBuf));
                $this->httpData = curl_exec($ch);
                if (curl_errno($ch) != 0){
                        $err = "cURL ERROR: ".curl_errno($ch).": ".curl_error($ch)."<br>";
                        $this->setError($err);
                        curl_close($ch);
                        return false;
                }
                curl_close($ch);

                // separate content from HTTP headers
                if(ereg("^(.*)\r?\n\r?\n", $this->httpData)) {
                        $this->debug("found proper headers and document");
                        $this->httpBody = ereg_replace("^[^<]*\r\n\r\n","", $this->httpData);
                        $this->debug("remove headers, body length: ".strlen($this->httpBody));
                } else {
                        $this->debug("headers and body are not properly separated");
                        $this->setError('headers and body are not properly separated');
                        return false;
                }

                if(strlen($this->httpBody) == 0){
                        $this->debug("body contains no data");
                        $this->setError("body contains no data");
                        return false;
                }
                $time = $this->getmicrotime() - $this->time_start;
                $this->debug('Got response from FedEx ('. $time.')');
                return $this->httpBody;
        }
        /**
        * sends a request to FedEx using pear HTTP_Request
        *
        * @return   string
        * @access   private
        */
        function _sendHTTP() {
            require_once "HTTP/Request.php";            
            $params = array_merge(array('timeout' => REQUEST_TIMEOUT), $this->request_params);            
            $req =& new HTTP_Request($this->fedex_uri, $params);
            $req->addHeader('User-Agent', $this->NAME .'-'. $this->VERSION . ' class ( http://www.vermonster.com )');
            $req->setMethod(HTTP_REQUEST_METHOD_POST);
            $req->addRawPostData($this->sBuf);            
            $response = $req->sendRequest();
            if (PEAR::isError($response)) {
                 $this->setError("HTTP_Request Error: ".$response->getMessage());
                 return;
            } else {
                 $this->httpBody = $req->getResponseBody();                
            }
            return $this->httpBody;
            
        }
        
        /* Below are methods for each of FedEx's services
           I thought this would be easier as all the
           functions are the same except for the setData
           and image key value.  Perfect task for PHP5 __call method!
        */

        /**
        * close ground shipments
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function ground_close ($aData) {
                $this->setData('ground_close', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process ground_close');
                    return false;
                }
        }
        
        /**
        * cancel an express shipment
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function express_cancel ($aData) {
                $this->setData('express_cancel', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process cancel_express');
                    return false;
                }
        }

        /**
        * send an express shipment
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function express_ship ($aData) {
                $this->setData('express_ship', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process express_ship');
                    return false;
                }
        }
        
        /**
        * global rate available services
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function express_global_rate ($aData) {
                $this->setData('express_global_rate', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process express_global_rate');
                    return false;
                }
        }
        
        /**
        * FedEx service availability
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function services_avail ($aData) {
                $this->setData('services_avail', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process services_avail');
                    return false;
                }
        }

        /**
        * rate all available services
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function services_rate ($aData) {
                $this->setData('services_rate', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process services_rate');
                    return false;
                }
        }
        
        /**
        * Locate FedEx services
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function fedex_locater ($aData) {
                $this->setData('fedex_locater', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process fedex_locater');
                    return false;
                }
        }

        /**
        * Email an express label
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function express_email ($aData) {
                $this->setData('express_email', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process express_email');
                    return false;
                }
        }
        
        /**
        * Cancel an email express label
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function express_cancel_email ($aData) {
                $this->setData('express_cancel_email', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process express_cancel_email');
                    return false;
                }
        }

        /**
        * Send express tag to dispatch carrier
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function express_tag ($aData) {
                $this->setData('express_tag', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process express_tag');
                    return false;
                }
        }
        
        /**
        * Cancel express tag to dispatch carrier
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function express_cancel_tag ($aData) {
                $this->setData('express_cancel_tag', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process express_cancel_tag');
                    return false;
                }
        }

        /**
        * Check availability of express tag request
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function express_tag_avail ($aData) {
                $this->setData('express_tag_avail', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process express_tag_avail');
                    return false;
                }
        }        
        
        /**
        * send a ground shipment
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function ground_ship ($aData) {
                $this->setData('ground_ship', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process ground_ship');
                    return false;
                }
        }

        /**
        * cancel ground shipments
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function ground_cancel ($aData) {
                $this->setData('ground_cancel', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process ground_cancel');
                    return false;
                }
        }

        /**
        * Subscribe to FedEx API
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function subscribe ($aData) {
                $this->setData('subscribe', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process subscribe');
                    return false;
                }
        }
        
        /**
        * global rate available services
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function ground_global_rate ($aData) {
                $this->setData('ground_global_rate', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process ground_global_rate');
                    return false;
                }
        }

        /**
        * Send ground tag to dispatch carrier
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function ground_tag ($aData) {
                $this->setData('ground_tag', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process ground_tag');
                    return false;
                }
        }
        
        /**
        * Cancel ground tag to dispatch carrier
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function ground_cancel_tag ($aData) {
                $this->setData('ground_cancel_tag', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process ground_cancel_tag');
                    return false;
                }
        }
   
        /**
        * Email a ground label
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function ground_email ($aData) {
                $this->setData('ground_email', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process ground_email');
                    return false;
                }
        }

        /**
        * Cancel an email ground label
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function ground_cancel_email ($aData) {
                $this->setData('ground_cancel_email', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process ground_cancel_email');
                    return false;
                }
        }
                     
        /**
        * Signature Proof of Delivery
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function sig_proof_delivery ($aData) {
                $this->image_key = 1471;
                $this->setData('sig_proof_delivery', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process sig_proof_delivery');
                    return false;
                }
        }
        
        /**
        * Track a shipment by Number, Destination, Ship Date, and Reference
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function track ($aData) {
                $this->setData('track', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process track');
                    return false;
                }
        }

        /**
        * Address Validation.  rank the validation of an address
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function address_validate ($aData) {
                $this->setData('address_validate', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process address_validate');
                    return false;
                }
        }
                
        /**
        * Get a location id.  Transaction 018
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function get_location ($aData) {
                $this->setData('get_location', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process get_location');
                    return false;
                }
        }

        /**
        * Send a version number. Transaction 070
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function send_version ($aData) {
                $this->setData('send_version', $aData);
                if ($aRet = $this->transaction()) {
                    return $aRet;
                } else {
                    $this->setError('unable to process send_version');
                    return false;
                }
        }
        
       /**
        * Old deprecated functions.  This is for reverse compatibility
        * Please use the new function names.
        *
        * @param    $aData array values to send to FedEx
        * @return   string
        * @access   private
        */
        function close_ground ($aData) {
            return $this->ground_close($aData);
        }
        function cancel_express ($aData) {
            return $this->express_cancel($aData);
        }
        function ship_express ($aData) {
            return $this->express_ship($aData);
        }
        function global_rate_express ($aData) {
            return $this->express_global_rate($aData);
        }
        function service_avail ($aData) {
            return $this->services_avail($aData);
        }
        function rate_services ($aData) {
            return $this->services_rate($aData);
        }
        function ship_ground ($aData) {
            return $this->ground_ship($aData);
        }
        function cancel_ground ($aData) {
            return $this->ground_cancel($aData);
        }
        function global_rate_ground ($aData) {
            return $this->ground_global_rate($aData);
        }
        function ref_track ($aData) {
            return $this->track($aData);
        }
        
}
?>
