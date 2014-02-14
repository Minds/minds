<?php

require('/var/www/elgg/engine/start.php');

$subject = get_user_by_username('mark');
$followers = $subject->getFriendsOf(null, 10000, "", 'guids');
var_dump($followers);
