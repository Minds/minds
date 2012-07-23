<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/kaltura/api_client/includes.php");

	global $SKIP_KALTURA_REWRITE;
	//this is to avoid the embed video over the longtext box
	$SKIP_KALTURA_REWRITE = true;

	$is_new_object = get_input('entryid',0);

	// Set title, form destination
		if (isset($vars['entity'])) {

			$title = sprintf(elgg_echo("kalturavideo:label:adminvideos"),$object->title);
			$action = "kaltura_video/update";
			$title = $vars['entity']->title;
			$body = $vars['entity']->description;
			$license = $vars['entity']->license;
			$tags = $vars['entity']->tags;
			$access_id = $vars['entity']->access_id;
			if($is_new_object && $access_id==ACCESS_PRIVATE) {
				$access_id = get_default_access();
				$vars['entity']->access_id = $access_id;
				$vars['entity']->save();
			}

			$metadata = kaltura_get_metadata($vars['entity']);

			if ($metadata->kaltura_video_comments_on == 'Off') {
				$comments_on = false;
			} else {
				$comments_on = true;
			}
			if ($metadata->kaltura_video_rating_on == 'Off') {
				$rating_on = false;
			} else {
				$rating_on = true;
			}
			if ($metadata->kaltura_video_cancollaborate) {
				$collaborate_on = true;
			}
			else {
				$collaborate_on = false;
			}

		} else  {
			forward();
		}

		// set the required variables
		$title_label = elgg_echo('title');
		$title_textbox = elgg_view('input/text', array('name' => 'title', 'value' => $title));
		$text_label = elgg_echo('description');
		$text_textarea = elgg_view('input/longtext', array('name' => 'description', 'value' => $body));
		$license_label = elgg_echo('kalturavideo:license:label');
		$license_dropdown = elgg_view('input/dropdown', array(	'name' => 'license',
																'id' => 'license',
																'options_values' => array(
																	'cca' => elgg_echo('kalturavideo:license:cca'),
																	'ccs' => elgg_echo('kalturavideo:license:ccs'),
																	'gpl' => elgg_echo('kalturavideo:license:gpl')
																	),
																'value' => $license
																));
		$tag_label = elgg_echo('tags');
		$tag_input = elgg_view('input/tags', array('name' => 'tags', 'value' => $tags));
		$access_label = elgg_echo('access');

		if($comments_on)
			$comments_on_switch = "checked=\"checked\"";
		else
			$comment_on_switch = "";
		if($rating_on)
			$rating_on_switch = "checked=\"checked\"";
		else
			$rating_on_switch = "";

		if($collaborate_on)
			$collaborate_on_switch = "checked=\"checked\"";
		else
			$collaborate_on_switch = "";

		$thumb = '<img style="width:200px;" src="' . $metadata->kaltura_video_thumbnail . '" alt="" title="' . htmlspecialchars($vars['entity']->title) . '" />';

		$access_input = elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));
		$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));
		$publish = elgg_echo('save');
		$cat = elgg_echo('categories');
		$privacy = elgg_echo('access');
		$allowcomments = elgg_echo('kalturavideo:comments:allow');
		$allowrating = elgg_echo('kalturavideo:rating:allow');
		$allowcollaborate = elgg_echo('kalturavideo:label:collaborative');

		//collaborate part
		if(get_entity($vars['entity']->container_guid) instanceof ElggGroup) {
			$collaborate_part = '<p><label><input type="checkbox" name="collaborate_select" '.$collaborate_on_switch.' /><img src="'. $CONFIG->wwwroot .'mod/kaltura_video/kaltura/images/group.png" alt="'. htmlspecialchars(elgg_echo("kalturavideo:text:iscollaborative")). '" style="vertical-align:middle;" /> '.$allowcollaborate.'</label></p>';
		}
		else {
			$collaborate_part = '';
		}

		//rating part
		$rating_part = "<p><label><input type=\"checkbox\" name=\"rating_select\"  {$rating_on_switch} /> {$allowrating}</label></p>";
		if(elgg_get_plugin_setting("enablerating","kaltura_video") == 'no') {
			$rating_part = '';
			$collaborate_part = '';
		}

		// INSERT EXTRAS HERE
		$extras = elgg_view('categories',$vars);
		if (!empty($extras)) $extras = '<div id="kaltura_edit_sidebar">' . $extras . '</div>';

?>

<?php

	$form_body = <<<EOT

	<div id="two_column_left_sidebar_210">

    	<div id="kaltura_edit_sidebar">
			<div id="content_area_user_title">
			{$thumb}
			</div>

			<div class="kaltura_access">
				<p>{$privacy}: {$access_input}</p>
			</div>

			<div class="allow_comments">
				<p><label><input type="checkbox" name="comments_select"  {$comments_on_switch} /> {$allowcomments}</label></p>
				$rating_part
				$collaborate_part
			</div>
			<div class="publish_kaltura">
				{$submit_input}
			</div>
		</div>

		{$extras}

		$container

	</div><!-- /two_column_left_sidebar_210 -->

	<!-- main content -->
	<div id="two_column_left_sidebar_maincontent">
EOT;

?>

<?php

	$entity_hidden = elgg_view('input/hidden', array('name' => 'kaltura_video_id', 'value' => $metadata->kaltura_video_id));
	
	$uploaded_video = elgg_view('input/hidden', array('name' => 'kaltura_uploaded_video_id', 'value' => get_input('uploaded_video_id')));
	
	
	//to no update the river if it's a new object (it has done before)
	if($is_new_object) {
		$entity_hidden .= elgg_view('input/hidden', array('name' => 'do_no_add_toriver', 'value' => 1));
	}

	$form_body .= <<<EOT
		<p>
			<label>$title_label</label><br />
                        $title_textbox
		</p>
		<p class='longtext_editarea'>
			<label>$text_label</label><br />
                        $text_textarea
		</p>
		<p>
			<label>$license_label</label><br />
                        $license_dropdown
		</p>
		<p>
			<label>$tag_label</label><br />
                        $tag_input
		</p>
		<!-- <p>
			<label>$access_label</label><br />
                        $access_input
		</p> -->
		<p>
			$entity_hidden
			$uploaded_video
			<!-- $submit_input -->
		</p>
	</div><div class="clearfloat"></div><!-- /two_column_left_sidebar_maincontent -->
EOT;

      echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body, 'id' => 'kalturaPostForm'));
?>
