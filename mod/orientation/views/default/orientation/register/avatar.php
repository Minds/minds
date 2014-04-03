<?php
$user = elgg_get_logged_in_user_entity();

//echo elgg_view_form('avatar/crop');
?>
<div class="blurb">
	A cool avatar can get you noticed. 
</div>
<?php

// only offer the crop view if an avatar has been uploaded
if (isset($user->icontime)) {
	echo elgg_view('forms/avatar/crop', array('entity' => $user));
} else {
	echo elgg_view('forms/avatar/upload', array('entity' => $user));
}
