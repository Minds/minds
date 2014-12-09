<?php
/**
 * Javascript for Groups forms
 *
 * @package ElggGroups
 */
if(0){?><script><?php } ?>

// this adds a class to support IE8 and older
elgg.register_hook_handler('init', 'system', function() {
	// jQuery uses 0-based indexing
	$('#groups-tools').children('li:even').addClass('odd');
	
	$(".group-banner-editable .carousel .item img").css('cursor', "move");
	$(".group-banner-editable .carousel .item img").draggable({
		scroll: false,
		axis: "y",
		drag: function(event, ui) {
			img = $(event.target);
          	wrapper = img.parent();
     
			if(ui.position.top >= 0){
				ui.position.top = 0;
			} else if(ui.position.top <= wrapper.height() - img.height()) {
				ui.position.top = wrapper.height() - img.height();
			}

			wrapper.parents('.body').find('input[name=banner_position]').val(ui.position.top);
				
		},
        stop: function(event, ui) {
         	img = $(event.target);
       	//	minds.cms.update(img.parents('section'));	
		}
    });
	
});
