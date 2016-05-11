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
use Minds\Core\Sockets;

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

        $conversation = (new Messenger\Entities\Conversation())
          ->loadFromGuid($pages[0]);
        $messages = (new Messenger\Core\Messages)
          ->setConversation($conversation);

        /*$is_subscribed = $me->isSubscribed($user->guid);
        $is_subscriber = $user->isSubscribed($me->guid);
        if(!$is_subscribed || !$is_subscriber){
            return Factory::response(array(
              'status'=>'error',
              'message' => "No mutual subscription",
              'subscribed' => (bool) $is_subscribed,
              'subscriber' => (bool) $is_subscriber,
              'user' => $user->export()
            ));
        }*/

        //$conversation->clearCount();

        if ($conversation) {
            $response = $conversation->export();
        }

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 6;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "";
        $finish = isset($_GET['finish']) ? $_GET['finish'] : "";
        $messages = $messages->getMessages($limit, $offset, $finish);

        if($messages){

            foreach($messages as $k => $message){
              $_GET['decrypt'] = true;
                if(isset($_GET['decrypt']) && $_GET['decrypt']){
                    $messages[$k]->decrypt(Core\Session::getLoggedInUser(), urldecode($_GET['password']));
                } else {
                    //support legacy clients
                    //$key = "message:$me->guid";
                    //$messages[$k]->message = $messages[$k]->$key;
                }
            }

            $messages = array_reverse($messages);
            $response['messages'] = Factory::exportable($messages);
            $response['load-next'] = (string) end($messages)->guid;
            $response['load-previous'] = (string) reset($messages)->guid;
        }

        //return the public keys
        $response['publickeys'] = array(
            (string) $me->guid => elgg_get_plugin_user_setting('publickey', elgg_get_logged_in_user_guid(), 'gatherings'),
            $pages[0] => $user->{"plugin:user_setting:gatherings:publickey"} ?: elgg_get_plugin_user_setting('publickey', $pages[0], 'gatherings')
        );

        return $response;
    }

    private function getList()
    {
        $response = [];

        $conversations = (new Messenger\Core\Conversations)->getList(12, $_GET['offset']);

        if($conversations){
            $response['conversations'] = Factory::exportable($conversations);
            end($conversations);
            $response['load-next'] = (string) key($conversations);
            $response['load-previous'] = (string) key(reset($conversations));
        }
        return $response;
    }

    public function post($pages){
        Factory::isLoggedIn();

        //error_log("got a message to send");
        $conversation = new Messenger\Entities\Conversation();
        if(strpos($pages[0], ':') === FALSE){ //legacy messages get confused here
            $conversation->setParticipant(Core\Session::getLoggedInUserGuid())
              ->setParticipant($pages[0]);
        } else {
            $conversation->setGuid($pages[0]);
        }

        $message = (new Messenger\Entities\Message())
          ->setConversation($conversation);

        if(isset($_POST['encrypt']) && $_POST['encrypt']){
            $message->setMessage($_POST['message']);
            $message->encrypt();
        } else {
            $message->client_encrypted = true;
            foreach($conversation->getParticipants() as $guid){
                $msg = base64_encode(base64_decode(rawurldecode($_POST[$key]))); //odd bug sometimes with device base64..
                $message->setMessages($guid, $msg, true);
            }
        }

        $message->save();

        //$conversation->update();
        //$conversation->notify();

        /*if($message->client_encrypted){
          $key = "message:".elgg_get_logged_in_user_guid();
          $message->message = $message->$key;
        } else {
          $key = "message";
          $message->message = $_POST['message'];
        }*/
        $response["message"] = $message->export();

        try {
            (new Sockets\Events())
              ->to($conversation->buildSocketRoomName())
              ->emit('pushConversationMessage', (string) $conversation->getGuid(), $response["message"]);
        } catch (\Exception $e) { /* TODO: To log or not to log */ }

        if ($conversation->getParticipants()) {
            $messenger_rooms = [];
            foreach ($conversation->getParticipants() as $guid) {
                if ($guid == Core\Session::getLoggedInUserGuid()) {
                    continue;
                }

                $messenger_rooms[] = "messenger:{$guid}";
            }

            try {
                (new Sockets\Events())
                ->to($messenger_rooms)
                ->emit('touchConversation', (string) $conversation->getGuid());
            } catch (\Exception $e) { /* TODO: To log or not to log */ }
        }

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
