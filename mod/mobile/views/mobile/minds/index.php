<?php
/**
 * metatags to show mobile thmem
 * written by Mark Harding @ kramnorth
 * copyright Kramnorth 2011
 */



if (!elgg_is_logged_in()) {


echo elgg_view('core/account/login_box');


} else {
	forward("news");
}
?>