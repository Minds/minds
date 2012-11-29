<?php


	if (isset($vars['entity'])) {
       $object=$vars['entity'];
       $sqllastaccesku = "SELECT ".$CONFIG->dbprefix."objects_entity.description, ".
			 $CONFIG->dbprefix."objects_entity.guid ".
			 "FROM ( ".$CONFIG->dbprefix."entities ".$CONFIG->dbprefix."entities ".
       "INNER JOIN ".
       $CONFIG->dbprefix."entity_subtypes ".$CONFIG->dbprefix."entity_subtypes ".
       "ON (".$CONFIG->dbprefix."entities.subtype = ".$CONFIG->dbprefix."entity_subtypes.id)) ".
       "INNER JOIN ".
       $CONFIG->dbprefix."objects_entity ".$CONFIG->dbprefix."objects_entity ".
       "ON (".$CONFIG->dbprefix."objects_entity.guid = ".$CONFIG->dbprefix."entities.guid) ".
			 "WHERE ".$CONFIG->dbprefix."entity_subtypes.subtype = 'videowhisper' AND ".
			 $CONFIG->dbprefix."objects_entity.title = '".$object->title."' AND ".
			 "LEFT(".$CONFIG->dbprefix."objects_entity.description,1) = '4' ORDER BY guid DESC LIMIT 1;";
			 
	$lastaccesku = "";
/*	
	if ($rowlastaccesku = get_data_row($sqllastaccesku)) {
		$lastaccesku = explode(":", $rowlastaccesku->description);
	} */
$sqlliveusercount = "SELECT ".$CONFIG->dbprefix."objects_entity.description, ".
			 $CONFIG->dbprefix."objects_entity.guid ".
			 "FROM ( ".$CONFIG->dbprefix."entities ".$CONFIG->dbprefix."entities ".
       "INNER JOIN ".
       $CONFIG->dbprefix."entity_subtypes ".$CONFIG->dbprefix."entity_subtypes ".
       "ON (".$CONFIG->dbprefix."entities.subtype = ".$CONFIG->dbprefix."entity_subtypes.id)) ".
       "INNER JOIN ".
       $CONFIG->dbprefix."objects_entity ".$CONFIG->dbprefix."objects_entity ".
       "ON (".$CONFIG->dbprefix."objects_entity.guid = ".$CONFIG->dbprefix."entities.guid) ".
			 "WHERE ".$CONFIG->dbprefix."entity_subtypes.subtype = 'videowhisper' AND ".
			 $CONFIG->dbprefix."objects_entity.title = '".$object->title."' AND ".
			 "(LEFT(".$CONFIG->dbprefix."objects_entity.description,3) = '4:1' OR ".
       "LEFT(".$CONFIG->dbprefix."objects_entity.description,3) = '5:1') ORDER BY guid DESC;";
	$count = 0;
	$ztime = time();
	$exptime=$ztime-30;
	$users = "";
	
	/*if ($rows = get_data($sqlliveusercount)) {
		foreach($rows as $row) {
			$descriptionx = $row->description; 
			$guid = $row->guid;
			//echo $descriptionx."<br />";
			$nilai = explode(":", $descriptionx);
			$newdescription = "";
			if ($nilai[3] < $exptime) {	// if last access time < exptime
				for ($i = 0; $i <= 2; $i++) {
					if ($i == 1) 
						$newdescription .= "0:"; // set status as 0 ( logout )
					else
						$newdescription .= $nilai[$i].":";
				}
				$newdescription .= $nilai[3];
				$result = update_data("UPDATE {$CONFIG->dbprefix}objects_entity 
				set description='$newdescription' 
				where guid=$guid ;");
			} else {
				$count = $count + 1;
				if ($count <= 5) {
					$users .= $nilai[2].', ';
				}
			}
			
		}
		$users = substr($users, 0, (strlen($users) - 2));
	}*/

	$user_name = $object->getOwnerEntity()->name;
	$url = $object->getURL();
	$link1 = " <a href=\"" . $object->getURL() . "?live=1\">Broadcast</a> | ";
	$link2 = " <a href=\"" . $object->getURL() . "?live=2\">Watch and Chat</a> | ";
	$link3 = " <a href=\"" . $object->getURL() . "?live=3\">Just Watch Video</a>";

	$ver=explode('.', get_version(true));			
	if ($ver[1]>7) {
    $full = elgg_extract('full_view', $vars, FALSE);
    
    if (!$object) {
    	return;
    }
    
    $owner = $object->getOwnerEntity();
    $owner_icon = elgg_view_entity_icon($owner, 'small');
    $container = $object->getContainerEntity();
    $categories = elgg_view('output/categories', $vars);
    
    $owner_link = elgg_view('output/url', array(
    	'href' => "livestreaming/owner/$owner->username",
    	'text' => $owner->name,
    	'is_trusted' => true,
    ));
    $author_text = elgg_echo('byline', array($owner_link));
    
    $tags = elgg_view('output/tags', array('tags' => $object->tags));
    $date = elgg_view_friendly_time($object->time_created);
    
    $comments_count = $object->countComments();
    //only display if there are commments
    if ($comments_count != 0) {
    	$text = elgg_echo("comments") . " ($comments_count)";
    	$comments_link = elgg_view('output/url', array(
    		'href' => $object->getURL() . '#comments',
    		'text' => $text,
    		'is_trusted' => true,
    	));
    } else {
    	$comments_link = '';
    }
    
    $metadata = elgg_view_menu('entity', array(
    	'entity' => $object,
    	'handler' => 'livestreaming',
    	'sort_by' => 'priority',
    	'class' => 'elgg-menu-hz',
    ));
    
    $subtitle = "$author_text $date $comments_link $categories";
    
    // do not show the metadata and controls in widget view
    if (elgg_in_context('widgets')) {
    	$metadata = '';
    }

		$descx = explode("^", $object->description);
  	$excerpt = elgg_get_excerpt($descx[0]);

		if ($count > 4) 
			$excerpt .= "<br />Live users: ".$count."&nbsp;&nbsp;(".$users."+ )";
		elseif ($count > 0)
			$excerpt .= "<br />Live users: ".$count."&nbsp;&nbsp;(".$users.")";
		else
			$excerpt .= "<br />Live users: ".$count;				

		if ($lastaccesku[3])	$excerpt .= ", Last access: " . elgg_view_friendly_time($lastaccesku[3]);

  	$content = $excerpt;

    $roomLink = "[ ";
    if (elgg_is_logged_in()) {
          // get name
      $ElggUser = elgg_get_logged_in_user_entity();
      $username=$ElggUser->get("name");			
			if ($username == $user_name) $roomLink .= " ".$link1;
    }
    $roomLink .= " ".$link2;
    $roomLink .= " ".$link3." ]<br />";

  	$params = array(
  		'entity' => $object,
  		'metadata' => $metadata,
      'title' => $object->title." ".$roomLink,
  		'subtitle' => $subtitle,
  		'tags' => $tags,
  		'content' => $content,
  	);
  	$params = $params + $vars;
  	$body = elgg_view('object/elements/summary', $params);
  	
  	echo elgg_view_image_block($owner_icon, $body);
  } // end elgg 1.8
	else {
	
?>
<div class="livestreaming-singlepage">
	<div class="livestreaming-room">

	    <!-- the actual shout -->
		<div class="room_body">

	    <div class="livestreaming_icon">
	    <?php
		        echo elgg_view("profile/icon", array('entity' => $vars['entity']->getOwnerEntity(), 'size' => 'small'));
	    ?>
	    </div>

			<div class="livestreaming_options">
	    <div class="clearfloat"></div>
	    		<?php

			// if the user looking at livestreaming post can edit, show the delete link
			//echo "can edit: " . $vars['entity']->canEdit();
			if ($vars['entity']->canEdit()) {
					   echo "<div class='delete' style='float:right;'>" . elgg_view("output/confirmlink", array(
															'href' => $vars['url'] . "action/livestreaming/delete?livestreamingroom=" . $vars['entity']->getGUID(),
															'text' => elgg_echo('delete'),
															'confirm' => elgg_echo('deleteconfirm'),
															'text_encode' => false,
														)) . "</div>";

			} //end of can edit if statement
		?>
	    </div>

		<?php
		    echo sprintf(elgg_echo('livestreaming:river:created'),"<b>{$user_name}</b>");
        echo $object->title." live channel.";
        if (isloggedin()) {
          // get name
          $ElggUser=get_loggedin_user();
          $username=$ElggUser->get("name");			
			    if ($username == $user_name) echo " $link1";
        }
        echo " $link2";
        echo " $link3<BR>";
		    $descx = explode("^", $vars['entity']->description);
        $desc = preg_replace('/\@([A-Za-z0-9\_\.\-]*)/i','@<a href="' . $vars['url'] . 'livestreaming/$1">$1</a>',$descx[0]);
  			echo parse_urls($desc);
				if ($count > 4) 
					echo "<br />Live users: ".$count."&nbsp;&nbsp;(".$users."+ )";
				elseif ($count > 0)
					echo "<br />Live users: ".$count."&nbsp;&nbsp;(".$users.")";
				else
					echo "<br />Live users: ".$count;				
				if ($lastaccesku[3]) echo "<br />Last access: ". friendly_time($lastaccesku[3]);
		?>


		<div class="clearfloat"></div>
		</div>
		<div class="room_date">
		&nbsp;&nbsp; <?php
				echo "Created: " . friendly_time($vars['entity']->time_created);
		?>
		</div>


	</div>
</div>
<?php
    } // end elgg 1.7
	}

?>
