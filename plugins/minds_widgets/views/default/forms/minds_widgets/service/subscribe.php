<?php 


$guid = (int)get_input('channel_guid');
if ($user = get_entity($guid))
{

echo elgg_view('input/hidden', array('name' => 'channel_guid', 'value' => $user->guid));
echo elgg_view('input/submit', array('value' => 'Subscribe to @' . $user->username));
}
else {
        register_error("No such channel");
    forward();
}
?>