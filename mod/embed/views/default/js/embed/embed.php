elgg.provide('elgg.embed');

elgg.embed.init = function() {

	// inserts the embed content into the textarea
	$(document).on('click', ".embed-item", elgg.embed.insert);

	// caches the current textarea id
	$(document).on('click', ".embed-control", function() {
		var classes = $(this).attr('class');
		var embedClass = classes.split(/[, ]+/).pop();
		var textAreaId = embedClass.substr(embedClass.indexOf('embed-control-') + "embed-control-".length);
		elgg.embed.textAreaId = textAreaId;
	});

	// special pagination helper for lightbox
	$(document).on('click', '.embed-wrapper .elgg-pagination a', elgg.embed.forward);

	$(document).on('click', '.embed-section', elgg.embed.forward);

	$(document).on('submit', '.elgg-form-embed', elgg.embed.submit);
	$(document).on('submit', '.elgg-form-embed-youtube', elgg.embed.submityt);
};

/**
 * Inserts data attached to an embed list item in textarea
 *
 * @todo generalize lightbox closing
 *
 * @param {Object} event
 * @return void
 */
elgg.embed.insert = function(event) {
	var textAreaId = elgg.embed.textAreaId;
	var textArea = $('#' + textAreaId);

	// generalize this based on a css class attached to what should be inserted
	var content = ' ' + $(this).find(".embed-insert").parent().html() + ' ';

	// this is a temporary work-around for #3971
	if (content.indexOf('thumbnail.php') != -1) {
		content = content.replace('size=small', 'size=large');
	}

	/*
	 * Make photos look good
	 */
	if($(this).find('.elgg-photo')){
		content = content.replace('/small/', '/large/');
	}

	/*
	 * Make videos play.
 	 */
	if($(this).find('.minds-archive-video').length >= 1){
		$.ajax({ url:   $(this).find('.minds-archive-video').attr('source'),
				async: false,
				success: function(data) {
					content = data;
					content = content.replace('width="515"','width="730px"');
					content = content.replace('height="295"','height="410px"');
				}
		});
	}

	textArea.val(textArea.val() + content);
	textArea.focus();
	
<?php
// See the TinyMCE plugin for an example of this view
echo elgg_view('embed/custom_insert_js');
?>

	$.fancybox.close();

	event.preventDefault();
};

/**
 * Submit an upload form through Ajax
 *
 * Requires the jQuery Form Plugin. Because files cannot be uploaded with
 * XMLHttpRequest, the plugin uses an invisible iframe. This results in the
 * the X-Requested-With header not being set. To work around this, we are
 * sending the header as a POST variable and Elgg's code checks for it in
 * elgg_is_xhr().
 *
 * @param {Object} event
 * @return bool
 */
elgg.embed.submit = function(event) {
	$('.embed-wrapper .elgg-form-embed').hide();
	$('.embed-throbber').show();
	
	$(this).ajaxSubmit({
		dataType : 'json',
		data     : { 'X-Requested-With' : 'XMLHttpRequest'},
		success  : function(response, data) {console.log(data);
			if (response) {
				if (response.system_messages) {
					elgg.register_error(response.system_messages.error);
					elgg.system_message(response.system_messages.success);
				}
				if (response.status >= 0) {
					var forward = $('input[name=embed_forward]').val();
					var url = elgg.normalize_url('embed/tab/' + forward);
					url = elgg.embed.addContainerGUID(url);
					$('.embed-wrapper').parent().load(url);
				} else {
					// incorrect response, presumably an error has been displayed
					$('.embed-throbber').hide();
					$('.embed-wrapper .elgg-form-embed').show();
				}
			}
		},
		error    : function(xhr, status) {
			// @todo nothing for now
		}
	});

	// this was bubbling up the DOM causing a submission
	event.preventDefault();
	event.stopPropagation();
};

/**
 * Submit youtube embed form and insert
 *
 * @param {Object} event
 * @return bool
 */
elgg.embed.submityt = function(event) {
	console.log('triggered');
	$('.embed-wrapper .elgg-form-embed-youtube').hide();
	$('.embed-throbber').show();
	
	$(this).ajaxSubmit({
		dataType : 'json',
		data     : { 'X-Requested-With' : 'XMLHttpRequest'},
		success  : function(response, data) {
			if (response) {
				if (response.system_messages) {
					elgg.register_error(response.system_messages.error);
					elgg.system_message(response.system_messages.success);
				}
				if (response.status >= 0) {
					
					var textAreaId = elgg.embed.textAreaId;
					var textArea = $('#' + textAreaId);
					
					//tinyMCE.execCommand('mceRemoveControl', false, textAreaId);
			
					tinyMCE.activeEditor.setContent(textArea.val() + response.output);
					textArea.val(textArea.val() + response.output);
					textArea.focus();
				
														
					$.fancybox.close();
					
				} else {
					// incorrect response, presumably an error has been displayed
					$('.embed-throbber').hide();
					$('.embed-wrapper .elgg-form-embed-youtube').show();
				}
			}
		},
		error    : function(xhr, status) {
			// @todo nothing for now
		}
	});

	// this was bubbling up the DOM causing a submission
	event.preventDefault();
	event.stopPropagation();
};

/**
 * Loads content within the lightbox
 *
 * @param {Object} event
 * @return void
 */
elgg.embed.forward = function(event) {
	// make sure container guid is passed
	var url = $(this).attr('href');
	url = elgg.embed.addContainerGUID(url);

	$('.embed-wrapper').parent().load(url);
	event.preventDefault();
};

/**
 * Adds the container guid to a URL
 *
 * @param {string} url
 * @return string
 */
elgg.embed.addContainerGUID = function(url) {
	if (url.indexOf('container_guid=') == -1) {
		var guid = $('input[name=embed_container_guid]').val();
		return url + '?container_guid=' + guid;
	} else {
		return url;
	}
};

elgg.register_hook_handler('init', 'system', elgg.embed.init);
