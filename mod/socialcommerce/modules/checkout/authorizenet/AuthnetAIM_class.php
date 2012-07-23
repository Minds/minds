<?php
/*************************************************************************************************

This class allows for easy connection to Authorize.Net's Advanced Integration Method (AIM)
API (version 3.1). More information about the AIM API can be found at http://developer.authorize.net/api/aim/.

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
@package    AuthnetAIM
@author     John Conde <johnny@johnconde.net>
@copyright  2005 - 2010 John Conde
@license    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
@version    1.5
@link       http://www.johnconde.net/blog/tutorial-integrating-the-authorizenet-aim-api-with-php/

**************************************************************************************************/

class AuthnetAIMException extends Exception {}

class AuthnetAIM
{
    const EXCEPTION_CURL              = 1;
    const EXCEPTION_CREDENTIALS       = 20;
    const EXCEPTION_RETRIES           = 21;
    const EXCEPTION_CREDITCARD        = 22;
    const EXCEPTION_EXPIRATION        = 23;
    const EXCEPTION_AMOUNT            = 24;
    const EXCEPTION_PARAM_FIELD       = 25;
    const EXCEPTION_PARAM_VALUE       = 26;
    const EXCEPTION_TRANS_TYPE        = 27;
    const EXCEPTION_ECHECK_AMOUNT     = 40;
    const EXCEPTION_ECHECK_ROUTING    = 41;
    const EXCEPTION_ECHECK_ACCOUNT    = 42;
    const EXCEPTION_ECHECK_BANK_NAME  = 43;
    const EXCEPTION_ECHECK_ACCT_NAME  = 44;
    const EXCEPTION_ECHECK_ACCT_TYPE  = 45;
    const EXCEPTION_ECHECK_TYPE       = 46;
    const EXCEPTION_ECHECK_TRANS_TYPE = 47;

    private $test;
    private $params   = array();
    private $results  = array();
    private $approved;
    private $declined;
    private $error;
    private $ch;
    private $response;
    private $url;

    public function __construct($login = '', $transkey = '', $test = false)
    {
        $login    = trim($login);
        $transkey = trim($transkey);
        if (empty($login) || empty($transkey))
        {
            throw new AuthnetAIMException('You have not configured your ' . __CLASS__ . '() login credentials properly', self::EXCEPTION_CREDENTIALS);
        }

        $this->test = (bool) $test;
        $subdomain  = ($this->test) ? 'test' : 'secure';
        $this->url  = 'https://' . $subdomain . '.authorize.net/gateway/transact.dll';

        $this->setParameter('x_delim_data', 'TRUE');
        $this->setParameter('x_delim_char', '|');
        $this->setParameter('x_relay_response', 'FALSE');
        $this->setParameter('x_url', 'FALSE');
        $this->setParameter('x_version', '3.1');
        $this->setParameter('x_method', 'CC');
        $this->setParameter('x_type', 'AUTH_CAPTURE');
        $this->setParameter('x_login', $login);
        $this->setParameter('x_tran_key', $transkey);

        $this->approved = false;
        $this->declined = false;
        $this->error    = true;
    }

    public function __destruct()
    {
        if ($this->ch)
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

        if ($this->results)
        {
            $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Incomming Parameters</b></th>' . "\n" . '</tr>' . "\n";

            $response = array('Response Code', 'Response Subcode', 'Response Reason Code',
                              'Response Reason Text', 'Approval Code', 'AVS Result Code',
                              'Transaction ID', 'Invoice Number', 'Description', 'Amount',
                              'Method', 'Transaction Type', 'Customer ID', 'Cardholder First Name',
                              'Cardholder Last Name', 'Company', 'Billing Address', 'City',
                              'State', 'Zip', 'Country', 'Phone', 'Fax', 'Email', 'Ship to First Name',
                              'Ship to Last Name', 'Ship to Company', 'Ship to Address',
                              'Ship to City', 'Ship to State', 'Ship to Zip', 'Ship to Country',
                              'Tax Amount', 'Duty Amount', 'Freight Amount', 'Tax Exempt Flag',
                              'PO Number', 'MD5 Hash', 'Card Code (CVV2/CVC2/CID) Response Code',
                              'Cardholder Authentication Verification Value (CAVV) Response Code');

            foreach ($this->results as $key => $value)
            {
                if ($key > 40) break;
                $output .= "\t" . '<tr>' . "\n\t\t" . '<td><b>' . $response[$key] . '</b></td>';
                $output .= '<td>' . $value . '</td>' . "\n" . '</tr>' . "\n";
            }
        }

        $output .= '</table>' . "\n";
        return $output;
    }

    public function __get($parameter)
    {
        return $this->params[$parameter];
    }

    public function __set($parameter, $value)
    {
        $this->setParameter($parameter, $value);
    }

    final public function process($retries = 3)
    {
        if (!is_int($retries))
        {
            throw new AuthnetAIMException(__METHOD__ . '() arg 1 "retries" must be a whole number value: ' . $retries . ' given.', self::EXCEPTION_RETRIES);
        }

        $count = 0;
        while ($count < $retries)
        {
            $this->ch = curl_init($this->url);
            curl_setopt($this->ch, CURLOPT_HEADER, 0);
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
            curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
            if($this->response = curl_exec($this->ch))
            {
                $this->parseResults();
                if ($this->getResultResponseFull() == 'Approved')
                {
                    $this->approved = true;
                    $this->declined = false;
                    $this->error    = false;
                }
                else if ($this->getResultResponseFull() == 'Declined')
                {
                    $this->approved = false;
                    $this->declined = true;
                    $this->error    = false;
                }
                curl_close($this->ch);
                unset($this->ch);
                return;
            }
            $count++;
        }
        throw new AuthnetAIMException('Connection error: ' . curl_error($this->ch) . ' (' . curl_errno($this->ch) . ')', self::EXCEPTION_CURL);
    }

    final private function parseResults()
    {
        $this->results = explode($this->params['x_delim_char'], $this->response);
    }

    final public function setTransaction($cardnum, $expiration, $amount, $cvv = null, $invoice = null, $tax = null)
    {
        $this->params['x_card_num']    = (string) trim($cardnum);
        $this->params['x_exp_date']    = (string) trim($expiration);
        $this->params['x_amount']      = (float)  $amount;
        $this->params['x_invoice_num'] = (string) $invoice;
        $this->params['x_tax']         = (float)  $tax;
        $this->params['x_card_code']   = str_pad((int) $cvv, 3, "0", STR_PAD_LEFT);
        if (empty($this->params['x_card_num']))
        {
            throw new AuthnetAIMException('Required information for transaction processing omitted: credit card number', self::EXCEPTION_CREDITCARD);
        }
        if (empty($this->params['x_exp_date']))
        {
            throw new AuthnetAIMException('Required information for transaction processing omitted: expiration date', self::EXCEPTION_EXPIRATION);
        }
        if (empty($this->params['x_amount']))
        {
            throw new AuthnetAIMException('Required information for transaction processing omitted: dollar amount', self::EXCEPTION_AMOUNT);
        }
        if (!$this->validateExpirationDate())
        {
            throw new AuthnetAIMException('Expiration date is in an invalid format', self::EXCEPTION_EXPIRATION);
        }
    }

    final public function setParameter($field = '', $value = null)
    {
        $field = (is_string($field)) ? trim($field) : $field;
        $value = (is_string($value)) ? trim($value) : $value;
        if (!is_string($field))
        {
            throw new AuthnetAIMException(__METHOD__ . '() arg 1 must be a string: ' . gettype($field) . ' given.', self::EXCEPTION_PARAM_FIELD);
        }
        if (!is_string($value) && !is_numeric($value) && !is_bool($value))
        {
            throw new AuthnetAIMException(__METHOD__ . '() arg 2 (' . $field . ') must be a string, integer, or boolean value: ' . gettype($value) . ' given.', self::EXCEPTION_PARAM_VALUE);
        }
        if (empty($field))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a parameter field to be named.', self::EXCEPTION_PARAM_FIELD);
        }
        if ($value === '' || is_null($value))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a parameter value to be assigned: "' . $field . '" given.', self::EXCEPTION_PARAM_VALUE);
        }
        $this->params[$field] = $value;
    }

    final public function setTransactionType($type = '')
    {
        $type      = strtoupper(trim($type));
        $typeArray = array('AUTH_CAPTURE', 'AUTH_ONLY', 'PRIOR_AUTH_CAPTURE', 'CREDIT', 'CAPTURE_ONLY', 'VOID');
        if (!in_array($type, $typeArray))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a valid value to be assigned.', self::EXCEPTION_TRANS_TYPE);
        }
        $this->params['x_type'] = $type;
    }

    final public function setEcheck($aba, $account, $accttype, $bankname, $bankacctname, $echecktype, $transtype = 'AUTH_CAPTURE')
    {
        $amount       = (float) $amount;
        $aba          = trim($aba);
        $account      = trim($account);
        $accttype     = strtoupper(trim($accttype));
        $bankname     = substr(strtoupper(trim($bankname)), 0, 50);
        $bankacctname = substr(strtoupper(trim($bankacctname)), 0, 22);
        $echecktype   = strtoupper(trim($echecktype));
        $transtype    = strtoupper(trim($transtype));

        if (!preg_match('/^\d{1,4}(\.?\d{0,2})?$/', $amount))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a valid dollar amount.', self::EXCEPTION_ECHECK_AMOUNT);
        }
        if (!preg_match('/^\d{9}$/', $aba))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a nine digit ABA/routing number.', self::EXCEPTION_ECHECK_ROUTING);
        }
        if (!preg_match('/^\d{6,20}$/', $account))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a valid account number.', self::EXCEPTION_ECHECK_ACCOUNT);
        }
        if (empty($bankname))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a valid bank name.', self::EXCEPTION_ECHECK_BANK_NAME);
        }
        else
        {
            $bankname = substr($bankname, 0, 50);
        }
        if (empty($bankacctname))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires the name that appears on the checking account.', self::EXCEPTION_ECHECK_ACCT_NAME);
        }

        $accountTypeArray = array('CHECKING', 'BUSINESSCHECKING', 'SAVINGS');
        if (!in_array($accttype, $accountTypeArray))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a valid bank account type to be assigned.', self::EXCEPTION_ECHECK_ACCT_TYPE);
        }

        $echeckTypeArray = array('CCD', 'PPD', 'TEL', 'WEB');
        if (!in_array($echecktype, $echeckTypeArray))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a valid echeck type value to be assigned.', self::EXCEPTION_ECHECK_TYPE);
        }

        $transTypeArray = array('AUTH_CAPTURE', 'CREDIT');
        if (!in_array($transtype, $transTypeArray))
        {
            throw new AuthnetAIMException(__METHOD__ . '() requires a valid transaction type.', self::EXCEPTION_ECHECK_TRANS_TYPE);
        }

        $this->params['x_amount']         = $amount;
        $this->params['x_method']         = 'ECHECK';
        $this->params['x_bank_aba_code']  = $aba;
        $this->params['x_bank_acct_num']  = $account;
        $this->params['x_bank_acct_type'] = $accttype;
        $this->params['x_bank_name']      = $bankname;
        $this->params['x_bank_acct_name'] = $bankacctname;
        $this->params['x_type']           = $transtype;
        $this->params['x_echeck_type']    = $echecktype;
    }

    final private function validateExpirationDate()
    {
        if (preg_match('|^\d{4}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{2}/\\d{2}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\\d{2}-\d{2}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{6}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{2}/\d{4}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{2}-\d{4}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{4}-\d{2}-\d{2}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{4}/\d{2}/\d{2}$|', $this->params['x_exp_date'])) return true;
        return false;
    }

    final public function getResultResponse()
    {
        return $this->results[0];
    }

    final public function getResultResponseFull()
    {
        $response = array('', 'Approved', 'Declined', 'Error');
        return $response[$this->results[0]];
    }

    final public function isApproved()
    {
        return $this->approved;
    }

    final public function isDeclined()
    {
        return $this->declined;
    }

    final public function isError()
    {
        return $this->error;
    }

    final public function isConfigError()
    {
        $reasons = array(5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 28, 29, 30, 31, 33, 34, 35, 36,
                        37, 38, 39, 40, 42, 43, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 64, 66, 67, 68,
                        69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 91, 92, 93, 94, 95, 96,
                        97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114,
                        115, 116, 117, 118, 119, 120, 121, 122, 123, 170, 171, 172, 173, 174, 175, 176, 177,
                        178, 179, 180, 181, 182, 183, 184, 185, 243, 244, 245, 246, 247, 261, 270, 271);
        return in_array($this->getResponseCode(), $reasons);
    }

    final public function isTempError()
    {
        $reasons = array(19, 20, 21, 22, 23, 24, 25, 26, 57, 58, 59, 60, 61, 62, 63);
        return in_array($this->getResponseCode(), $reasons);
    }

    final public function getResponseMessage()
    {
        $messages = array();
        $messages[4] = "The code returned from the processor indicating that the card used needs to be picked up.";
        $messages[5] = "The value submitted in the amount field did not pass validation for a number.";
        $messages[7] = "The format of the date submitted was incorrect.";
        $messages[9] = "The value submitted in the x_bank_aba_code field did not pass validation or was not for a valid financial institution.";
        $messages[10] = "The value submitted in the x_bank_acct_num field did not pass validation.";
        $messages[11] = "A transaction with identical amount and credit card information was submitted two minutes prior.";
        $messages[12] = "A transaction that required x_auth_code to be present was submitted without a value.";
        $messages[14] = "The Relay Response or Referrer URL does not match the merchants configured value(s) or is absent. Applicable only to SIM and WebLink APIs.";
        $messages[15] = "The transaction ID value is non-numeric or was not present for a transaction that requires it (i.e., VOID, PRIOR_AUTH_CAPTURE, and CREDIT).";
        $messages[16] = "The transaction ID sent in was properly formatted but the gateway had no record of the transaction.";
        $messages[17] = "The merchant was not configured to accept the credit card submitted in the transaction.";
        $messages[18] = "The merchant does not accept electronic checks.";
        $messages[28] = "The Merchant ID at the processor was not configured to accept this card type.";
        $messages[31] = "The FDC Merchant ID or Terminal ID is incorrect. Call Merchant Service Provider. (The merchant was incorrectly set up at the processor.";
        $messages[33] = "The word FIELD will be replaced by an actual field name. This error indicates that a field the merchant specified as required was not filled in. ";
        $messages[34] = "The merchant was incorrectly set up at the processor.";
        $messages[35] = "The merchant was incorrectly set up at the processor.";
        $messages[38] = "The merchant was incorrectly set up at the processor.";
        $messages[41] = "Only merchants set up for the FraudScreen.Net service would receive this decline. This code is returned if a given transaction's fraud score is higher than the threshold set by the merchant.";
        $messages[43] = "The merchant was incorrectly set up at the processor.";
        $messages[44] = "The card code submitted with the transaction did not match the card code on file at the card issuing bank and the transaction was declined";
        $messages[45] = "This error would be returned if the transaction received a code from the processor that matched the rejection criteria set by the merchant for both the AVS and Card Code filters.";
        $messages[47] = "This occurs if the merchant tries to capture funds greater than the amount of the original authorization-only transaction.";
        $messages[48] = "The merchant attempted to settle for less than the originally authorized amount.";
        $messages[49] = "The transaction amount submitted was greater than the maximum amount allowed.";
        $messages[50] = "Credits or refunds may only be performed against settled transactions. The transaction against which the credit/refund was submitted has not been settled, so a credit cannot be issued.";
        $messages[53] = "If x_method = ECHECK, x_type cannot be set to CAPTURE_ONLY.";
        $messages[55] = "The  transaction is rejected if the sum of this credit and prior credits exceeds the original debit amount";
        $messages[56] = "The merchant processes eCheck.Net transactions only and does not accept credit cards.";
        $messages[65] = "The transaction was declined because the merchant configured their account through the Merchant Interface to reject transactions with certain values for a Card Code mismatch.";
        $messages[66] = "The transaction did not meet gateway security guidelines.";
        $messages[68] = "The value submitted in x_version was invalid.";
        $messages[69] = "The value submitted in x_type was invalid.";
        $messages[70] = "The value submitted in x_method was invalid.";
        $messages[71] = "The value submitted in x_bank_acct_type was invalid.";
        $messages[72] = "The value submitted in x_auth_code was more than six characters in length.";
        $messages[73] = "The format of the value submitted in x_drivers_license_dob was invalid.";
        $messages[74] = "The value submitted in x_duty failed format validation.";
        $messages[75] = "The value submitted in x_freight failed format validation.";
        $messages[76] = "The value submitted in x_tax failed format validation.";
        $messages[77] = "The value submitted in x_customer_tax_id failed validation.";
        $messages[78] = "The value submitted in x_card_code failed format validation.";
        $messages[79] = "The value submitted in x_drivers_license_num failed format validation.";
        $messages[80] = "The value submitted in x_drivers_license_state failed format validation.";
        $messages[81] = "The merchant requested an integration method not compatible with the AIM API.";
        $messages[82] = "The system no longer supports version 2.5; requests cannot be posted to scripts.";
        $messages[83] = "The system no longer supports version 2.5; requests cannot be posted to scripts.";
        $messages[97] = "Applicable only to SIM API. Fingerprints are only valid for a short period of time. If the fingerprint is more than one hour old or more than 15 minutes into the future, it will be rejected. This code indicates that the transaction fingerprint has expired.";
        $messages[98] = "Applicable only to SIM API. The transaction fingerprint has already been used.";
        $messages[99] = "Applicable only to SIM API. The server-generated fingerprint does not match the merchant-specified fingerprint in the x_fp_hash field.";
        $messages[100] = "Applicable only to eCheck.Net. The value specified in the x_echeck_type field is invalid.";
        $messages[101] = "Applicable only to eCheck.Net. The specified name on the account and/or the account type do not match the NOC record for this account.";
        $messages[102] = "A password or Transaction Key was submitted with this WebLink request. This is a high security risk.";
        $messages[103] = "A valid fingerprint, Transaction Key, or password is required for this transaction.";
        $messages[104] = "TApplicable only to eCheck.Net. The value submitted for country failed validation.";
        $messages[105] = "TApplicable only to eCheck.Net. The values submitted for city and country failed validation.";
        $messages[106] = "TApplicable only to eCheck.Net. The value submitted for company failed validation.";
        $messages[107] = "TApplicable only to eCheck.Net. The value submitted for bank account name failed validation.";
        $messages[108] = "TApplicable only to eCheck.Net. The values submitted for first name and last name failed validation.";
        $messages[109] = "TApplicable only to eCheck.Net. The values submitted for first name and last name failed validation.";
        $messages[110] = "TApplicable only to eCheck.Net. The value submitted for bank account name does not contain valid characters.";
        $messages[116] = "This error is only applicable to Verified by Visa and MasterCard SecureCode transactions. The ECI value for a Visa transaction; or the UCAF indicator for a MasterCard transaction submitted in the x_authentication_indicator field is invalid.";
        $messages[117] = "This error is only applicable to Verified by Visa and MasterCard SecureCode transactions. The CAVV for a Visa transaction; or the AVV/UCAF for a MasterCard transaction is invalid.";
        $messages[118] = "This error is only applicable to Verified by Visa and MasterCard SecureCode transactions. The combination of authentication indicator and cardholder authentication value for a Visa or MasterCard transaction is invalid..";
        $messages[119] = "This error is only applicable to Verified by Visa and MasterCard SecureCode transactions. Transactions submitted with a value in x_authentication_indicator and x_recurring_billing=YES will be rejected.";
        $messages[120] = "The original transaction timed out while waiting for a response from the authorizer.)";
        $messages[121] = "The original transaction experienced a database error.";
        $messages[122] = "The original transaction experienced a processing error.";
        $messages[123] = "The transaction request must include the API Login ID associated with the payment gateway account.";
        $messages[127] = "The system-generated void for the original AVS-rejected transaction failed.";
        $messages[128] = "The customers financial institution does not currently allow transactions for this account.";
        $messages[130] = "IFT: The payment gateway account status is Blacklisted.";
        $messages[131] = "IFT: The payment gateway account status is Suspended-STA.";
        $messages[132] = "IFT: The payment gateway account status is Suspended-Blacklist.";
        $messages[141] = "The system-generated void for the original FraudScreen-rejected transaction failed.";
        $messages[145] = "The system-generated void for the original card code-rejected and AVS-rejected transaction failed.";
        $messages[152] = "The transaction was authorized, but the client could not be notified; the transaction will not be settled. (The system-generated void for the original transaction failed. The response for the original transaction could not be communicated to the client.";
        $messages[165] = "The system-generated void for the original card code-rejected transaction failed.";
        $messages[170] = "Concord EFS Provisioning at the processor has not been completed.";
        $messages[171] = "Concord EFS This request is invalid.";
        $messages[172] = "Concord EFS The store ID is invalid.";
        $messages[173] = "Concord EFS The store key is invalid.";
        $messages[174] = "Concord EFS This transaction type is not accepted by the processor.";
        $messages[175] = "Concord EFS This transaction is not allowed. The Concord EFS processing platform does not support voiding credit transactions. Please debit the credit card instead of voiding the credit.";
        $messages[180] = "The processor response format is invalid.";
        $messages[181] = "The system-generated void for the original invalid transaction failed. (The original transaction included an invalid processor response format.)";
        $messages[193] = "The transaction is currently under review. (The transaction was placed under review by the risk management system.";
        $messages[200] = "This error code applies only to merchants on FDC Omaha. The credit card number is invalid.";
        $messages[201] = "This error code applies only to merchants on FDC Omaha. The expiration date is invalid.";
        $messages[202] = "This error code applies only to merchants on FDC Omaha. The transaction type is invalid.";
        $messages[203] = "This error code applies only to merchants on FDC Omaha. The value submitted in the amount field is invalid.";
        $messages[204] = "This error code applies only to merchants on FDC Omaha. The department code is invalid.";
        $messages[205] = "This error code applies only to merchants on FDC Omaha. The value submitted in the merchant number field is invalid.";
        $messages[206] = "This error code applies only to merchants on FDC Omaha. The merchant is not on file.";
        $messages[207] = "This error code applies only to merchants on FDC Omaha. The merchant account is closed.";
        $messages[208] = "This error code applies only to merchants on FDC Omaha. The merchant is not on file.";
        $messages[209] = "This error code applies only to merchants on FDC Omaha. Communication with the processor could not be established.";
        $messages[210] = "This error code applies only to merchants on FDC Omaha. The merchant type is incorrect.";
        $messages[211] = "This error code applies only to merchants on FDC Omaha. The cardholder is not on file.";
        $messages[212] = "This error code applies only to merchants on FDC Omaha. The bank configuration is not on file";
        $messages[213] = "This error code applies only to merchants on FDC Omaha. The merchant assessment code is incorrect.";
        $messages[214] = "This error code applies only to merchants on FDC Omaha. This function is currently unavailable.";
        $messages[215] = "This error code applies only to merchants on FDC Omaha. The encrypted PIN field format is invalid.";
        $messages[216] = "This error code applies only to merchants on FDC Omaha. The ATM term ID is invalid.";
        $messages[217] = "This error code applies only to merchants on FDC Omaha. This transaction experienced a general message format problem.";
        $messages[218] = "This error code applies only to merchants on FDC Omaha. The PIN block format or PIN availability value is invalid.";
        $messages[219] = "This error code applies only to merchants on FDC Omaha. The ETC void is unmatched.";
        $messages[220] = "This error code applies only to merchants on FDC Omaha. The primary CPU is not available.";
        $messages[221] = "This error code applies only to merchants on FDC Omaha. The SE number is invalid.";
        $messages[222] = "This error code applies only to merchants on FDC Omaha. Duplicate auth request (from INAS).";
        $messages[223] = "This error code applies only to merchants on FDC Omaha. This transaction experienced an unspecified error.";
        $messages[224] = "This error code applies only to merchants on FDC Omaha. Please re-enter the transaction.";
        $messages[243] = "The combination of values submitted for x_recurring_billing and x_echeck_type is not allowed.";
        $messages[244] = "The combination of values submitted for x_bank_acct_type and x_echeck_type is not allowed.";
        $messages[245] = "The value submitted for x_echeck_type is not allowed when using the payment gateway hosted payment form.";
        $messages[246] = "The merchants payment gateway account is not enabled to submit the eCheck.Net type.";
        $messages[247] = "The combination of values submitted for x_type and x_echeck_type is not allowed.";
        $messages[248] = "Invalid check number. Check number can only consist of letters and numbers and not more than 15 characters.";
        $messages[250] = "This transaction was submitted from a blocked IP address.";
        $messages[251] = "The transaction was declined as a result of triggering a Fraud Detection Suite filter.";
        $messages[252] = "The transaction was accepted, but is being held for merchant review. The merchant may customize the customer response in the Merchant Interface.";
        $messages[253] = "The transaction was accepted and was authorized, but is being held for merchant review. The merchant may customize the customer response in the Merchant Interface.";
        $messages[254] = "The transaction was declined after manual review.";
        $messages[261] = "The transaction experienced an error during sensitive data encryption and was not processed. Please try again.";
        $messages[270] = "A value submitted in x_line_item for the item referenced is invalid.";
        $messages[271] = "The number of line items submitted exceeds the allowed maximum of 30.";
        $messages[288] = "The merchant has not indicated participation in any Cardholder Authentication Programs in the Merchant Interface. ";
        $messages[300] = "Invalid x_device_id value";
        $messages[301] = "Invalid x_device_batch_id value";
        $messages[302] = "Invalid x_reversal value";
        $messages[303] = "The current device batch must be closed manually from the POS device.";
        $messages[304] = "The original transaction has been settled and cannot be reversed.";
        $messages[305] = "This merchant is configured for auto-close and cannot manually close batches. ";
        $messages[306] = "The batch is already closed.";
        $messages[307] = "The reversal was processed successfully.";
        $messages[308] = "The transaction submitted for reversal was not found.";
        $messages[309] = "The device has been disabled.";
        $messages[310] = "This transaction has already been voided.";
        $messages[311] = "This transaction has already been captured.";
        $messages[315] = "This is a processor-issued decline.";
        $messages[316] = "This is a processor-issued decline.";
        $messages[317] = "This is a processor-issued decline.";
        $messages[318] = "This is a processor-issued decline.";
        $messages[319] = "This is a processor-issued decline.";

        $additional_info = (array_key_exists($this->getResponseCode(), $messages)) ? ' (' . $messages[$this->getResponseCode()] . ')' : '';
        return $this->getResponseText() . $additional_info;
    }

    final public function getResponseSubcode()
    {
        return $this->results[1];
    }

    final public function getResponseCode()
    {
        return $this->results[2];
    }

    final public function getResponseText()
    {
        return $this->results[3];
    }

    final public function getAuthCode()
    {
        return $this->results[4];
    }

    final public function getAVSResponse()
    {
        return $this->results[5];
    }

    final public function getTransactionID()
    {
        return $this->results[6];
    }

    final public function getInvoiceNumber()
    {
        return $this->results[7];
    }

    final public function getDescription()
    {
        return $this->results[8];
    }

    final public function getAmount()
    {
        return $this->results[9];
    }

    final public function getPaymentMethod()
    {
        return $this->results[10];
    }

    final public function getTransactionType()
    {
        return $this->results[11];
    }

    final public function getCustomerID()
    {
        return $this->results[12];
    }

    final public function getCHFirstName()
    {
        return $this->results[13];
    }

    final public function getCHLastName()
    {
        return $this->results[14];
    }

    final public function getCompany()
    {
        return $this->results[15];
    }

    final public function getBillingAddress()
    {
        return $this->results[16];
    }

    final public function getBillingCity()
    {
        return $this->results[17];
    }

    final public function getBillingState()
    {
        return $this->results[18];
    }

    final public function getBillingZip()
    {
        return $this->results[19];
    }

    final public function getBillingCountry()
    {
        return $this->results[20];
    }

    final public function getPhone()
    {
        return $this->results[21];
    }

    final public function getFax()
    {
        return $this->results[22];
    }

    final public function getEmail()
    {
        return $this->results[23];
    }

    final public function getShippingFirstName()
    {
        return $this->results[24];
    }

    final public function getShippingLastName()
    {
        return $this->results[25];
    }

    final public function getShippingCompany()
    {
        return $this->results[26];
    }

    final public function getShippingAddress()
    {
        return $this->results[27];
    }

    final public function getShippingCity()
    {
        return $this->results[28];
    }

    final public function getShippingState()
    {
        return $this->results[29];
    }

    final public function getShippingZip()
    {
        return $this->results[30];
    }

    final public function getShippingCountry()
    {
        return $this->results[31];
    }

    final public function getTaxAmount()
    {
        return $this->results[32];
    }

    final public function getDutyAmount()
    {
        return $this->results[33];
    }

    final public function getFreightAmount()
    {
        return $this->results[34];
    }

    final public function getTaxExemptFlag()
    {
        return $this->results[35];
    }

    final public function getPONumber()
    {
        return $this->results[36];
    }

    final public function getMD5Hash()
    {
        return $this->results[37];
    }

    final public function getCVVResponse()
    {
        return $this->results[38];
    }

    final public function getCAVVResponse()
    {
        return $this->results[39];
    }
}

?>