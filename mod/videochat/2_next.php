<?php
/*
This script implements a custom Next button function that can be used for various implementations.

POST Variables:
u=Username
s=Session, usually same as username
r=Room
cam, mic = 0 none, 1 disabled, 2 enabled
*/

$room=$_POST['r'];
$session=$_POST['s'];
$username=$_POST['u'];
$cam=$_POST['cam'];
$mic=$_POST['mic'];

$next_room="next_test";
$day=date("y-M-j",time());
$chat="uploads/$next_room/Log$day.html";	
$chatlog="The transcript of this conversation, including snapshots is available at <U><A HREF=\"$chat\" TARGET=\"_blank\">$chat</A></U>.";

//these produce actions if defined
$redirect_url=urlencode(""); //disconnect and redirect to url
$disconnect=urlencode(""); //disconnect with that message to standard disconnect page
$message=urlencode("Next button pressed. This feature can be programmed from 2_next.php or disabled from 2_login.php parameters. $chatlog"); //show this message to user
$send_message=urlencode("I pressed next."); //user sends this message to room
$next_room=urlencode($next_room); //user moves to this room
?>firstParameter=1&next_room=<?=$next_room?>&message=<?=$message?>&send_message=<?=$send_message?>&redirect_url=<?=$redirect_url?>&disconnect=<?=$disconnect?>&loadstatus=1