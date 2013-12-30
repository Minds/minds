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
								case 'text_colour':
									$('h1,h2,h3,h4,h5').css('color', $(this).val());
									console.log($(this).value);
									break;
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
