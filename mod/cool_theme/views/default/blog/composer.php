<?php
elgg_load_library('elgg:blog');
$body_vars = blog_prepare_form_vars();

//hack! Elgg engine should take care of this, or blog/save form should be coded better
if (elgg_is_xhr() && isset($vars['container_guid'])) {
	elgg_set_page_owner_guid($vars['container_guid']);
}

echo elgg_view_form('blog/save', array(), array_merge($body_vars, $vars));