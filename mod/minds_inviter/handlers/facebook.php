<?php
 $appid = '184865748231073'; //make this in settings
 $redirect = elgg_get_site_url() . 'invite/closewindow';
 forward("https://www.facebook.com/dialog/apprequests?app_id=$appid&redirect_uri=$redirect&message=I'd%20like%20to%20invite%20you%20to%20minds&display=popup");
 
 
