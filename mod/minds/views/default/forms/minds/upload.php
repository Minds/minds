<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}

?>
<script>
		var progressbox     = $('.progressbox');
        var progressbar     = $('.progressbar');
        var statustxt       = $('.statustxt');
        var submitbutton    = $(".elgg-button-submit");
        var myform          = $(".elgg-form-minds-upload");
        var output          = $("#output");
        var completed       = '0%';
        
        //test if the browser support xmlhttprequest
        var xhr = new XMLHttpRequest();
        if(xhr && ('upload' in xhr)){
 
                $(myform).ajaxForm({
                    beforeSend: function() { //brfore sending form
                        submitbutton.remove(); // disable upload button
                        statustxt.slideDown();
                        progressbox.show(); //show progressbar
                        progressbar.width(completed); //initial value 0% of progressbar
                        statustxt.html(completed); //set status text
                        statustxt.css('color','#000'); //initial color of status text
                    },
                    uploadProgress: function(event, position, total, percentComplete) { //on progress
                        progressbar.width(percentComplete + '%') //update progressbar percent complete
                        statustxt.html(percentComplete + '%'); //update status text
                        if(percentComplete>50)
                            {
                                //statustxt.css('color','#fff'); //change status text to white after 50%
                            }
                        if(percentComplete==100)
                        	{
                        		statustxt.html('Upload complete. Please wait until the upload is processed. This could take a few moments depending on the file size.')
                        	}
                        },
                    complete: function(response) { // on complete
                    	//console.log(response);
                        elgg.forward('/archive/owner/' + elgg.get_logged_in_user_entity().username);
                        myform.resetForm();  // reset form
                        submitbutton.removeAttr('disabled'); //enable submit button
                        progressbox.slideUp(); // hide progressbar
                    }
            });
        } else {
        	progressbox.hide();
        }
</script>
<div>
	<label><?php echo elgg_echo('minds:upload:file'); ?></label><br />
	<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
</div>
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>
<div>
	<label><?php echo elgg_echo('minds:license:label'); ?></label>
    <?php echo elgg_view('input/licenses', array(	'name' => 'license' ));?>
</div>                                                             															
<?php

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

?>
<div>
	<label><?php echo elgg_echo('access'); ?></label>
	<?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</div>
<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => elgg_echo('upload')));

?>
<div class="progressbox"><div class="progressbar"></div ><div class="statustxt">0%</div ></div>

</div>
