<?php
/**
 * Minds Newsfeed API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\gatherings\api\v1;

use Minds\Core;
use minds\plugin\gatherings\entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class conversations implements Interfaces\api{

    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/conversations
     */
    public function get($pages){

        $response = array();

        if(isset($pages[0])){

            $conversation = new entities\conversation(elgg_get_logged_in_user_guid(), $pages[0]);
            $conversation->clearCount();
            $ik = $conversation->getIndexKeys();
            $guids = core\Data\indexes::fetch("object:gathering:conversation:".$ik[0], array(
              'limit'=>get_input('limit',12),
              'offset'=>get_input('offset', ''),
              'finish'=>get_input('start', ''),
              'reversed'=>true
            ));
            if(isset($guids[get_input('start')])){
                unset($guids[get_input('start')]);
            }
            if(isset($guids[get_input('offset')])){
                unset($guids[get_input('offset')]);
            }

            if($guids){

                $messages = core\Entities::get(array('guids'=>$guids));

                if($messages){

                    foreach($messages as $k => $message){
                      if(isset($_GET['decrypt']) && $_GET['decrypt']){
                        $messages[$k]->decryptMessage(Core\Session::getLoggedInUser()->guid, $_GET['password']);
                      } else {
                        $key = "message:".elgg_get_logged_in_user_guid();
                        $messages[$k]->message = $messages[$k]->$key;
                      }
                    }

                    $messages = array_reverse($messages);
                    $response['messages'] = factory::exportable($messages);
                    $response['load-next'] = (string) end($messages)->guid;
                    $response['load-previous'] = (string) reset($messages)->guid;
                }
            }
            $me = elgg_get_logged_in_user_guid();
            $you = $pages[0] ;
            //return the public keys
            $response['publickeys'] = array(
                $me => elgg_get_plugin_user_setting('publickey', elgg_get_logged_in_user_guid(), 'gatherings'),
                $pages[0] => elgg_get_plugin_user_setting('publickey', $pages[0], 'gatherings')
            );

        } else {

            $conversations = \minds\plugin\gatherings\start::getConversationsList(get_input('offset', ''));

            if($conversations){
                $response['conversations'] = factory::exportable($conversations, array('unread', 'last_msg'));
                $response['load-next'] = (string) end($conversations)->guid;
                $response['load-previous'] = (string) reset($conversations)->guid;
            }

        }

        return Factory::response($response);

    }

    public function post($pages){
        //error_log("got a message to send");
        $conversation = new entities\conversation(elgg_get_logged_in_user_guid(), $pages[0]);

        $message = new entities\message($conversation);
        if(isset($_POST['encrypt']) && $_POST['encrypt']){
          $message->message = $_POST['message'];
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

        if($message->client_encrypted)
          $key = "message:".elgg_get_logged_in_user_guid();
        else
          $key = "message";
        $message->message = $message->$key;
        $response["message"] = $message->export();

        return Factory::response($response);
    }

    public function put($pages){

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

        return Factory::response(array());

    }

}
