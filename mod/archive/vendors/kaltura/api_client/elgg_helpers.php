<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/
/**
 * Usefull functions that are missing in Elgg or needed for some specific purpose
 * */

//Checks if a video is rated by the user
function kaltura_is_rated_by_user($guid,$user_entity,$current_votes=1) {
	$user_ratings = $user_entity->getAnnotations('kaltura_video_rated');
	//echo "[".print_r($user_ratings,true)."]";die;
	foreach ($user_ratings as $user_rating){
		//$ratingobject->delete();
		if($user_rating['value'] == $guid) {
			if($current_votes==0) {
				delete_annotation($user_rating['id']);
				continue;
			}
			return true;
		}
	}
	return false;
}
//returns a rating of video object
function kaltura_get_rating($entity) {
	$article_rating = 0;
	$getoldrates = $entity->getAnnotations('kaltura_video_rating',1);
	foreach ($getoldrates as $getoldrate){
		$article_rating = $getoldrate['value'];
	}
	$numvotes = 0;
	$getnumvotes = $entity->getAnnotations('kaltura_video_numvotes',1);
	foreach ($getnumvotes as $getnumvote){
		$numvotes = $getnumvote['value'];
	}

	// Match rating to image
	if($numvotes == 0){
			$rating_image = "rating0.gif";
	} else {
		if((($article_rating >= 0)or($article_rating == 0)) && ($article_rating <= 0.50)){
			$rating_image = "rating0.gif";
		}
		if((($article_rating >= 0.50)or($article_rating == 0.50)) && ($article_rating <= .99)){
			$rating_image = "rating0_halt.gif";
		}
		if((($article_rating >= 1.00)or($article_rating == 1.50)) && ($article_rating <= 1.49)){
			$rating_image = "rating1.gif";
		}
		if((($article_rating >= 1.50)or($article_rating == 1.50)) && ($article_rating <= 1.99)){
			$rating_image = "rating1_half.gif";
		}
		if((($article_rating >= 2.00)or($article_rating == 2.00)) && ($article_rating <= 2.49)){
			$rating_image = "rating2.gif";
		}
		if((($article_rating >= 2.50)or($article_rating == 2.50)) && ($article_rating <= 2.99)){
			$rating_image = "rating2_half.gif";
		}
		if((($article_rating >= 3.00)or($article_rating == 3.00)) && ($article_rating <= 3.49)){
			$rating_image = "rating3.gif";
		}
		if((($article_rating >= 3.50)or($article_rating == 3.50)) && ($article_rating <= 3.99)){
			$rating_image = "rating3_half.gif";
		}
		if((($article_rating >= 4.00)or($article_rating == 4.00)) && ($article_rating <= 4.49)){
			$rating_image = "rating4.gif";
		}
		if((($article_rating >= 4.50)or($article_rating == 4.50)) && ($article_rating <= 4.99)){
			$rating_image = "rating4_half.gif";
		}
		if($article_rating == 5.0){
			$rating_image = "rating5.gif";
		}
	}
	return array($numvotes,$rating_image,$article_rating);
}

//gets a kaltura object with all metadata from a guid
function kaltura_get_metadata($entity) {
	unset($ob);
	if(!$entity) return false;
	$options = array(
		'guid' => $entity->getGuid(),
		'limit' => 0
	);
	$metadata = elgg_get_metadata($options);

	foreach($metadata as $meta) {
		if(strpos($meta->name,"kaltura_video_")===0) $ob->{$meta->name} = $meta->value;
	}
	//editable ??
	$ob->kaltura_video_editable = false;
	//collaborative videos
	$ob->kaltura_video_cancollaborate = false;

	if(elgg_is_logged_in() && elgg_get_plugin_setting("alloweditor","archive") != 'no') {
		if($entity->canEdit()) {
			$ob->kaltura_video_editable = true;
		}

		$isgroupmember = is_group_member($entity->container_guid,$_SESSION['user']->getGUID());
		if($ob->kaltura_video_collaborative) {
			if($isgroupmember) {
				$ob->kaltura_video_cancollaborate = true;
			}
		}
	}

	//plugin option override
	if(elgg_get_plugin_setting("enablerating","archive") == 'no') $ob->archive_rating_on = 'Off';

	//the mini applet
	//$ob->kaltura_video_widget_m_width = 250;
	//$ob->kaltura_video_widget_m_height = 244;

	$ob->kaltura_video_widget_m_width = 266;
	$ob->kaltura_video_widget_m_height = 260;
	$ob->kaltura_video_widget_m_html = preg_replace(array('/width="([0-9]*)"/','/height="([0-9]*)"/'),array('width="'.$ob->kaltura_video_widget_m_width.'"','height="'.$ob->kaltura_video_widget_m_height.'"'),$ob->kaltura_video_widget_html);

	//the default date format

	if($ob->kaltura_video_time_created) $ob->kaltura_video_created = elgg_view_friendly_time($ob->kaltura_video_time_created);
	else $ob->kaltura_video_created = elgg_view_friendly_time($entity->time_created);

	return $ob;
}

//gets a kaltura object with all metadata from a kaltura id
function kaltura_get_entity($video_id) {

	if(empty($video_id)) return false;

	$objs = elgg_get_entities_from_metadata(array('metadata_name_value_pairs' => array(
		"kaltura_video_id" => $video_id
		), 'types' => 'object', 'subtypes' => 'kaltura_video'));
	if($objs) {
		return get_entity($objs[0]->guid, 'object');
	}
	return false;
}

//creates or updates a kaltura object with all metadata
function kaltura_update_object($entry,$kmodel=null,$access=ACCESS_DEFAULT,$user_guid=null,$container_guid=null,$force=false, $params = null) {
		
	global $CONFIG,$KALTURA_GLOBAL_UICONF;

	$ob = kaltura_get_entity($entry->id);

	if($user_guid){
		if($ob instanceof ElggEntity) {
			$ob->owner_guid = $user_guid;
			$ob->container_guid = ($container_guid ? $container_guid : $user_guid);
			$ob->save();
		}
		else {
			//will create the new object if not exists
			//echo "Creating new: $user_guid:$container_guid\n";

            $ob = new ElggObject();
			$ob->subtype = 'kaltura_video';
			$ob->title = $params['title'] ? $params['title'] : $entry->name;
			$ob->description = $entry->description;
			$ob->tags = $entry->tags;
			$ob->owner_guid = $user_guid;
			$ob->container_guid = ($container_guid ? $container_guid : $user_guid);
			$ob->access_id = $access;
            $guid = $ob->save(); //save here to get the guid

			add_to_river('river/object/kaltura_video/create','create',$user_guid,$guid);
		}
	}

    if(!($ob instanceof ElggEntity)) {
		//echo " no entity\n";
		return false;
	}

	$ob->kaltura_video_id = $entry->id;

	//keep the current metada if exists (if not forced)...
	if($force || (empty($ob->title) && isset($entry->name))) $ob->title = $entry->name;
	if($force || (empty($ob->description) && isset($entry->description))) $ob->description = $entry->description;
	if($force || (empty($ob->tags) && isset($entry->tags))) {
		$ob->deleteMetadata('tags');
		if($array = string_to_tag_array($entry->tags)) {
			foreach($array as $i => $arr) {
				$array[$i] = trim($arr);
			}
		}
		$ob->tags = $array;
	}

	if($entry->comments_on) $ob->kaltura_video_comments_on = $entry->comments_on;
	if($entry->rating_on) $ob->kaltura_video_rating_on = $entry->rating_on;
	if($entry->plays) $ob->kaltura_video_plays = $entry->plays;
	if($entry->duration) $ob->kaltura_video_length = kaltura_parse_time($entry->duration);
	if($entry->thumbnailUrl) $ob->kaltura_video_thumbnail = $entry->thumbnailUrl;
	if($entry->downloadUrl) $ob->kaltura_video_download = $entry->downloadUrl;

	//for the rss
	if($entry->createdAt) {
		$ob->kaltura_video_time_created = $entry->createdAt;
		$ob->time_created = $entry->createdAt;
		$ob->time_updated = $entry->createdAt;
	}

	$ob->access_id = $access;

	//group perms
	$ob->kaltura_video_isgroup = 0;
	$ob->kaltura_video_group_visible = 0;
	$group = get_entity($ob->container_guid);
	if ($group instanceof ElggGroup) {
		$ob->kaltura_video_isgroup = 1;
		if($ob->access_id) {
			$ob->kaltura_video_group_visible = 1;
		}
	}

	if($kmodel) {
		//create the widget if needed
		$default_player = elgg_get_plugin_setting('defaultplayer', 'archive');
		if($default_player == 'custom') {
			$widgetUi = elgg_get_plugin_setting('custom_kdp', 'archive');
		}
		else {
			$t = elgg_get_plugin_setting('kaltura_server_type',"archive");
			if(empty($t)) $t = 'corp';
			$players = $KALTURA_GLOBAL_UICONF['kdp'][$t];
			if(!array_key_exists($default_player,$players)) $default_player = key($players);
			$widgetUi = $players[$default_player]['uiConfId'];
		}

		$metadata = kaltura_get_metadata($ob);
		$widget = null;

		//get the current widget if exists
		/*
		if($metadata->kaltura_video_widget_id) {
			try{
				$widget = $kmodel->getWidget($metadata->kaltura_video_widget_id);
			}catch(KalturaException $e) {
				$widget = null;
			}
		}
		* */

        if(!$widget) {
			//search for existing widgets for this entryId
			$widgets = $kmodel->listWidgets(1,0,$widgetUi,$entry->id);
			if($widgets->totalCount>0){
				$widget = $widgets->objects[0];
			}
			else {
				$widget = $kmodel->addWidget($entry->id,$widgetUi);
				//print_r($widget);
			}
		}
		if($widget) {
			$ob->kaltura_video_widget_id = $widget->id;
			$ob->kaltura_video_widget_uid = $widget->uiConfId;
			$ob->kaltura_video_widget_html = $widget->widgetHTML;
			//maybe not needed, width/height from entry object?? needs investigation...
			if(preg_match('/width="([0-9]+)"/i',$widget->widgetHTML,$u)) $width = (int) $u[1];
			if(preg_match('/height="([0-9]+)"/i',$widget->widgetHTML,$u)) $height = (int) $u[1];
			$ob->kaltura_video_widget_width = $width;
			$ob->kaltura_video_widget_height = $height;
		}

		//update metadata in kaltura
		try{
			$kentry = new KalturaMixEntry();
			$kentry->name = $entry->name;
			$kentry->description = $entry->description;
			$kentry->tags = $entry->tags;
			$kentry->adminTags = $entry->adminTags;
			$kentry->userId = $entry->userId;
			//TODO: votes, rank, searchText
			$entry = $kmodel->updateMixEntry($entry->id, $kentry);
		}catch(Exception $e) {
			//nothing at the moment
			register_error("ID ".$entry->id.": ".$e->getMessage());
		}
	}

    $ob = handleParams($ob, $params);

	$ob->save();
	return $ob;
}

function handleParams($ob, $params){
    if($params['uploaded_id']){
        $ob->uploaded_id = $params['uploaded_id'];
    }

    //setup the license
    if($params['license']){
        $ob->license = $params['license'];
    }

    //setup the setup thumbnail time
    if($params['thumbnail_sec']){
        $ob->thumbnail_sec = $params['thumbnail_sec'];
    }

    return $ob;
}

//
function kaltura_parse_time($seconds,$complete=true) {
	$d = floor($seconds / (3600*24));
	$h = floor($seconds / 3600) - $d*24;
	$m = floor($seconds / 60) - $h*60 -$d*24*60;
	$s = $seconds % 60;
	//echo "\n[$d $h $m $s]\n";
	if($complete) {
		if(!empty($d)) return sprintf("%dd %d:%02d:%02d",$d,$h,$m,$s);
		elseif(!empty($h)) return sprintf("%d:%02d:%02d",$h,$m,$s);
		elseif(!empty($m)) return sprintf("%d:%02d",$m,$s);
		else return $s."s";
	}
	else {
		$ret = "";
		if(!empty($d)) {
			$ret .= sprintf("%dd %dh",$d,$h);
			if(!empty($m)) $ret .= sprintf(" %02dm",$m);
			if(!empty($s)) $ret .= sprintf(" %02ds",$s);
			return $ret;
		}
		elseif(!empty($h)) {
			$ret .= sprintf("%dh",$h);
			if(!empty($m)) $ret .= sprintf(" %02dm",$m);
			if(!empty($s)) $ret .= sprintf(" %02ds",$s);
			return $ret;
		}
		elseif(!empty($m)) {
			$ret .= sprintf("%dm",$m);
			if(!empty($s)) $ret .= sprintf(" %02ds",$s);
			return $ret;
		}
		else return $s."s";
	}
}

function kaltura_get_plays_count($entry){
	
	return $entry->plays;
	
}

/*
 * Get a list of video objects by most viewed
 */
function archive_kaltura_get_most_viewed($limit = 25, $offset = 0){
	$kmodel = KalturaModel::getInstance();
	$entries = (array) $kmodel->listEntriesbyPlays($limit, $offset);

	foreach($entries["objects"] as $entry){
		$entry_ids[] = $entry->id;
	}
	return $entry_ids;
}

function kaltura_build_widget_object($ob,$widget_html) {
	//echo htmlspecialchars(print_r($widget_html,true));

	preg_match('/width="([0-9]*)"/',$widget_html,$matches);
	$width = $matches[1];
	preg_match('/height="([0-9]*)"/',$widget_html,$matches);
	$height = $matches[1];
	preg_match('/id="([a-zA-Z0-9_-]*)"/',$widget_html,$matches);
	$id = $matches[1];
	preg_match('/data="([a-zA-Z0-9_\:\.\,\/-]*)"/',$widget_html,$matches);
	$swf = $matches[1];
	preg_match('/name="flashVars" value="([a-zA-Z0-9\%\ \&_\:\.\,\/-]*)"/',$widget_html,$matches);
	$flash_vars = $matches[1];

	if(empty($id)) $id = $ob->kaltura_video_id;

    $widget->id = $id;
    $widget->height = $height;
    $widget->width = $width;
    $widget->swf = $swf;
    $widget->flashvars = $flash_vars;
    $widget->thumbnail = $ob->kaltura_video_thumbnail;

    //echo '<br>'.nl2br(htmlspecialchars(print_r($widget,true)));die;

    return $widget;

}

function kaltura_get_error_page($code='',$text = "",$popup=true) {
	global $CONFIG;
	$partner_id = elgg_get_plugin_setting('partner_id', 'archive');
	$subp_id = elgg_get_plugin_setting('subp_id', 'archive');
	$secret = elgg_get_plugin_setting('secret', 'archive');
	$admin_secret = elgg_get_plugin_setting('admin_secret', 'archive');
	$password = elgg_get_plugin_setting('password', 'archive');

	$title = elgg_echo('kalturavideo:error:misconfigured');

	$ret = '';
	$ret .= '<div class="kalturaError">';
	$ret .= "<h2>$title</h2>";

	//typic error when not configured
	if(empty($partner_id) || empty($subp_id) || empty($secret) || empty($admin_secret) || empty($password)) {
		$title = elgg_echo('kalturavideo:error:notconfigured');
		$text = elgg_echo('kalturavideo:error:readme');
	}
	elseif($code == 'MISSING_KS') {
		$text .= '<br /><br />'.elgg_echo('kalturavideo:error:missingks');
	}
	elseif($code == 'UNKNOWN_PARTNER_ID') {
		$text .= '<br /><br />'.elgg_echo('kalturavideo:error:partnerid');
	}
	elseif(empty($code) && empty($text)) {

		$text = "<strong>Kaltura Connection Failed!</strong> Check your internet connection and the availability of kaltura's site.";

		$ret .= '<p><br />'.nl2br($text).'</p>';

		if($popup) {
			$ret .= '<p><a href="#" class="kalturaButton" onclick="finished(0)">'.elgg_echo('kalturavideo:label:closewindow').'</a></p>';
		}
		$ret .= '</div>';
		return $ret;
	}

	$ret .= '<p><br />'.nl2br($text).'</p>';
	if($popup) {
		if(!empty($code)) $ret .= '<p>'.elgg_echo('kalturavideo:label:gotoconfig').' <a href="#" onclick="gotoadmin()">'.elgg_echo('admin').' -> '.elgg_echo('kalturavideo:admin').'</a></p>';
		$ret .= '<p><a href="#" class="kalturaButton" onclick="finished(0)">'.elgg_echo('kalturavideo:label:closewindow').'</a></p>';
	}
	else {
		$ret .= '<p>'.elgg_echo('kalturavideo:label:gotoconfig').' <a href="'.$CONFIG->wwwroot.'pg/kaltura_video_admin/">'.elgg_echo('admin').' -> '.elgg_echo('kalturavideo:admin').'</a></p>';
	}

	$ret .= '</div>';
	return $ret;
}


//
function kaltura_create_generic_widget_html ( $entryId , $size='l' , $monetized = false)
{
	global $KALTURA_GLOBAL_UICONF;
	
	$kaltura_server = elgg_get_plugin_setting('kaltura_server_url',  'archive');
	$partnerId = elgg_get_plugin_setting('partner_id', 'archive');

	if(empty($entryId)) return "Error entryId: $entryId";
    if ( $size == "m" ) {
    	// medium size
    	$height = 225;
    	$width = 400;
    }
	elseif( $size == 'news') {
		$view = $params['view'];
		$context = elgg_get_context();
		if($context == 'news'){
			$height = 295;
    		$width = 515;
		} else {
			$height = 214;
    		$width = 380;
		}
		$flashVars = '&autoPlay=true';
	}elseif($size=='mobile') {
		$height = '250px';//standard
		$width = '100%';
	} elseif(elgg_get_context()=='archive'){
		$height = 410;
    	$width = 730;
		$flashVars = '&autoPlay=true';
	}else{
    	// large size "410", "364"
    	$height = 410;
    	$width = 730;

    }

    $default_player = elgg_get_plugin_setting('defaultplayer', 'archive');

		$widgetUi = elgg_get_plugin_setting('custom_kdp', 'archive');
		
	$viewData = array();
    $viewData["swfUrl"]	= KalturaHelpers::getSwfUrlForBaseWidget($widgetUi);

   
        $kmodel = KalturaModel::getInstance();
   /* try {
        $mediaEntries = $kmodel->listMixMediaEntries($entryId);
    } catch(Exception $e) {
    }
	if(count($mediaEntries) > 0){
	    $mediaEntry = $mediaEntries[0];
	}
	$entryForPlayer = ($mediaEntry ? $mediaEntry->id : $entryId);
	*/
	$entryForPlayer = $entryId;
	//$flashVarsStr = "streamerType=rtmp&streamerUrl=rtmp://rtmpakmi.kaltura.com/ondemand&rtmpFlavors=1&&";
      
    $viewData["flashVars"]["entryId"] = $entryForPlayer;
    $flashVarsStr .= KalturaHelpers::flashVarsToString($viewData["flashVars"]);

//	$flashVars .= '&watermark.watermarkPath=' . elgg_get_site_url() . '/archive/view/' . $entryForPlayer;
	
	$video_location = $kaltura_server . '/index.php/kwidget/wid/_'.$partnerId.'/uiconf_id/' . $widgetUi . '/entry_id/'. $entryForPlayer;
	
	$widget .= '<script type="text/javascript" src="' . $kaltura_server . '/p/'.$partnerId.'/sp/'.$partnerId.'00/embedIframeJs/uiconf_id/'.$widgetUi.'/partner_id/' . $partnerId * 100 . '"></script>';
	 
	//$showAds = 'customAd.path=' . ($monetized ? elgg_get_plugin_setting('adPluginID', 'archive'):'false');

	$widget .= '<object id="kaltura_player_' . $widgetUi .'" class="archive-large-widget" name="kaltura_player_' . $widgetUi . '" type="application/x-shockwave-flash" 
	 xmlns:dc="http://purl.org/dc/terms/" xmlns:media="http://search.yahoo.com/searchmonkey/media/" 
	allowFullScreen="true" allowScriptAccess="always" allowNetworking="all" height="' . $height . '" width="' . $width . '"  resource="' . $video_location . '" data="'. $video_location . '" rel="media:video" wmode="transparent"'.
           
        '<a rel="media:thumbnail" href="' . $kaltura_server . '/p/'.$partnerId.'/sp/' . $partnerId * 100 . '/thumbnail/entry_id/0_l47o3qy5/width/120/height/90/bgcolor/000000/type/2"></a>' .
		'<param name="allowScriptAccess" value="always" />'.
		'<param name="allowNetworking" value="all" />'.
		'<param name="allowFullScreen" value="true" />'.
		'<param name="bgcolor" value="#000000" />'.
		'<param name="wmode" value="transparent" />' .
		'<param name="movie" value="' . $video_location . '"/>'.
    	'<param name="flashVars" value="&{FLAVOR}' . $flashVars . '&'.$showAds.'" />' .
		'<span property="dc:description" content=""></span>
		<span property="media:title" content="video.mov"></span> 
		<span property="media:width" content="' . $width . '"></span>
		<span property="media:height" content="' . $height . '"></span> 
		<span property="media:type" content="application/x-shockwave-flash"></span>' .
	'</object>';
	
	$widget .= elgg_view('share/kaltura', array('widget' => htmlspecialchars($widget))); 

	return $widget ;
}


function kaltura_view_select_privacity($video_id,$access_id,$group_mode=false,$collaborative=false) {
	global $CONFIG;
	$ret = elgg_view('input/access', array('name' => 'access_id', 'class'=>'input-access ajaxprivacity','id'=>'ID'.$video_id, 'value' => $access_id));

	if($group_mode && elgg_get_plugin_setting("alloweditor","archive")!='no') {
		$label = elgg_echo("kalturavideo:text:collaborative");
		$label2 = '<label for="CO'.$video_id.'" title="'.htmlspecialchars($label).'" style="font-size:0.8em;">'.elgg_echo("kalturavideo:label:collaborative").'</label>';
		$ret .= '
		&nbsp; <input id="CO'.$video_id.'" class="input-checkboxes collaborative" type="checkbox" title="'.htmlspecialchars($label).'" name="kaltura_video_collaborative" value="'.$video_id.'"'.($collaborative?' checked="checked"':'').' /><img src="'. $CONFIG->wwwroot .'mod/kaltura_video/kaltura/images/group.png" alt="'. htmlspecialchars(elgg_echo("kalturavideo:text:iscollaborative")). '" style="vertical-align:middle;" /> '.$label2;
	}

	return $ret;
}

function kaltura_get_thumnail($entry_id, $width=100, $height=100, $quality=100, $vid_sec = 0){
	//$ob = kaltura_get_entity($entry_id);
	if($vid_sec == 0){
		$vid_sec = 3;
	}
	if(elgg_get_site_url() == 'http://www.minds.com/'){
		$kaltura_server = 'http://dladfude8tdj2.cloudfront.net';
	} else {
		$kaltura_server = elgg_get_plugin_setting('kaltura_server_url',  'archive');
	}
	$partnerId = elgg_get_plugin_setting('partner_id', 'archive');
	$thumbnail_url = "$kaltura_server/p/$partnerId/thumbnail/entry_id/$entry_id/width/$width/height/$height/quality/$quality/type/3/vid_sec/$vid_sec/";
	return $thumbnail_url;
}



?>
