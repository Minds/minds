<?php
namespace Minds\Helpers\Campaigns;

use Minds\Core;
use Minds\Core\Guid;
use Minds\Core\Config;
use Minds\Helpers;
use Minds\Entities;
use Minds\Core\Di\Di;
use Minds\Core\Blockchain\Transactions\Transaction;

/**
 * Helper for Email Rewards
 * @todo Avoid static and use proper DI
 */
class EmailRewards
{
    /**
     * Grants an email reward to an user
     * @param  string $campaign
     * @param  mixed  $user_guid
     * @return null
     */
    public static function reward($campaign, $user_guid)
    {
        if (!is_numeric($user_guid)) {
            return;
        }

        $user = new Entities\User($user_guid);

        $wire = false;

        $cacher = Core\Data\cache\factory::build('apcu');
        $label = $campaign;
        switch ($campaign) {
          case "retention-1":
          case "retention-3":
          case "retention-7":
          case "retention-28":
            $points = 100;
            $label = "Check-in bonus";
            break;
          case "announcement-20-02-18":
          case "announcement-21-02-18":
            $validator = $_GET['validator'];
            if ($validator == sha1($campaign . $user->guid . Config::_()->get('emails_secret'))) {
                $points = 500;
                $wire = true;
            } else {
                echo "Validator failed"; exit;
            }
            break;
          case "when":
            $topic = $_GET['topic'];
            if ($topic != 'wire_received') {
                return;
            }
            $validator = $_GET['validator'];
            $key = 'june-18';
            if ($validator == sha1($campaign . $key . $user->guid . Config::_()->get('emails_secret'))) {
                $tokens = (10 ** 18) / 2;
                $wire = true;
                $campaign = $validator; //hack
            } else {
                return;
            }
            break;
          case "global":
              $topic = $_GET['topic'];
              if ($topic != 'minds_news') {
                  return;
              }
              $validator = $_GET['validator'];
              if ($validator == sha1($campaign . $topic . $user->guid . Config::_()->get('emails_secret'))) {
                  $tokens = (10 ** 18);
                  $campaign = $validator; //hack
              } else {
                return;
              }
              break;
          default:
            return;
        }

        if ($cacher->get("rewarded:email:$campaign:$user_guid") == true) {
            return;
        }

        $db = new Core\Data\Call('entities_by_time');
        $ts = Helpers\Analytics::buildTS("day", time());
        $row = $db->getRow("analytics:rewarded:email:$campaign", ['offset'=> $user_guid, 'limit'=>1]);
        if (!$row || key($row) != $user_guid) {
            $db->insert("analytics:rewarded:email:$campaign", [ $user_guid => time()]);

            $transaction = new Transaction(); 
            $transaction
                ->setUserGuid($user->guid)
                ->setWalletAddress('offchain')
                ->setTimestamp(time())
                ->setTx('oc:' . Guid::build())
                ->setAmount($tokens)
                ->setContract('offchain:wire')
                ->setCompleted(true)
                ->setData([
                    'amount' => (string) $tokens,
                    'sender_guid' => "730071191229833224",
                    'receiver_guid' => (string) $user->guid,
                    'entity_guid' => (string) $user->guid,
                    'promotion' => true,
                ]);
            Di::_()->get('Blockchain\Transactions\Repository')->add($transaction);
        }
        $cacher->set("rewarded:email:$campaign:$user_guid", true, strtotime('tomorrow', time()) - time());
    }
}
