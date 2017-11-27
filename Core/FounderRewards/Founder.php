<?php
/**
 * Created by PhpStorm.
 * User: Marcelo
 * Date: 21/06/2017
 * Time: 12:11 PM
 */

namespace Minds\Core\FounderRewards;


class Founder
{
    public $uuid = '';
    public $name = '';
    public $amount = 0;
    public $sentRewards = false;
    public $investmentState = '';
    public $rowNumber = -1;
    public $email = '';
    public $postalAddress = '';
    public $tshirtSize = '';
    public $address = '';
    public $guid = '';
    public $claimed = false;

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function toRow()
    {
        return [
            \Google_Model::NULL_VALUE, // Investor
            \Google_Model::NULL_VALUE, // Email
            \Google_Model::NULL_VALUE, // Investment ID
            \Google_Model::NULL_VALUE, // Total
            \Google_Model::NULL_VALUE, // 19-Jun-17
            \Google_Model::NULL_VALUE, // 13-Jul-17
            \Google_Model::NULL_VALUE, // 22-Sep-17
            \Google_Model::NULL_VALUE, // 11-Aug-17
            \Google_Model::NULL_VALUE, // 6-Sep-17
            \Google_Model::NULL_VALUE, // 29-Sep-17
            \Google_Model::NULL_VALUE, // K
            \Google_Model::NULL_VALUE, // Address
            \Google_Model::NULL_VALUE, // City
            \Google_Model::NULL_VALUE, // State
            \Google_Model::NULL_VALUE, // Region
            \Google_Model::NULL_VALUE, // Country
            \Google_Model::NULL_VALUE, // Postal Code,
            $this->tshirtSize,
            $this->address,
            $this->guid,
            $this->claimed ? 'YES' : 'NO'
        ];
    }
}
