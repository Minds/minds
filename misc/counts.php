<?php

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$user = array('peggy', 'ottman', 'mark', 'john', 'markandrewculp', 'IanCrossland', 'jack');
foreach($user as $u){
    $u = new Minds\entities\user($u);
    Minds\Helpers\Counters::increment($u->guid, 'points', $u->points_count);
}
//var_dump(new Minds\entities\user('mark'));
//login(new Minds\entities\user('mark'));

