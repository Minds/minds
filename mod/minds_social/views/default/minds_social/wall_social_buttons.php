<?php 
/**
 * Shows a tick box if a user has a facebook/twitter account associated. 
 *
 */
elgg_load_js('minds.social.js');
if(elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()){
 $facebook = elgg_get_plugin_user_setting('minds_social_facebook_uid', elgg_get_logged_in_user_guid(), 'minds_social');
 
 echo '<span class="entypo">&#62221;</span>';
 echo elgg_view('input/checkbox', array('name'=>'facebook', 'checked'=> $facebook ? 'checked' : false, 'linked'=> $facebook?'yes':'no'));
 
  
 $twitter = elgg_get_plugin_user_setting('twitter_name', elgg_get_logged_in_user_guid(), 'minds_social');
 
 echo '<span class="entypo">&#62218;</span>';
 echo elgg_view('input/checkbox', array('name'=>'twitter', 'checked'=> $twitter ? 'checked' : false, 'linked'=>$twitter ? 'yes':'no'));
 
}
