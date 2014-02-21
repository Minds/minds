<?php
/**
 * Photo tagging JavaScript
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

?>
//<script>
elgg.provide('elgg.tidypics.tagging');

// Image scale (should be 1 by default)
elgg.tidypics.tagging.scale = 1;

/**
 * Init all tagging events
 */
elgg.tidypics.tagging.init = function() {
	elgg.tidypics.tagging.active = false;
	$('[rel=photo-tagging]').click(elgg.tidypics.tagging.start);

	$('#tidypics-tagging-quit').click(function(event) {
		elgg.tidypics.tagging.stop();
		event.preventDefault();
	});

	$('.tidypics-tag').each(elgg.tidypics.tagging.position);

	$('a._tp-people-tag-remove').click(elgg.tidypics.tagging.peopleTagRemoveClick);

	elgg.tidypics.tagging.tag_hover = false;
	elgg.tidypics.tagging.toggleTagHover();


	$('a._tp-people-tag-link').hover(
		function() {
			code = $(this).attr('id').substr(9);
			$('#tag-id-' + code).parent().show();
		},
		function() {
			code = $(this).attr('id').substr(9);
			$('#tag-id-' + code).parent().hide();
	});
};

/**
 * Unbind all tagging events
 */
elgg.tidypics.tagging.destroy = function() {
	if (elgg.tidypics.tagging.active) {
		elgg.tidypics.tagging.stop();	
	}

	// Unbind events
	$('[rel=photo-tagging]').unbind('click');
	$('#tidypics-tagging-quit').unbind('click');
	$('a._tp-people-tag-remove').unbind('click');
	$('.tidypics-photo').unbind('mouseenter mouseleave');
	$('a._tp-people-tag-link').unbind('mouseenter mouseleave');
	$('input[name=_tp_people_tag_submit]').unbind('click');

	// Clean up imgareaselect elements
	$('.tidypics-tagging-outer').remove();
	$('.tidypics-tagging-selection').parent().remove();
}

/**
 * Start a tagging session
 */
elgg.tidypics.tagging.start = function(event) {

	// Trigger a tagging started hook
	elgg.trigger_hook('peopleTagStarted', 'tidypics', null, null);

	if (elgg.tidypics.tagging.active) {
		elgg.tidypics.tagging.stop(event);
		return;
	}

	$('.tidypics-photo.taggable').imgAreaSelect({
		disable      : false,
		hide         : false,
		classPrefix  : 'tidypics-tagging',
		imageHeight  : $('.tidypics-photo.taggable').data('original_height'),
		imageWidth   : $('.tidypics-photo.taggable').data('original_width'),
		onSelectEnd  : elgg.tidypics.tagging.startSelect,
		onSelectStart: function() {
			$('#tidypics-tagging-select').hide();
		}
	});

	elgg.tidypics.tagging.toggleTagHover();

	$('.tidypics-photo.taggable').css({"cursor" : "crosshair"});

	$('#tidypics-tagging-help').toggle();

	elgg.tidypics.tagging.active = true;

	event.preventDefault();
};

/**
 * Stop tagging
 *
 * A tagging session could be completed or the user could have quit.
 */
elgg.tidypics.tagging.stop = function() {
	$('#tidypics-tagging-help').hide();
	$('#tidypics-tagging-select').hide();
	$('.tidypics-photo').imgAreaSelect({hide: true, disable: true});
	$('.tidypics-photo').css({"cursor" : "pointer"});

	elgg.tidypics.tagging.active = false;
	elgg.tidypics.tagging.toggleTagHover();
};

/**
 * Start the selection stage of tagging
 */
elgg.tidypics.tagging.startSelect = function(img, selection) {

	var coords  = '"x1":"' + selection.x1 + '",';
	coords += '"y1":"' + selection.y1 + '",';
	coords += '"width":"' + selection.width + '",';
	coords += '"height":"' + selection.height + '"';
	$("input[name=coordinates]").val(coords);

	$('#tidypics-tagging-select').show()
	.position({
		my : 'left center',
		at : 'right center',
		of : img
	})
	.find('input[name=_tp_people_tag_submit]').click(elgg.tidypics.tagging.peopleTagAddClick);
	
	$('input[type=text].elgg-input-autocomplete').val('').focus();
};

/**
 * Position the tags over the image
 */
elgg.tidypics.tagging.position = function() {
	var tag_left = parseInt($(this).data('x1')) / elgg.tidypics.tagging.scale;
	var tag_top = parseInt($(this).data('y1')) / elgg.tidypics.tagging.scale;
	var tag_width = parseInt($(this).data('width')) / elgg.tidypics.tagging.scale;
	var tag_height = parseInt($(this).data('height')) / elgg.tidypics.tagging.scale;

	// add image offset
	var image_pos = $('.tidypics-photo').position();
	tag_left += image_pos.left;
	tag_top += image_pos.top;

	$(this).parent().css({
		left: tag_left + 'px',
		top: tag_top + 'px'
	});	
						

	$(this).css({
		width: tag_width + 'px',
		height: tag_height + 'px'
	});
};

/**
 * Toggle whether tags are shown on hover over the image
 */
elgg.tidypics.tagging.toggleTagHover = function() {
	if (elgg.tidypics.tagging.tag_hover == false) {
		$('.tidypics-photo').hover(
			function() {
				$('.tidypics-tag-wrapper').show();
			},
			function(event) {
				// this check handles the tags appearing over the image
				var mouseX = event.pageX;
				var mouseY = event.pageY;
				var offset = $('.tidypics-photo').offset();
				var width = $('.tidypics-photo').outerWidth() - 1;
				var height = $('.tidypics-photo').outerHeight() - 1;

				mouseX -= offset.left;
				mouseY -= offset.top;

				if (mouseX < 0 || mouseX > width || mouseY < 0 || mouseY > height) {
					$('.tidypics-tag-wrapper').hide();
				}
			}
		);
	} else {
		$('.tidypics-photo').hover(
			function() {
				$('.tidypics-tag-wrapper').hide();
			},
			function() {
				$('.tidypics-tag-wrapper').hide();
			}
		);
	}
	elgg.tidypics.tagging.tag_hover = !elgg.tidypics.tagging.tag_hover;
};

/**
 * People tag ajax submit handler
 */
elgg.tidypics.tagging.peopleTagAddClick = function(event) {
	var $form = $(this).closest('form');
	var action = $form.attr('action');
	var value = $form.find('input[name=username]').val();
	var guid = $form.find('input[name=guid]').val();

	if (value) {
		elgg.action(action, {
			data: $form.serialize(),
			success: function(json) {
				if (json.status >= 0) {
					// Success
					elgg.tidypics.tagging.stop();

					var params = {
						output: json.output,
						guid: guid
					};

					if (elgg.trigger_hook('peopleTagAdded', 'tidypics', params, true)) {
						window.location = window.location.href;
					}
				} else {
					// There was an error
				}
			}
		});
	}

	event.preventDefault();
}

/**
 * People tag remove ajax handler
 */
elgg.tidypics.tagging.peopleTagRemoveClick = function(event) {
	if (confirm(elgg.echo('tidypics:phototagging:delete:confirm'))) {
		var action = $(this).attr('href');
		var $_this = $(this);
		elgg.action(action, {
			data: {},
			success: function(json) {
				if (json.status >= 0) {
					// Success, remove the tag from the DOM
					$_this.closest('div.tidypics-tag-wrapper').remove();

					var params = {
						output: json.output
					};

					elgg.trigger_hook('peopleTagRemoved', 'tidypics', params, null);
				} else {
					// Error
				}
			}
		});
	}
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.tidypics.tagging.init);
elgg.register_hook_handler('photoLightboxBeforeClose', 'tidypics', elgg.tidypics.tagging.destroy);