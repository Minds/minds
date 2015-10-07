<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$entities = new Minds\Core\Data\Call('entities');
$indexes = new Minds\Core\Data\Call('entities_by_time');
$lu = new Minds\Core\Data\Call('user_index_to_guid');

$campaign = $argv[1];
echo "Emailing out $campaign \n";

$users = array(new Minds\Entities\user('mark'));



/*$count = 0;
$offset = isset($argv[2]) ? $argv[2] : "";
while(true){

    $guids = $indexes->getRow('user', array('limit' => 1000, 'offset' => $offset));
    $users = $entities->getRows(array_keys($guids));
    if(count($guids) <= 1)
        break;

    echo "Batch: ";
 */
    foreach($users as $guid => $data){
        $user = new Minds\Entities\user($data);
        if(!$user->guid)
          continue;

        if($user->disabled_emails){
            echo "x";
            continue;
        }

        $vars = array(
            'username'=> $user->username,
            'email' => $user->getEmail()
        );
        
        ob_start();

        include($campaign);
        
        $html = ob_get_clean();

        phpmailer_send(elgg_get_site_entity()->email,elgg_get_site_entity()->name, $user->getEmail(), $user->name, "$user->name invited you to Minds", $html, null, true);  

        $count++;
        echo ".";
    }

/*    end($guids);
    $offset = key($guids);
    echo "Done up to $offset \n";

}*/
echo "\n Sent $count emails \n";

