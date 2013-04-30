<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
	 
	 //Main js that needs to be loaded
	 elgg.provide('minds');
	 
	 minds.init = function() {	 

		$('.elgg-menu li a').tipsy({gravity: 'n'}); 
		
		$('.thumbnail-tile').hover(
			function(){
		   		$(this).children('.hover').fadeIn('fast');
			},
			function(){
		   		$(this).children('.hover').fadeOut('fast');
		}); 

		//handle cookie session messages
		var msg = $.cookie('_elgg_ss_msg');
		var err_msg = $.cookie('_elgg_err_msg');
		if (typeof err_msg == 'string' || typeof msg == 'string' ) {
		
				if (err_msg != null) {
					elgg.register_error(err_msg);
					$.removeCookie('_elgg_err_msg',{path:'/'});
				}
				if (msg != null) {
					elgg.system_message(msg);
					$.removeCookie('_elgg_ss_msg', {path:'/'});
				}
		}
	 };
	 
	
	 elgg.register_hook_handler('init', 'system', minds.init);
	 
	 
	 //Extend the river
	 elgg.provide('river.extend');
	 
	 river.extend.init = function() {
		 
	
		 
	 };
	 var riverOffset = 0;
	 river.extend.trigger = function(context, offset) {
			offset = offset || 5;
			
			$list = $(this).parent();
			$('.news-show-more').html('loading...');
			
			riverOffset += +offset;
			var loc = location.href;
			if(context == 'main'){
				loc = location.href + 'news/featured';
			}
			$params = elgg.parse_str(elgg.parse_url(location.href).query);
			$params = $.extend($params, {
				path: loc,
				items_type: $list.hasClass('elgg-list-entity') ? 'entity' :
							$list.hasClass('elgg-list-river') ? 'river' :
							$list.hasClass('elgg-list-annotation') ? 'annotation' : 'river',
				offset: riverOffset,
			});
			url = "/ajax/view/page/components/ajax_list?" + $.param($params);
						
			elgg.get(url, function(data) {
				//$list.toggleClass('infinite-scroll-ajax-loading', false);
				
				if($(data).contents().length == 0){
					$('.news-show-more').removeAttr('onclick');
					$('.news-show-more').html('no more posts');
				} else {

					$('.news-show-more').remove();
					
					$('.elgg-list.elgg-list-river.elgg-river').parent().append(data);							
					
					$('.elgg-list.elgg-list-river.elgg-river').parent().append('<div class="news-show-more" onclick="river.extend.trigger(\''+context+'\', ' + offset + ')">more</div>');
				
				
				}
			});
		 
	 };
	 
	
	 elgg.register_hook_handler('init', 'system', river.extend.init);
	
     
<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

