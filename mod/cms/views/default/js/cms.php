<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>
elgg.provide('minds.cms');

minds.cms.init = function() {
    
    $('.cms-section-add > a').on('click', function(e){
    	e.preventDefault();
    	$.ajax({
    		url : elgg.get_site_url() + 'admin/cms/sections/' + $(this).data('group'),
    		method : 'put',
    		success : function(data){
    			console.log(data);
    			
    			if($('.cms-sections').find('section').length == 0)
    				window.location.reload();
    			
    			$('.cms-sections').append(data);
    			if(jQuery().minicolors) { 
				$('.cms-sections').find('.icon-colour input').minicolors();
				
				elgg.tinymce.init();
				
			}    
		}
    	});
    });
    
    $(document).on('click', '.icon-delete', function(e){
    	e.preventDefault();
    	var section = $(this).parents('section')[0];

    	$.ajax({
    		url : elgg.get_site_url() + 'admin/cms/sections/' + $(section).attr('data-guid'),
    		method : 'delete',
    		success : function(data){
    			$(section).remove();
    		}
    	});
    });
    
     $(document).on('change', '.icon-bg', function(e){
    	e.preventDefault();
    	var section = $(this).parents('section')[0];
		
		files = e.target.files;
		var data = new FormData();
		$.each(files, function(key, value){
			data.append(key, value);
		});
		
		data.append('__elgg_ts', elgg.security.token.__elgg_ts);
		data.append('__elgg_token', elgg.security.token.__elgg_token);

    	$.ajax({
    		url : elgg.get_site_url() + 'admin/cms/sections/' + $(section).attr('data-guid'),
    		data : data,
    		method : 'post',
			processData: false, // Don't process the files
			contentType: false,
    		success : function(data){
    			console.log(data);
    		//	$(section).find('.cms-section-bg').attr('style="background-image: ' + data + '"');
    			$(section).find('.cms-section-bg img').css('display', 'block');
    			$(section).find('.cms-section-bg img').attr('src', data);
    		//	$(section).find('.cms-section-bg').css('background-image', 'url('+data+')');
    		}
    	});
    });

	if(jQuery().minicolors) {    
    		$('.icon-colour input').minicolors({
    			defaultValue : $('.icon-colour input').val()
    		});
    		$('.icon-overlay input[type=text]').minicolors({
                defaultValue : $('.icon-overlay input[name=overlay_background]').val(),
                opacity: true,
                change: function(hex, opacity){
                    var section = $(this).parents('section')[0];
                     $(section).find('.cms-overlay').css('opacity', opacity);
                   $(section).find('input[name=overlay_colour]').css('background', opacity);
                }
            });
	}
	
	$(document).on('change', '.icon-overlay input', function(){
        var section = $(this).parents('section')[0];
        $(section).find('.cms-overlay').css('background', $(this).val());
        $(section).find('input[name=overlay_colour]').css('background', $(this).val());
        
        minds.cms.update($(section));
    });
	
    $(document).on('change', '.icon-colour input', function(){
    	var section = $(this).parents('section')[0];
    	$(section).find('textarea').css('color', $(this).val());
    	$(section).find('input').css('color', $(this).val());
    	
    	minds.cms.update($(section));
    });
    
    $(document).on('click', '.icon-toggle', function(){
        var section = $(this).parents('section')[0];
        var size = $(section).find('input[name=size]').val();

        if(size == 'thin' || !size){
            //now make fat
            $(section).removeClass('cms-section-thin');
            $(section).addClass('cms-section-fat');
            $(section).find('input[name=size]').val('fat');
        } else {
            //now make thin
            $(section).removeClass('cms-section-fat');
            $(section).addClass('cms-section-thin');
            $(section).find('input[name=size]').val('thin');
        }
        
        minds.cms.update($(section));
    });
    
    var typingTimer;                //timer identifier
    $(document).on('keyup', '.cms-section', function(e){
    	 tigger_update(this);
    });
     $(document).on('updated-tinymce', function(e, id){
     	fake_input = $('#'+id);
     	section = fake_input.parents('.cms-section');
     	if(section.length)
    		tigger_update(section);
    });
    
    function tigger_update(_this){
    	clearTimeout(typingTimer);
    	 typingTimer = setTimeout(function(){
    	 	minds.cms.update($(_this));
    	 },1000);
	}
 
 	$(".cms-sections-editable .cms-section .cms-section-bg img").draggable({
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
					
				input = wrapper.find("input[name=top_offset]");
				if(input.length > 0)
					input.val(ui.position.top);
					
			},
            stop: function(event, ui) {
             	img = $(event.target);
           		minds.cms.update(img.parents('section'));	
			}
        });
   
   
   $(".cms-sections-editable").sortable({
			//items:                '.cms-sections section.cms-section',
			//connectWith:          '.cms-section',
			helper: 			  'clone',
			handle:               '.icon-move',
			forcePlaceholderSize: true,
			//placeholder:          'elgg-widget-placeholder',
			opacity:              0.8,
			//revert:               500,
			distance: 				0,
			stop:                 function(e, ui){ 
									console.log('you just moved me to '+ ui.item.index()); 
									//ui.item.find('#order').val(ui.item.index());
									
									$('.cms-sections-editable > section').each(function(i,j){
										section = $(this).find('input[name=position]');
										section.val($(this).index());
										minds.cms.update($(this));
									});
									
								}
		}); 	
		
	$(".cms-banner-editable .carousel .item img").css('cursor', "move");
	$(".cms-banner-editable .carousel .item img").draggable({
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

			form = wrapper.parents('.body').find('.elgg-form-cms-page');
			form.find('input[name=banner_position]').val(ui.position.top);
				
		},
        stop: function(event, ui) {
         	img = $(event.target);
       	//	minds.cms.update(img.parents('section'));	
		}
    });
}

minds.cms.update = function(section){

	
	var data = {
		leftH2: section.find('.left .h2').val(),
		leftP: section.find('.left .p').val(),
		rightH2: section.find('.right .h2').val(),
		rightP: section.find('.right .p').val(),
		color: section.find('.icon-colour input').val(),
		href: section.find('input[name=href]').val(),
		position: section.find('input[name=position]').val(),
		top_offset: section.find('input[name=top_offset]').val(),
		overlay_colour: section.find('input[name=overlay_colour]').val(),
		overlay_opacity:  section.find('input[name=overlay_opacity]').val(),
		size:  section.find('input[name=size]').val(),
	};
	
	if (typeof tinymce !== 'undefined') {	
		data.content = tinymce.get(section.find('textarea').attr('id')).getContent();
	}

	$.ajax({
		url : elgg.get_site_url() + 'admin/cms/sections/' + section.attr('data-guid'),
		data : elgg.security.addToken(data),
		method : 'post',
		success : function(data){
			console.log(data);
		}
	});

}
	
elgg.register_hook_handler('init', 'system', minds.cms.init);
