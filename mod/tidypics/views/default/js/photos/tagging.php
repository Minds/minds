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

elgg.tidypics.tagging.init = function() {
	elgg.tidypics.tagging.active = false;
	$('[rel=photo-tagging]').click(elgg.tidypics.tagging.start);

	$('#tidypics-tagging-quit').click(elgg.tidypics.tagging.stop);

	$('.tidypics-tag').each(elgg.tidypics.tagging.position);

	elgg.tidypics.tagging.tag_hover = false;
	elgg.tidypics.tagging.toggleTagHover();
};

/**
 * Start a tagging session
 */
elgg.tidypics.tagging.start = function(event) {

	if (elgg.tidypics.tagging.active) {
		elgg.tidypics.tagging.stop(event);
		return;
	}

	$('.tidypics-photo').imgAreaSelect({
		disable      : false,
		hide         : false,
		classPrefix  : 'tidypics-tagging',
		onSelectEnd  : elgg.tidypics.tagging.startSelect,
		onSelectStart: function() {
			$('#tidypics-tagging-select').hide();
		}
	});

	elgg.tidypics.tagging.toggleTagHover();

	$('.tidypics-photo').css({"cursor" : "crosshair"});

	$('#tidypics-tagging-help').toggle();

	elgg.tidypics.tagging.active = true;

	event.preventDefault();
};

/**
 * Stop tagging
 *
 * A tagging session could be completed or the user could have quit.
 */
elgg.tidypics.tagging.stop = function(event) {
	$('#tidypics-tagging-help').toggle();
	$('#tidypics-tagging-select').hide();

	$('.tidypics-photo').imgAreaSelect({hide: true, disable: true});
	$('.tidypics-photo').css({"cursor" : "pointer"});

	elgg.tidypics.tagging.active = false;
	elgg.tidypics.tagging.toggleTagHover();

	event.preventDefault();
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
		.css({
			'top' : selection.y2 + 10,
			'left' : selection.x2
		})
		.find('input[type=text]').focus();

};

/**
 * Position the tags over the image
 */
elgg.tidypics.tagging.position = function() {
	var tag_left = parseInt($(this).data('x1'));
	var tag_top = parseInt($(this).data('y1'));
	var tag_width = parseInt($(this).data('width'));
	var tag_height = parseInt($(this).data('height'));

	// add image offset
	var image_pos = $('.tidypics-photo').position();
	tag_left += image_pos.left;
	tag_top += image_pos.top;

	$(this).parent().css({
		left: tag_left + 'px',
		top: tag_top + 'px' /*
		width: tag_width + 'px',
		height: tag_height + 'px' */
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

elgg.register_hook_handler('init', 'system', elgg.tidypics.tagging.init);
