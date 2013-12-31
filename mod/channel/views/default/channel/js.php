<?php if (FALSE) : ?>
   	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('channels');

	channels.init = function(){
		
		if($(".colorpicker").length){
			$(".colorpicker").miniColors({
					letterCase: 'uppercase',	
					change: function(){ 
							switch ($(this).attr('name')){
								case 'background_colour': 
									$('body').css('background', $(this).val());
									break;
								case 'h1_colour':
									$('.channel-header h1').css('color', $(this).val());
									break;
								case 'h3_colour':
									$('.channel-header h3').css('color', $(this).val());
								case 'menu_link_colour':
									 $('.channel-filter-menu a').css('color', $(this).val());
							}
					}	
				});
		}
		
				
	
		// only do this on the profile page's widget canvas.
		if ($('.profile').length) {
			$('#elgg-widget-col-1').css('min-height', $('.profile').outerHeight(true) + 1);
		}
	}

elgg.register_hook_handler('init', 'system', channels.init, 400);
