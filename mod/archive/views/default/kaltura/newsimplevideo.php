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

	$modal_view = $vars["modal_view"];
?>

<?php

    if (isset($vars['entity'])) {

	$title = sprintf(elgg_echo("kalturavideo:label:adminvideos"),$object->title);
	$action = "kaltura_video/update";
	$title = $vars['entity']->title;
	$body = $vars['entity']->description;
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

	// set the required variables
	$title_label = elgg_echo('title');
	$title_textbox = elgg_view('input/text', array('internalname' => 'title', 'value' => $title));
	$text_label = elgg_echo('description');
	if($modal_view) {
	    $text_textarea = '<textarea class="input-textarea" name="description"></textarea>';
	}
	else {
	    $text_textarea = elgg_view('input/longtext', array('internalname' => 'description', 'value' => $body));
	}

	$file_input = elgg_view('input/file', array('internalname' => 'fileData', 'value' => ''));
	$access_label = elgg_echo('access');
	$publish = elgg_echo('save');
	$privacy = elgg_echo('access');
	$access_input = elgg_view('input/access', array('internalname' => 'access_id', 'value' => $access_id));
	$issimplevideo_input = elgg_view('input/hidden', array('internalname' => 'is_simple_video', 'value' => '1'));
	$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

    $allowcomments = elgg_echo('kalturavideo:comments:allow');
    $allowrating = elgg_echo('kalturavideo:rating:allow');
    $allowcollaborate = elgg_echo('kalturavideo:label:collaborative');

    if(get_entity($vars['entity']->container_guid) instanceof ElggGroup) {
        $collaborate_part = '<p><label><input type="checkbox" name="collaborate_select" '.$collaborate_on_switch.' /><img src="'. $CONFIG->wwwroot .'mod/kaltura_video/kaltura/images/group.png" alt="'. htmlspecialchars(elgg_echo("kalturavideo:text:iscollaborative")). '" style="vertical-align:middle;" /> '.$allowcollaborate.'</label></p>';
    }
    else {
        $collaborate_part = '';
    }

    //rating part
    $rating_part = "<p><label><input type=\"checkbox\" name=\"rating_select\"  {$rating_on_switch} /> {$allowrating}</label></p>";
    if(get_plugin_setting("enablerating","kaltura_video") == 'no') {
        $rating_part = '';
        $collaborate_part = '';
    }

    // INSERT EXTRAS HERE
    $extras = elgg_view('categories',$vars);
    if (!empty($extras)) $extras = '<div id="kaltura_edit_sidebar">' . $extras . '</div>';
    }
?>

<?php

    $entity_hidden = elgg_view('input/hidden', array('internalname' => 'kaltura_video_id', 'value' => $metadata->kaltura_video_id));
    if($modal_view) {
        $simple_video_creator_modal_hidden = elgg_view('input/hidden', array('internalname' => 'simple_video_creator_modal', 'value' => 1));
    }
	//to no update the river if it's a new object (it has done before)
	if($is_new_object) {
		$entity_hidden .= elgg_view('input/hidden', array('internalname' => 'do_no_add_toriver', 'value' => 1));
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
	    <p class='longtext_editarea'>
			<label>Upload Video:</label><br />
			$file_input
        </p>
        <p>
        <div class="allow_comments">
                <p><label><input type="checkbox" name="comments_select"  {$comments_on_switch} /> {$allowcomments}</label></p>
                $rating_part
                $collaborate_part
            </div>
        </p>
		<p>
			<label>$access_label</label><br />
                        $access_input
		</p>
		<p>
			$entity_hidden
			$simple_video_creator_modal_hidden
			$issimplevideo_input
			<div class="publish_kaltura">
				{$submit_input}
			</div>
		</p>
    {$extras}
    <div class="clearfloat"></div><!-- /two_column_left_sidebar_maincontent -->
    <script type="text/javascript">
        $(".input-file").change(function() {
            $('input[name="title"]').val($(this).val().replace(/C:\\\\fakepath\\\\/i, ''));
        });
    </script>
EOT;

    $form_html = elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body, 'internalid' => 'kalturaPostForm', 'enctype' => 'multipart/form-data'));
    if($modal_view) {
	$form_html = '<div style="margin: 5px">' . $form_html . '</div>';
    }
    echo $form_html;

?>
