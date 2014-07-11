<?php
/**
 * Form for editing UPLOADED content
 */


$entity = elgg_extract('entity', $vars);

$title = $entity->title;
$body = $entity->description;
$license = $entity->license;
$tags = $entity->tags;
$access_id = $entity->access_id;

// set the required variables
$title_label = elgg_echo('title');
$title_textbox = elgg_view('input/text', array('name' => 'title', 'value' => $title));

$description_label = elgg_echo('description');
$description_textarea = elgg_view('input/longtext', array('name' => 'description', 'value' => $body));

$license_label = elgg_echo('minds:license:label');
$license_dropdown =  elgg_view('input/licenses', array(	'name' => 'license', 'value' => $license ));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array('name' => 'tags', 'value' => $tags));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));
	
// INSERT EXTRAS HERE
$category_label = elgg_echo('categories');
$categories = elgg_view('categories',$vars);
	
$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));

$guid = elgg_view('input/hidden', array('name' => 'guid', 'value' => $entity->guid));
	
	
switch($entity->getSubtype()){

	case 'video':
	
		$thumbnail_input = elgg_view('input/thumbnail_picker', array('entity'=>$entity));
		$thumb = '<img style="width:200px;" src="' . $entity->kaltura_video_thumbnail . '" alt="" title="' . htmlspecialchars($entity->title) . '" />';
	
		$video_id_input = elgg_view('input/hidden', array('name'=>'video_id', 'value'=>$entity->kaltura_video_id));


		$form_body = <<<EOT
			<p>
				<label>$title_label</label><br />
	                        $title_textbox
			</p>
			<p>
				<label>$description_label</label>
	                        $description_textarea
			</p>
			<p>
				<label>$license_label</label>
	                        $license_dropdown
			</p>
			<p>
					$categories
			<p>
			<p>
				<label>$access_label</label>
	                        $access_input
			</p>
				<label>$tag_label</label><br />
	                        $tag_input
			</p>
			</p>
				<label>$thumbnail_label</label><br />
	                        $thumbnail_input
			</p>
			<p>
				$guid
				$submit_input
				$video_id_input
			</p>
		<div class="clearfloat"></div>
EOT;
	
		break;
	case 'file':	
		$file_replace_label = elgg_echo('minds:archive:file:replace');
		$file_replace_input = elgg_view('input/file', array('name' => 'upload')); 
		$form_body = <<<EOT
			<p>
				<label>$title_label</label><br />
	                        $title_textbox
			</p>
			<p>
				<label>$file_replace_label</label>
							$file_replace_input
			<p>
				<label>$description_label</label>
	                        $description_textarea
			</p>
			<p>
				<label>$license_label</label>
	                        $license_dropdown
			</p>
			<p>
					$categories
			<p>
			<p>
				<label>$access_label</label>
	                        $access_input
			</p>
				<label>$tag_label</label><br />
	                        $tag_input
			</p>
			<p>
				$guid
				$submit_input
			</p>
		<div class="clearfloat"></div>
EOT;
		break;
	case 'album':
	case 'image':
		$form_body = <<<EOT
			<p>
				<label>$title_label</label><br />
	                        $title_textbox
			</p>
			<p>
				<label>$description_label</label>
	                        $description_textarea
			</p>
			<p>
				<label>$license_label</label>
	                        $license_dropdown
			</p>
			<p>
					$categories
			<p>
			<p>
				<label>$access_label</label>
	                        $access_input
			</p>
				<label>$tag_label</label><br />
	                        $tag_input
			</p>
			<p>
				$guid
				$submit_input
			</p>
		<div class="clearfloat"></div>
EOT;
		break;
}


echo $form_body;



