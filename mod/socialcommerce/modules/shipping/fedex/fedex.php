<?php
require_once($CONFIG->path.'mod/'.$CONFIG->pluginname.'/modules/shipping/fedex/xmlparser.php');

class Fedex {
    
    // Variables
    var $server = "https://gatewaybeta.fedex.com/GatewayDC";
    var $accountNumber;
    var $meterNumber;
    var $carrierCode = "FDXG";
    var $dropoffType = "REGULARPICKUP";
    var $service;
    var $serviceName;
    var $packaging = "YOURPACKAGING";
    var $weightUnits = "LBS";
    var $weight;
    // Origin Address
    var $originStateOrProvinceCode;
    var $originPostalCode;
    var $originCountryCode;
    // Destination Address
    var $destStateOrProvinceCode;
    var $destPostalCode;
    var $destCountryCode;
    var $payorType = "SENDER";
    
    
    // Functions    
    function setServer($server) {
        $this->server = $server;
    }

    function setAccountNumber($accountNumber) {
        $this->accountNumber = $accountNumber;
    }

    function setMeterNumber($meterNumber) {
        $this->meterNumber = $meterNumber;
    }

    function setCarrierCode($carrierCode) {
        $this->carrierCode = $carrierCode;
    }
    
    function setDropoffType($dropoffType) {
        $this->dropoffType = $dropoffType;
    }

    function setService($service, $name) {
        $this->service = $service;
        $this->serviceName = $name;
    }

    function setPackaging($packaging) {
        $this->packaging = $packaging;
    }
    
    function setWeightUnits($units) {
        $this->weightUnits = $units;
    }
    
    function setWeight($weight) {
        $this->weight = $weight;
    }
    
    function setOriginStateOrProvinceCode($code) {
        $this->originStateOrProvinceCode = $code;
    }
    
    function setOriginPostalCode($code) {
        $this->originPostalCode = $code;
    }
    
    function setOriginCountryCode($code) {
        $this->originCountryCode = $code;
    }
    
    function setDestStateOrProvinceCode($code) {
        $this->destStateOrProvinceCode = $code;
    }
    
    function setDestPostalCode($code) {
        $this->destPostalCode = $code;
    }
    
    function setDestCountryCode($code) {
        $this->destCountryCode = $code;
    }
    
    function setPayorType($type) {
        $this->payorType = $type;
    }
    
    function getPrice() {
        
        $str = '<?xml version="1.0" encoding="UTF-8" ?>';
        $str .= '    <FDXRateRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="FDXRateRequest.xsd">';
        $str .= '        <RequestHeader>';
        $str .= '            <CustomerTransactionIdentifier>Express Rate</CustomerTransactionIdentifier>';
        $str .= '            <AccountNumber>'.$this->accountNumber.'</AccountNumber>';
        $str .= '            <MeterNumber>'.$this->meterNumber.'</MeterNumber>';
        $str .= '            <CarrierCode>'.$this->carrierCode.'</CarrierCode>';
        $str .= '        </RequestHeader>';
        $str .= '        <DropoffType>'.$this->dropoffType.'</DropoffType>';
        $str .= '        <Service>'.$this->service.'</Service>';
        $str .= '        <Packaging>'.$this->packaging.'</Packaging>';
        $str .= '        <WeightUnits>'.$this->weightUnits.'</WeightUnits>';
        $str .= '        <Weight>'.number_format($this->weight, 1, '.', '').'</Weight>';
        $str .= '        <OriginAddress>';
        $str .= '            <StateOrProvinceCode>'.$this->originStateOrProvinceCode.'</StateOrProvinceCode>';
        $str .= '            <PostalCode>'.$this->originPostalCode.'</PostalCode>';
        $str .= '            <CountryCode>'.$this->originCountryCode.'</CountryCode>';
        $str .= '        </OriginAddress>';
        $str .= '        <DestinationAddress>';
        $str .= '            <StateOrProvinceCode>'.$this->destStateOrProvinceCode.'</StateOrProvinceCode>';
        $str .= '            <PostalCode>'.$this->destPostalCode.'</PostalCode>';
        $str .= '            <CountryCode>'.$this->destCountryCode.'</CountryCode>';
        $str .= '        </DestinationAddress>';
        $str .= '        <Payment>';
        $str .= '            <PayorType>'.$this->payorType.'</PayorType>';
        $str .= '        </Payment>';
        $str .= '        <PackageCount>'.ceil(bcdiv(number_format($this->weight, 1, '.', ''), '150', 3)).'</PackageCount>';
        $str .= '    </FDXRateRequest>';
        //print($str);
        $header[] = "Host: www.smart-shop.com";
        $header[] = "MIME-Version: 1.0";
        $header[] = "Content-type: multipart/mixed; boundary=----doc";
        $header[] = "Accept: text/xml";
        $header[] = "Content-length: ".strlen($str);
        $header[] = "Cache-Control: no-cache";
        $header[] = "Connection: close \r\n";
        $header[] = $str;

        $ch = curl_init();
        //Disable certificate check.
        // uncomment the next line if you get curl error 60: error setting certificate verify locations
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // uncommenting the next line is most likely not necessary in case of error 60
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //-------------------------
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //curl_setopt($ch, CURLOPT_CAINFO, "c:/ca-bundle.crt");
        //-------------------------
        curl_setopt($ch, CURLOPT_URL,$this->server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            
        $data = curl_exec($ch);        
        if (curl_errno($ch)) {
            $this->getPrice();
        } else {
            // close curl resource, and free up system resources
            curl_close($ch);
            $xmlParser = new xmlparser();
            $array = $xmlParser->GetXMLTree($data);
            //$xmlParser->printa($array);
            if(count($array['FDXRATEREPLY'][0]['ERROR'])) { // If it is error
                $error = new fedexError();
                $error->number = $array['FDXRATEREPLY'][0]['ERROR'][0]['CODE'][0]['VALUE'];
                $error->description = $array['FDXRATEREPLY'][0]['ERROR'][0]['MESSAGE'][0]['VALUE'];
                $error->response = $array;
                $this->error = $error;
            } else if (count($array['FDXRATEREPLY'][0]['ESTIMATEDCHARGES'][0]['DISCOUNTEDCHARGES'][0]['NETCHARGE'])) {
                $price = new fedexPrice();
                $price->rate = $array['FDXRATEREPLY'][0]['ESTIMATEDCHARGES'][0]['DISCOUNTEDCHARGES'][0]['NETCHARGE'][0]['VALUE'];
                $price->service = $this->serviceName;
                $price->response = $array;
                $this->price = $price;            
            }
            return $this;
        }
    }
}
class fedexError
{
    var $number;
    var $description;
    var $response;
}
class fedexPrice
{
    var $service;
    var $rate;
    var $response;
}
?> 
