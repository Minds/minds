<?php
//hack! Elgg engine should take care of this, or blog/save form should be coded better
if (elgg_is_xhr() && isset($vars['entity_guid'])) {
	elgg_set_page_owner_guid($vars['entity_guid']);
}
echo elgg_view_form('messageboard/add', array(), $vars);