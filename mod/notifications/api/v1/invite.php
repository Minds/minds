<?php
/**
 * Minds Invite API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\notifications\api\v1;

use Minds\Core;
use minds\interfaces;
use Minds\Api\Factory;

class invite implements Interfaces\api{

    /**
     * Return a list of notifications
     * @param array $pages
     * 
     * API:: /v1/invite
     */      
    public function get($pages){
	   $response = array();
       return Factory::response($response);
    }
    
    /**
     * Send email invitations to users
     * 
     */
    public function post($pages){

        $user = Core\Session::getLoggedinUser();
       
        if(isset($_POST['contacts'])){         
            $contacts = $_POST['contacts'];
            foreach($contacts as $contact){
                $name = $contact['name']['formatted'];
                $email = $contact['emails'][0]['value'];
                $html = "<h1>Hey $name, $user->name invited you to use Minds</h1> <a href='http://minds.com/app'>Click here</a> to download the app: http://minds.com/app</a>";
                if($email)
                    $send = phpmailer_send(elgg_get_site_entity()->email,elgg_get_site_entity()->name, $email, $name, "$user->name invited you to Minds", $html, null, true);
            }
        }

        if(isset($_POST['contact'])){
            //error_log(print_r($_POST['contact'],true));
            $contact = $_POST['contact'];
            $name = $contact['name']['formatted'];
            $email = $contact['emails'][0]['value'];
            //$html = "<h1>Hey $name, $user->name invited you to use Minds</h1> <a href='http://minds.com/app'>Click here</a> to download the app: http://minds.com/app</a>";
            $html = elgg_view('emails/invite', array('user'=>$user, 'name'=>$name));
            if($email)
                $send = phpmailer_send(elgg_get_site_entity()->email,elgg_get_site_entity()->name, $email, $name, "$user->name invited you to Minds", $html, null, true);

        }

           $response = array(); 
        return Factory::response($response);
    }
    
    /**
     * Not supported
     */
    public function put($pages){}
    
    /**
     * Not supported
     */
    public function delete($pages){}
 
}
        
