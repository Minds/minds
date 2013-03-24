<?php 
/**
 * Shows a tick box if a user has a facebook/twitter account associated. 
 *
 */
if(elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()){
 $facebook = elgg_get_plugin_user_setting('minds_social_facebook_uid', elgg_get_logged_in_user_guid(), 'minds_social');
 
 if($facebook){
 	echo elgg_view('output/img', array('src'=>elgg_get_site_url().'mod/minds_social/graphics/fb_small.png'));
 	echo elgg_view('input/checkbox', array('name'=>'facebook', 'checked'=>'checked'));
 }
 
 $twitter = elgg_get_plugin_user_setting('twitter_name', elgg_get_logged_in_user_guid(), 'minds_social');
 
 if($twitter){
 	echo elgg_view('output/img', array('src'=>elgg_get_site_url().'mod/minds_social/graphics/twitter_small.png'));
 	echo elgg_view('input/checkbox', array('name'=>'twitter', 'checked'=>'checked'));
 }
}