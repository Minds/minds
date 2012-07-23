<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once(dirname(__FILE__)."/kaltura/api_client/includes.php");
if(!isadminloggedin()) die('');

$page = intval(get_input('page',0));

$load_text = elgg_echo('kalturavideo:recreate:initiating');
$total = 1;
$page_size = 10;
$continue = $page + 1;
//0 does nothing
if($page > 0) {
	try {
		//open the kaltura list
		$kmodel = KalturaModel::getInstance();
		//we only work with mixing entries
		$results = $kmodel->listMixEntries($page_size, $page);
		$total = $results->totalCount;
		$ok_guids = array();
		if(count($results->objects)>0) {
			foreach($results->objects as $result) {
			/*
			 A object is:
			 *
				[hasRealThumbnail] =>
				[editorType] => 1
				[dataContent] => all this data in a xml text
				[plays] =>
				[views] =>
				[width] =>
				[height] =>
				[duration] => 4
				[id] => entryId
				[name] => New Elgg Video
				[description] =>
				[partnerId] => partner_id
				[userId] => Elgg_user
				[tags] =>
				[adminTags] =>
				[status] => 2
				[type] => 2
				[createdAt] => 1254667488 timestamp
				[rank] => 0
				[totalRank] =>
				[votes] =>
				[groupId] =>
				[partnerData] =>
				[downloadUrl] =>
				[searchText] =>
				[licenseType] => -1
				[version] => 100001
				[thumbnailUrl] => entry thumbnail url

			* */

				//process object
				$prefix = KALTURA_ELGG_USER_PREFIX;

				$body = 'FOUND: <img src="'.$result->thumbnailUrl.'" style="height:20px;vertical-align:middle;" /> '.$result->id.' ('.$result->name.')';

				$user = substr($result->userId,strlen($prefix));
				$groupid = 0;
				if(!$user) {
					//old configuration option
					if(get_plugin_setting('uid_prefix', 'kaltura_video')) {
						$user = substr($result->userId,strlen($prefix));
					}
				}
				$uob = false;

				//check import from kaltura
				$taguser = '';
				$admintags = explode(" ",trim($result->adminTags));
				if($admintags[0] == KALTURA_ADMIN_TAGS) {
					$taguser = $admintags[1];
				}
				$result->adminTags = KALTURA_ADMIN_TAGS;

				foreach(array($taguser,$user) as $u) {
					if(strpos($u,":")!==false) {
						list($u,$groupid) = explode(":",$u);
					}
					if($uob = get_user_by_username($u)) {
						$user = $u;
						break;
					}
				}

				if($uob) {
					$user_guid = $uob->getGUID();
					$container_guid = null;
					//print_r($result);continue;
					$ob = kaltura_get_entity($result->id);

					if($ob->title) $result->name = $ob->title;
					if($ob->description) $result->description = $ob->description;
					if($ob->tags) $result->tags = implode(", ",$ob->tags);

					if($ob) {
						$metadata = kaltura_get_metadata($ob);
						$container_guid = $ob->container_guid;
						$privacy = $ob->access_id;

						//check for duplicates (and delete it)
						$objs = elgg_get_entities_from_metadata(array('metadata_name_value_pairs' => array(
							"kaltura_video_id" => $result->id
							), 'types' => 'object', 'subtypes' => 'kaltura_video'));
						if(count($objs) > 1) {
							foreach($objs as $_ob) {
								delete_entity($_ob->guid);
							}
						}
					}
					else {
						$metadata = false;
						$privacy = null;
					}

					//remove all metadata (to clean old configs not needed)
					if($metadata) {
						foreach($metadata as $name => $value) {
							//do no remove this id because the kaltura_update_object needs this
							if($name == 'kaltura_video_id') continue;
							remove_metadata($ob->guid,$name);
						}
					}

					//privacity
					////////////////////////////////////////////
					//for compatability with old versions,
					//will not used in future because Elgg provides more complets types of privacity now
					if($metadata->kaltura_video_privacity == 'loggedin') {
						$privacy = ACCESS_LOGGED_IN;
					}
					if($metadata->kaltura_video_privacity == 'public') {
						$privacy = ACCESS_PUBLIC;
					}
					if($metadata->kaltura_video_privacity == 'friends') {
						$privacy = ACCESS_FRIENDS;
					}
					if($metadata->kaltura_video_privacity == 'thisgroup') {
						$group = get_entity($ob->container_guid);
						if ($group instanceof ElggGroup) {
							$ob->kaltura_video_isgroup = 1;
							if($group->group_acl) {
								$privacy = $group->group_acl;
								$ob->kaltura_video_group_visible = 1;
							}
						}
					}
					////////////////////////////////////////////

					//get the current container
					$user_ID = KALTURA_ELGG_USER_PREFIX.$user;
					if($groupid) {
						$user_guid = $uob->getGUID();
						$container_guid = $uob->getGUID();
						if($groupid) {
							$group = get_entity($groupid);
							if($group instanceof ElggGroup) {
								$container_guid = $group->getGUID();
								$user_ID .= ":".$groupid;
							}
						}
					}

					//create new object
					$result->userId = $user_ID;

					try {
						$kmodel = KalturaModel::getInstance();
					}
					catch(Exception $e) {
						$body .= ' ERROR: '.$e->getMessage();
						continue;
					}
					//$body .= print_r($result,1).print_r($kmodel->client->user->getConfig(),1);
					$ob = kaltura_update_object($result,$kmodel,$privacy,$user_guid,$container_guid);
					if(!$ob) {
						die("Fatal error while creating new object, ABORTING\n");
					}

					//create metadata
					if($metadata) {
						/*
						//If this is uncommented that will mantain the old players
						//Otherwise (commented) the player will be recreated to the plugin admin definitions
						if($metadata->kaltura_video_widget_id) {
							$ob->kaltura_video_widget_id = $metadata->kaltura_video_widget_id;
							$ob->kaltura_video_widget_uid = $metadata->kaltura_video_widget_uid;
							$ob->kaltura_video_widget_width = $metadata->kaltura_video_widget_width;
							$ob->kaltura_video_widget_height = $metadata->kaltura_video_widget_height;
							$ob->kaltura_video_widget_html = $metadata->kaltura_video_widget_html;
						}
						* */
						if($metadata->kaltura_video_collaborative) $ob->kaltura_video_collaborative = $metadata->kaltura_video_collaborative;
						if($metadata->kaltura_video_comments_on) $ob->kaltura_video_comments_on = $metadata->kaltura_video_comments_on;
						if($metadata->kaltura_video_rating_on) $ob->kaltura_video_rating_on = $metadata->kaltura_video_rating_on;
						//echo htmlspecialchars(print_r($metadata,true));
					}

					if($ob->save()) {
						$ok_guids[] = $ob->guid;
					}
					else {
						die("Fatal error while saving metadata or object, ABORTING\n");
					}

					$body .= ' <strong style="color:#0b0;">'.$result->userId.' imported to '.$user.($groupid?" group $groupid":'').'.</strong>';
				}
				else {
					$body .= ' <strong style="color:#b00;">Not imported!</strong> (Expected user: '.$u.')';
				}

				$body .= "<br />\n";
				echo $body;
			}

			echo "<p>".str_replace(array("%NUMRANGE%","%TOTAL%"),array( (($page-1)*$page_size+1) . "-" . (count($results->objects)+($page-1)*$page_size),$total),elgg_echo('kalturavideo:recreate:processedvideos')).'</p>';
		}
		else $continue = 'end';
	}
	catch(Exception $e) {
		$error = $e->getMessage();
		echo $error;
	}
	$load_text = str_replace(array("%NUM%","%TOTAL%"),array($page,ceil($total/$page_size)),elgg_echo('kalturavideo:recreate:stepof'));
}

//show the next loading until the end
echo '<div class="loaded" rel="'.$continue.'" style="height:20px;width:100%;background:url('.$CONFIG->wwwroot.'mod/kaltura_video/kaltura/editor/images/loadingAnimation.gif) no-repeat center left;padding:1px 1px 1px 215px;font-style:italic;">'.$load_text.'</div>';

?>
