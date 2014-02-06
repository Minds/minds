<?php

require('/var/www/elgg/engine/start.php');


$ts = 1391607071;
global $SESSION;
$st = $SESSION['__elgg_session'];

$token = generate_action_token($ts);

var_dump($st, $ts, session_id(), $token);
