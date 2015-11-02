<?php
/**
 * Minds cluster subscriptions
 */
namespace Minds\Controllers\clusters;

use Minds\Core;
use Minds\Interfaces;
use Minds\Entities;

class subscriptions extends core\page implements Interfaces\page
{
    public $csrf = false; //ignore csrf
    
    public function get($pages)
    {
    }
    
    public function post($pages)
    {
        if (!isset($pages[1])) {
            echo json_encode(array('error'=>'GUID must be supplied in the request uri'));
            return false;
        }
        
        $user_guid = $pages[1];
        
        if (!isset($_POST['guid'])) {
            echo json_encode(array('error'=>'GUID must be sent in the post body'));
            return false;
        }
        $subscriber_guid = $_POST['guid'];
        
        if (!isset($_POST['host'])) {
            echo json_encode(array('error'=>'HOST must be sent in the post body'));
            return false;
        }
        $host = $_POST['host'];
        
        switch ($pages[0]) {
            case 'subscribe':
                $secret = core\clusters::generateSecret();
                $user = new Entities\User($user_guid);
                if (!$user->guid) {
                    echo json_encode(array('error'=>'The user couldn\'t be found'));
                    return false;
                }

                $db = new core\Data\Call('friendsof');
                $data = array_merge($_POST, array('host'=>$host, 'secret'=>$secret));
                error_log("$subscriber_guid : " . print_r($data, true));
                $subscription = $db->insert($user->guid, array($subscriber_guid=>json_encode($data)));
                
                echo json_encode(array(
                    'success' => array_merge($user->export(), array(
                        'secret' => $secret,
                        'host' => elgg_get_site_url()
                    ))));
                    
                break;
            case 'unsubscribe':
                
                /**
                 * First off, lets just verify our user exists, and is in fact subscribed to this user
                 */
                $db = new core\Data\Call('friends');
                $subscription = $db->getRow($user_guid, array('limit'=> 1, 'offset'=>$subscriber_guid));
                
                if (key($subscription) != $subscriber_guid) {
                    echo json_encode(array('error'=> "$subscriber_guid is not a subscriber."));
                    return true;
                }

                $payload = json_decode($subscription, true);
                $secret = $payload['secret'];//the shared secret
                
                /**
                 * Validate our signature..
                 */
                $signature = core\clusters::generateSignature($_POST, $secret);
                if ($_SERVER['HTTP_X_MINDS_SIGNATURE'] != $signature) {
                    echo json_encode(array('error'=>'Incorrect signature. Please check the secret key'));
                    return false;
                }
                
                $db->removeAttributes($user_guid, array($subscriber_guid));
                
                echo json_encode(array('success' => 'unsubscribed'));
                
                break;
        }
    }
    
    public function put($pages)
    {
        throw new \Exception('Sorry, the put method is not supported for the page');
    }
    
    public function delete($pages)
    {
        throw new \Exception('Sorry, the delete method is not supported for the page');
    }
}
