<rooms>
<?php
if ($_COOKIE["userroom"]) echo "<room room_name=\"".$_COOKIE["userroom"]."\" room_description=\"Welcome to ".$_COOKIE["userroom"]."!\" user_number=\"0\" capacity=\"100\" private_room=\"1\"/>";
?>
<room room_name="Lobby" room_description="Welcome!" user_number="0" capacity="100" />
<room room_name="Fun" room_description="Haha!" user_number="0" capacity="50" />
<room room_name="Zero" room_description="Room full test" user_number="0" capacity="0" />
<room room_name="Hangout" room_description="Chill..." user_number="0" capacity="50" />
<room room_name="Room 1" room_description="Some room" user_number="0" capacity="50" />
<room room_name="Room 2" room_description="Some other room" user_number="0" capacity="50" />
</rooms>