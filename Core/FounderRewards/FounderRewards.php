<?php

namespace Minds\Core\FounderRewards;

use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Minds\Core;

class FounderRewards
{
    //communicate with google spreadsheets here
    private $client;
    private $config;
    private $spreadsheetId;

    public function __construct($config = null)
    {
        $this->config = $config ?: Core\Di\Di::_()->get('Config');
        $this->initClient();
    }

    protected function initClient()
    {
        $config = $this->config->get('google');
        $this->spreadsheetId = $config['sheets']['wefunder_sheet_id'];

        $googleClient = new \Google_Client();
        $googleClient->setApplicationName('Minds');
        $googleClient->setAuthConfig($config['sheets']['service_account']['key_path']);
        $googleClient->setScopes(implode(' ', array(Google_Service_Sheets::SPREADSHEETS)));
        $this->client = new \Google_Service_Sheets($googleClient);

        return $this;
    }

    public function getFounders()
    {
        $range = 'Ledger | Final!A2:U1547';
        $response = $this->client->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues();

        $toFounder = function ($array, $key) {
            $founder = new Founder();
            $founder->rowNumber = $key + 2;
            $founder->name = $array[0];
            $founder->email = $array[1];
            $founder->uuid = $array[2]; // using the investment ID
            $founder->amount = (int) preg_replace('/[^0-9\.-]+/', '', $array[3]); // because of excel's formatting as $##,###.##
            $founder->postalAddress = $array[11];
            $founder->tshirtSize = $array[17];
            $founder->address = $array[18];
            $founder->guid = $array[19];
            $founder->claimed = strtoupper($array[20]) == 'YES';
            return $founder;
        };

        $founders = [];
        foreach ($values as $key => $value) {
            $founders[] = $toFounder($value, $key);
        }

        return $founders;
    }

    public function getRewardTypes()
    {
        $range = 'Rewards to Issue!A2:E12';
        $response = $this->client->spreadsheets_values->get($this->spreadsheetId, $range);
        $toRewardType = function ($array) {
            $rewardType = new RewardType();
            $rewardType->name = $array[0];
            $rewardType->threshold = (integer)str_replace(',', '', substr($array[1], 0, stripos($array[1], '.'))); // removing decimals and commas
            $rewardType->quantity = (integer)$array[2];
            $rewardType->requiresTShirtSize = (boolean)$array[3];
            $rewardType->requiresCellPhone = (boolean)$array[4];
            return $rewardType;
        };
        return array_map($toRewardType, $response->getValues());
    }

    public function getEligibleRewards($amount)
    {
        $rewardTypes = $this->getRewardTypes();
        return array_filter($rewardTypes, function ($item) use ($amount) {
            return $amount >= $item->threshold;
        });
    }

    /**
     * Updates spreadsheet
     * @param Core\FounderRewards\Founder $founder
     */
    public function claimReward($founder)
    {
        $founder->claimed = true;
        $range = 'Ledger | Final!A' . $founder->rowNumber . ':U' . $founder->rowNumber;
        $optParams = [];

        $optParams['valueInputOption'] = 'USER_ENTERED';
        
        $requestBody = new Google_Service_Sheets_ValueRange();
        $requestBody->setValues(
            [
                $founder->toRow()
            ]
        );

        $this->client->spreadsheets_values->update($this->spreadsheetId, $range, $requestBody, $optParams);
    }
}
