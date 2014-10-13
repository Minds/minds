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
    			
    			$('.cms-sections').append(data);
    			$('.cms-sections').find('.icon-colour input').minicolors();
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
		
		files = event.target.files;
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
    			$(section).find('.cms-section-bg').css('background-image', 'url('+data+')');
    		}
    	});
    });

	if(jQuery().minicolors) {    
    		$('.icon-colour input').minicolors({
    			defaultValue : $('.icon-colour input').val()
    		});
	}
    $(document).on('change', '.icon-colour input', function(){
    	var section = $(this).parents('section')[0];
    	$(section).find('textarea').css('color', $(this).val());
    	$(section).find('input').css('color', $(this).val());
    	
    	minds.cms.update($(section));
    });
    
    var typingTimer;                //timer identifier
    $(document).on('keyup', '.cms-section', function(e){
    	 clearTimeout(typingTimer);
    	 _this = this;
    	 typingTimer = setTimeout(function(){
    	 	minds.cms.update($(_this));
    	 },1000);
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
}

minds.cms.update = function(section){

	var data = {
		leftH2: section.find('.left .h2').val(),
		leftP: section.find('.left .p').val(),
		rightH2: section.find('.right .h2').val(),
		rightP: section.find('.right .p').val(),
		color: section.find('.icon-colour input').val(),
		href: section.find('input[name=href]').val(),
		position: section.find('input[name=position]').val()
	};

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
