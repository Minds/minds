<?php

//this needs to be a slightly different form as we want a text area.
$form = elgg_view_form('minds_inviter/invite');
echo elgg_view_page('', $form);