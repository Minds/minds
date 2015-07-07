<?php if (FALSE) : ?>
   	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('channels');

	channels.init = function(){
	
	   $.ajaxSetup({
            scriptCharset: "utf-8", //or "ISO-8859-1"
            contentType: "application/json; charset=utf-8"
        });
	   $.getJSON("https://www.facebook.com/me", function(){
	           console.log(response);
	           console.log(data);
	        }
	        );
		
		if($(".colorpicker").length){
			$(".colorpicker").minicolors({
					letterCase: 'uppercase',	
					change: function(){ 
						switch ($(this).attr('name')){
							case 'background_colour': 
								$('body').css('background', $(this).val());
								break;
							case 'h1_colour':
								$('.name h1').css('color', $(this).val());
								break;
							/*case 'h3_colour':
								$('.channel-header h3').css('color', $(this).val());*/
							case 'menu_link_colour':
								 $('.elgg-menu-channel-default a').css('color', $(this).val());
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
