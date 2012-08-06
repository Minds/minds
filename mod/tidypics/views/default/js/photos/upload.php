<?php
/**
 *
 */

$maxfilesize = (int) elgg_get_plugin_setting('maxfilesize', 'tidypics');

?>

elgg.provide('elgg.tidypics.upload');

elgg.tidypics.upload.init = function() {
	
	window.locale = {
		"fileupload": {
			"error": elgg.echo('tidypics:upload:error'),
			"errors": {
				"maxFileSize": elgg.echo('tidypics:upload:maxfilesize'),
				"minFileSize": elgg.echo('tidypics:upload:minfilesize'),
				"acceptFileTypes": elgg.echo('tidypics:upload:acceptfiletypes'),
				"maxNumberOfFiles": elgg.echo('tidypics:upload:maxnumberoffiles'),
			},
		}
	};
	
	$.widget('blueimpJUI.fileupload', $.blueimpUI.fileupload, {
		_transition: function (node) {
			var that = this,
				deferred = $.Deferred();
			if (node.hasClass('fade')) {
				node.fadeToggle(function () {
					deferred.resolveWith(node);
				});
			} else {
				deferred.resolveWith(node);
			}
			return deferred;
		},
	});

	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload();

	// Settings
	$('#fileupload').fileupload('option', {
		maxFileSize: <?php echo ($maxfilesize * 1024)*1024;?>,
		acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		change: function() {
			elgg.tidypics.upload.fileinput.hide().appendTo($('#fileupload'));
			elgg.tidypics.upload.fileinput = $('#fileupload .elgg-input-file');
		},
		drop: function () {
			return false;
		},
  
	});
	
	elgg.tidypics.upload.fileinput = $('#fileupload .elgg-input-file');
};

elgg.register_hook_handler('init', 'system', elgg.tidypics.upload.init);
