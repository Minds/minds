<?php
/**
 * Minds Newsfeed API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Plugin\Messenger\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;
use Minds\Plugin\Messenger;
use Minds\Interfaces;
use Minds\Api\Factory;

class conversations implements Interfaces\Api
{

    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/conversations
     */
    public function get($pages)
    {

        Factory::isLoggedIn();

        $response = [];

        if(isset($pages[0])){
            $response = $this->getConversation($pages);
        } else {
            $response = $this->getList();
        }


        return Factory::response($response);

    }

    private function getConversation($pages)
    {
        $response = [];

        $me = Core\Session::getLoggedInUser();
        $user = Entities\Factory::build($pages[0]);

        $conversation = (new Messenger\Core\Conversation)
          ->setParticipant($me)
          ->setParticipant($user);

        $is_subscribed = $me->isSubscribed($user->guid);
        $is_subscriber = $user->isSubscribed($me->guid);
        if(!$is_subscribed || !$is_subscriber){
            return Factory::response(array(
              'status'=>'error',
              'message' => "No mutual subscription",
              'subscribed' => (bool) $is_subscribed,
              'subscriber' => (bool) $is_subscriber,
              'user' => $user->export()
            ));
        }

        //$conversation->clearCount();

        $messages = $conversation->getMessages($_GET['limit'], $_GET['offset']);

        if($messages){

            foreach($messages as $k => $message){
                if(isset($_GET['decrypt']) && $_GET['decrypt']){
                    $messages[$k]->decrypt(Core\Session::getLoggedInUser(), urldecode($_GET['password']));
                } else {
                    //support legacy clients
                    $key = "message:$me->guid";
                    $messages[$k]->message = $messages[$k]->$key;
                }
            }

            $messages = array_reverse($messages);
            $response['messages'] = Factory::exportable($messages);
            $response['load-next'] = (string) end($messages)->guid;
            $response['load-previous'] = (string) reset($messages)->guid;
        }

        //return the public keys
        $response['publickeys'] = array(
            $me => elgg_get_plugin_user_setting('publickey', elgg_get_logged_in_user_guid(), 'gatherings'),
            $pages[0] => $user->{"plugin:user_setting:gatherings:publickey"} ?: elgg_get_plugin_user_setting('publickey', $pages[0], 'gatherings')
        );

        return $response;
    }

    private function getList()
    {
        $response = [];

        $conversations = (new Messenger\Core\Conversations)->getList(12, $_GET['offset']);

        if($conversations){
            $response['conversations'] = $conversations;
            end($conversations);
            $response['load-next'] = (string) key($conversations);
            $response['load-previous'] = (string) key(reset($conversations));
        }
        return $response;
    }

    public function post($pages){
        Factory::isLoggedIn();

        //error_log("got a message to send");
        $conversation = new entities\conversation(Core\Session::getLoggedInUser()->guid, $pages[0]);

        $message = new entities\message($conversation);
        if(isset($_POST['encrypt']) && $_POST['encrypt']){
          $message->encryptMessage($_POST['message']);
        } else {
          $message->client_encrypted = true;
          foreach($conversation->participants as $guid){
              $key = "message:$guid";
              $message->$key = base64_encode(base64_decode(rawurldecode($_POST[$key]))); //odd bug sometimes with device base64..
          }
        }

        $message->save();

        $conversation->update();
        $conversation->notify();

        if($message->client_encrypted){
          $key = "message:".elgg_get_logged_in_user_guid();
          $message->message = $message->$key;
        } else {
          $key = "message";
          $message->message = $_POST['message'];
        }
        $response["message"] = $message->export();

        return Factory::response($response);
    }

    public function put($pages){
        Factory::isLoggedIn();

        switch($pages[0]){
            case 'call':
               \Minds\Core\Queue\Client::build()->setExchange("mindsqueue")
                                                ->setQueue("Push")
                                                ->send(array(
                                                     "user_guid"=>$pages[1],
                                                    "message"=> \Minds\Core\Session::getLoggedInUser()->name . " is calling you.",
                                                    "uri" => 'call',
                                                    "sound" => 'ringing-1.m4a',
                                                    "json" => json_encode(array(
                                                        "from_guid"=>\Minds\Core\Session::getLoggedInUser()->guid,
                                                        "from_name"=>\Minds\Core\Session::getLoggedInUser()->name
                                                    ))
                                                ));
                break;
            case 'no-answer':
              //leave a notification
              $conversation = new entities\conversation(elgg_get_logged_in_user_guid(), $pages[1]);
              $message = new entities\CallMissed($conversation);
              $message->save();
              $conversation->update();
              Core\Queue\Client::build()->setExchange("mindsqueue")
                                        ->setQueue("Push")
                                        ->send(array(
                                              "user_guid"=>$pages[1],
                                              "message"=> \Minds\Core\Session::getLoggedInUser()->name . " tried to call you.",
                                              "uri" => 'chat',

                                             ));
              break;
            case 'ended':
              $conversation = new entities\conversation(elgg_get_logged_in_user_guid(), $pages[1]);
              $message = new entities\CallEnded($conversation);
              $message->save();
              break;
        }

        return Factory::response(array());

    }

    public function delete($pages){

      $message = \Minds\Entities\Factory::build($pages[1]);
      if($message->canEdit()){
          $message->delete();
      }

        return Factory::response(array());

    }

}
