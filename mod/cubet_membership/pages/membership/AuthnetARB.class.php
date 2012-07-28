<?php
/*************************************************************************************************

This class allows for easy connection to Authorize.Net's Automated Recurring Billing (ARB)
API. More information about the ARB API can be found at http://developer.authorize.net/api/arb/.

PHP version 5

LICENSE: This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see http://www.gnu.org/licenses/.

@category   Ecommerce
@package    AuthnetARB
@author     John Conde <johnny@johnconde.net>
@copyright  2005 - 2010 John Conde
@license    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
@version    2.0

**************************************************************************************************/

class AuthnetARBException extends Exception {}

class AuthnetARB
{
    const USE_PRODUCTION_SERVER  = 0;
    const USE_DEVELOPMENT_SERVER = 1;

    const EXCEPTION_CURL = 10;

    private $login;
    private $transkey;
    private $test;

    private $params  = array();
    private $success = false;
    private $error   = true;

    private $ch;
    private $xml;
    private $response;
    private $resultCode;
    private $code;
    private $text;
    private $subscrId;

    public function __construct($login, $transkey, $test = self::USE_PRODUCTION_SERVER)
    {
        $login    = trim($login);
        $transkey = trim($transkey);
        if (empty($login) || empty($transkey))
        {
            throw new AuthnetARBException('You have not configured your ' . __CLASS__ . '() login credentials properly.');
        }

        $this->login    = trim($login);
        $this->transkey = trim($transkey);
        $this->test     = (bool) $test;

        $subdomain = ($this->test) ? 'apitest' : 'api';
        $this->url = 'https://' . $subdomain . '.authorize.net/xml/v1/request.api';

        $this->params['interval_length']  = 1;
        $this->params['interval_unit']    = 'months';
        $this->params['startDate']        = date("Y-m-d", strtotime("+ 1 month"));
        $this->params['totalOccurrences'] = 9999;
        $this->params['trialOccurrences'] = 0;
        $this->params['trialAmount']      = 0.00;
    }

    public function __destruct()
    {
        if (isset($this->ch))
        {
            curl_close($this->ch);
        }
    }

    public function __toString()
    {
        if (!$this->params)
        {
            return (string) $this;
        }
        $output  = '';
        $output .= '<table summary="Authnet Results" id="authnet">' . "\n";
        $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Outgoing Parameters</b></th>' . "\n" . '</tr>' . "\n";
        foreach ($this->params as $key => $value)
        {
            $output .= "\t" . '<tr>' . "\n\t\t" . '<td><b>' . $key . '</b></td>';
            $output .= '<td>' . $value . '</td>' . "\n" . '</tr>' . "\n";
        }
        $output .= '</table>' . "\n";
        if (!empty($this->xml))
        {
            $output .= 'XML: ';
            $output .= htmlentities($this->xml);
        }
        return $output;
    }

    private function process()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, Array('Content-Type: text/xml'));
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->xml);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        $this->response = curl_exec($this->ch);
        if($this->response)
        {
            $this->parseResults();
            if ($this->resultCode === 'Ok')
            {
                $this->success = true;
                $this->error   = false;
            }
            else
            {
                $this->success = false;
                $this->error   = true;
            }
            curl_close($this->ch);
            unset($this->ch);
            return;
        }
        throw new AuthnetARBException('Connection error: ' . curl_error($this->ch) . ' (' . curl_errno($this->ch) . ')', self::EXCEPTION_CURL);
    }

    public function createAccount($echeck = false)
    {
        $this->xml = "<?xml version='1.0' encoding='utf-8'?>
                      <ARBCreateSubscriptionRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
                          <merchantAuthentication>
                              <name>" . $this->login . "</name>
                              <transactionKey>" . $this->transkey . "</transactionKey>
                          </merchantAuthentication>
                          <refId>" . $this->params['refID'] ."</refId>
                          <subscription>
                              <name>". $this->params['subscrName'] ."</name>
                              <paymentSchedule>
                                  <interval>
                                      <length>". $this->params['interval_length'] ."</length>
                                      <unit>". $this->params['interval_unit'] ."</unit>
                                  </interval>
                                  <startDate>" . $this->params['startDate'] . "</startDate>
                                  <totalOccurrences>". $this->params['totalOccurrences'] . "</totalOccurrences>
                                  <trialOccurrences>". $this->params['trialOccurrences'] . "</trialOccurrences>
                              </paymentSchedule>
                              <amount>". $this->params['amount'] ."</amount>
                              <trialAmount>" . $this->params['trialAmount'] . "</trialAmount>
                              <payment>";
        if ($echeck)
        {
            $this->xml .= "
                                  <bankAccount>
                                      <accountType>". $this->params['accountType'] ."</accountType>
                                      <routingNumber>". $this->params['routingNumber'] ."</routingNumber>
                                      <accountNumber>". $this->params['accountNumber'] ."</accountNumber>
                                      <nameOnAccount>". $this->params['nameOnAccount'] ."</nameOnAccount>
                                      <bankName>". $this->params['bankName'] ."</bankName>
                                  </bankAccount>";
        }
        else
        {
            $this->xml .= "
                                  <creditCard>
                                      <cardNumber>" . $this->params['cardNumber'] . "</cardNumber>
                                      <expirationDate>" . $this->params['expirationDate'] . "</expirationDate>
                                  </creditCard>";
        }

        $this->xml .= "
                              </payment>
                              <order>
                                  <invoiceNumber>" . $this->params['orderInvoiceNumber'] . "</invoiceNumber>
                                  <description>" . $this->params['orderDescription'] . "</description>
                              </order>
                              <customer>
                                  <id>" . $this->params['customerId'] . "</id>
                                  <email>" . $this->params['customerEmail'] . "</email>
                                  <phoneNumber>" . $this->params['customerPhoneNumber'] . "</phoneNumber>
                                  <faxNumber>" . $this->params['customerFaxNumber'] . "</faxNumber>
                              </customer>
                              <billTo>
                                  <firstName>". $this->params['firstName'] . "</firstName>
                                  <lastName>" . $this->params['lastName'] . "</lastName>
                                  <company>" . $this->params['company'] . "</company>
                                  <address>" . $this->params['address'] . "</address>
                                  <city>" . $this->params['city'] . "</city>
                                  <state>" . $this->params['state'] . "</state>
                                  <zip>" . $this->params['zip'] . "</zip>
                              </billTo>
                              <shipTo>
                                  <firstName>". $this->params['shipFirstName'] . "</firstName>
                                  <lastName>" . $this->params['shipLastName'] . "</lastName>
                                  <company>" . $this->params['shipCompany'] . "</company>
                                  <address>" . $this->params['shipAddress'] . "</address>
                                  <city>" . $this->params['shipCity'] . "</city>
                                  <state>" . $this->params['shipState'] . "</state>
                                  <zip>" . $this->params['shipZip'] . "</zip>
                              </shipTo>
                          </subscription>
                      </ARBCreateSubscriptionRequest>";
        $this->process();
    }

    public function updateAccount()
    {
        $this->xml = "<?xml version='1.0' encoding='utf-8'?>
                      <ARBUpdateSubscriptionRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
                          <merchantAuthentication>
                              <name>" . $this->login . "</name>
                              <transactionKey>" . $this->transkey . "</transactionKey>
                          </merchantAuthentication>
                          <refId>" . $this->params['refID'] ."</refId>
                          <subscriptionId>" . $this->params['subscrId'] . "</subscriptionId>
                          <subscription>
                              <name>". $this->params['subscrName'] ."</name>
                              <amount>". $this->params['amount'] ."</amount>
                              <trialAmount>" . $this->params['trialAmount'] . "</trialAmount>
                              <payment>
                                  <creditCard>
                                      <cardNumber>" . $this->params['cardNumber'] . "</cardNumber>
                                      <expirationDate>" . $this->params['expirationDate'] . "</expirationDate>
                                  </creditCard>
                              </payment>
                              <billTo>
                                  <firstName>". $this->params['firstName'] . "</firstName>
                                  <lastName>" . $this->params['lastName'] . "</lastName>
                                  <address>" . $this->params['address'] . "</address>
                                  <city>" . $this->params['city'] . "</city>
                                  <state>" . $this->params['state'] . "</state>
                                  <zip>" . $this->params['zip'] . "</zip>
                              </billTo>
                          </subscription>
                      </ARBUpdateSubscriptionRequest>";
        $this->process();
    }

    public function deleteAccount()
    {
        $this->xml = "<?xml version='1.0' encoding='utf-8'?>
                      <ARBCancelSubscriptionRequest xmlns='AnetApi/xml/v1/schema/AnetApiSchema.xsd'>
                          <merchantAuthentication>
                              <name>" . $this->login . "</name>
                              <transactionKey>" . $this->transkey . "</transactionKey>
                          </merchantAuthentication>
                          <refId>" . $this->params['refID'] ."</refId>
                          <subscriptionId>" . $this->params['subscrId'] . "</subscriptionId>
                      </ARBCancelSubscriptionRequest>";
        $this->process();
    }

    private function parseResults()
    {
        $response = str_replace('xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd"', '', $this->response);
        $xml = new SimpleXMLElement($response);

        $this->resultCode = (string) $xml->messages->resultCode;
        $this->code       = (string) $xml->messages->message->code;
        $this->text       = (string) $xml->messages->message->text;
        $this->subscrId   = (string) $xml->subscriptionId;
    }

    public function setParameter($field = '', $value = null)
    {
        $field = (is_string($field)) ? trim($field) : $field;
        $value = (is_string($value)) ? trim($value) : $value;
        if (!is_string($field))
        {
            throw new AuthnetARBException('setParameter() arg 1 must be a string or integer: ' . gettype($field) . ' given.');
        }
        if (!is_string($value) && !is_numeric($value) && !is_bool($value))
        {
            throw new AuthnetARBException('setParameter() arg 2 must be a string, integer, or boolean value: ' . gettype($value) . ' given.');
        }
        if (empty($field))
        {
            throw new AuthnetARBException('setParameter() requires a parameter field to be named.');
        }
        if ($value === '')
        {
            throw new AuthnetARBException('setParameter() requires a parameter value to be assigned: $field');
        }
        $this->params[$field] = $value;
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function isError()
    {
        return $this->error;
    }

    public function getResponse()
    {
        return strip_tags($this->text);
    }

    public function getResponseCode()
    {
        return $this->code;
    }

    public function getSubscriberID()
    {
        return $this->subscrId;
    }
}

?>