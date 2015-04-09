<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

/*$videos = Minds\Core\entities::get(array("subtype"=>'video', 'limit'=>1000000));
foreach($videos as $video){
echo $video->{"thumbs:up:count"} . "\n";
 //   Minds\Helpers\Counters::increment($video->guid, 'thumbs:up', $video->{"thumbs:up:count"});

}
exit;
 */
$i = 0;
$offset = "";
while(true){
    $activity = Minds\Core\entities::get(array("type"=>'activity', 'limit'=>1000, 'offset'=>$offset));
    $offset = end($activity)->guid;
    echo "$offset \n";
    foreach($activity as $a){
                
        if($a->entity_guid){
            echo "updating count for $a->entity_guid \n";
            Minds\Helpers\Counters::increment($a->entity_guid, 'thumbs:up', $a->{"thumbs:up:count"});
        }
    }
}
