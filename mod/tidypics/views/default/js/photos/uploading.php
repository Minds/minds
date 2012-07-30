<?php
/**
 * AJAX uploading
 */
?>

//<script>
elgg.provide('elgg.tidypics.uploading');

elgg.tidypics.uploading.init = function() {

	var fields = ['Elgg', 'user_guid', 'album_guid', 'batch', 'tidypics_token'];
	var data = elgg.security.token;

	$(fields).each(function(i, name) {
		var value = $('input[name=' + name + ']').val();
		if (value) {
			data[name] = value;
		}
	});

	$("#uploadify").uploadify({
		'uploader'     : elgg.config.wwwroot + 'mod/tidypics/vendors/uploadify/uploadify.swf',
		'script'       : elgg.config.wwwroot + 'action/photos/image/ajax_upload',
		'cancelImg'    : elgg.config.wwwroot + 'mod/tidypics/vendors/uploadify/cancel.png',
		'fileDataName' : 'Image',
		'multi'        : true,
		'auto'         : false,
		'wmode'        : 'transparent',
		'buttonImg'    : " ",
		'height'       : $('#tidypics-choose-button').height(),
		'width'        : $('#tidypics-choose-button').width(),
		'scriptData'   : data,
		'onEmbedFlash' : function(event) {
			// @todo This is supposed to mimick hovering over the link.
			// hover events aren't firing for the object.
			$("#" + event.id).hover(
				function(){
					$("#tidypics-choose-button").addClass('tidypics-choose-button-hover');
				},
				function(){
					$("#tidypics-choose-button").removeClass('tidypics-choose-button-hover');
				}
			);
		},
		'onSelectOnce'  : function() {
			$("#tidypics-upload-button").removeClass('tidypics-disable');
		},
		'onAllComplete' : function() {
			// @todo they can keep adding pics if they want. no need to disable this.
			$("#tidypics-choose-button").addClass('tidypics-disable');
			$("#tidypics-upload-button").addClass('tidypics-disable').die();
			$("#tidypics-describe-button").removeClass('tidypics-disable');

			elgg.action('photos/image/ajax_upload_complete', {
				data: {
					album_guid: data.album_guid,
					batch: data.batch
				},
				success: function(json) {
					var url = elgg.normalize_url('photos/edit/' + json.batch_guid)
					$('#tidypics-describe-button').attr('href', url);
				}
			});
		},
		'onComplete'    : function(event, queueID, fileObj, response) {
			// check for errors here
			if (response != 'success') {
				$("#uploadify" + queueID + " .percentage").text(" - " + response);
				$("#uploadify" + queueID).addClass('uploadifyError');
			}
			$("#uploadify" + queueID + " > .cancel").remove();
			return false;
		},
		'onCancel'      : function(event, queueID, fileObj, data) {
			if (data.fileCount == 0) {
				$("#tidypics-upload-button").addClass('tidypics-disable');
			}
		},
		'onError' : function (event, ID, fileObj, errorObj) {
			// @todo do something useful with the limited information in the errorObj.
		}

	});

	// bind to upload button
	$('#tidypics-upload-button').live('click', function(e) {
		var $uploadify = $('#uploadify');
		$uploadify.uploadifyUpload();
		e.preventDefault();
	});
}

elgg.register_hook_handler('init', 'system', elgg.tidypics.uploading.init);