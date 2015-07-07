<?php
elgg_load_js('jquery.autosize');

$user = elgg_get_logged_in_user_entity();

if (!$user) {
	return false;
}

echo elgg_view('input/plaintext', array('name'=>'message', 'id'=>'post-input-box', 'placeholder' => elgg_echo('Message')));


/*echo <<<HTML
	<div class="post-scheduler-button">&#xe801;</div>
	<div class="post-scheduler-content">
		$date $time
	</div>
HTML;*/

echo <<<HTML
	<div id="post-preview" class="post-post-preview">
			<img id="photopreview" class="post-post-preview-icon-img"/>
		<input type="text" name="title" class="post-post-preview-title"/>
		<textarea type="text" name="description" class="post-post-preview-description"></textarea>
		<input type="hidden" name="thumbnail" class="post-post-preview-icon"/>
		<input type="hidden" name="url" class="post-post-url"/>
	</div>
HTML;



$file_input = elgg_view('input/file', array('name'=>'attachment', 'class'=>'post-attachment-button', 'accept' => "image/*;capture=camera", 'onchange'=>"photoPreview(this)"));

echo <<<HTML
	<div class="post-attachment-button-override">
		$file_input
	</div>
HTML;

$date = elgg_view('input/date', array('name'=>'schedule_date','value'=>time()));
$time = elgg_view('input/timepicker',array('name'=>'schedule_time','value'=> (time() - strtotime("today")) /60));

echo <<<HTML
	<div class="upload-progress" style="float: right;height: 25px;width: 162px;margin: 8px;">
		<div class="percent" style="width:0;height:25px;background:blue;"></div>
	</div>
HTML;

echo elgg_view('input/hidden', array('name'=>'to_guid', 'value'=>elgg_extract('to_guid', $vars, $user->guid)));
echo elgg_view('input/hidden', array('name'=>'container_guid', 'value'=>elgg_extract('container_guid', $vars)));
echo elgg_view('input/hidden', array('name'=>'access_id', 'value' => elgg_extract('access_id', $vars, ACCESS_PUBLIC)));

echo elgg_view('input/submit', array('value'=>'Post'));
?>

<script>
    $( document ).ready(function() {
        $('#post-input-box').autosize();
    });
    function photoPreview(input) {

	if (input.files && input.files[0]) {
	    var reader = new FileReader();

	    reader.onload = function (e) {
		$('#post-preview').show();
		$('#photopreview').attr('src', e.target.result);
	    }

	    reader.readAsDataURL(input.files[0]);
	}
    }
</script>
