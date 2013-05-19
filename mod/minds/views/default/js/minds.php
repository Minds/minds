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
		
		$(document).on('click', '.load-more', minds.loadMore);
		/**
		 * Now make this autoscroll!
		 */
		$(window).on('scroll', minds.onScroll);
	 };
	 
	
	 elgg.register_hook_handler('init', 'system', minds.init);
	 
	 minds.onScroll = function(){
	 	$loadMoreDiv = $(document).find('.load-more');
	 	//go off the loadmore button being available
	 	if($loadMoreDiv){
	 		$list = $loadMoreDiv.parent();
	 		if($(window).scrollTop() + $(window).height() > $(document).height() - 300){
	 			if(!$loadMoreDiv.hasClass('loading')){
	 				$loadMoreDiv.trigger('click');
	 			}
	 		}
	 	}
	 	
	 }

	 minds.loadMore = function() {
						
			$list = $(this).parent().find('.elgg-list');
			$('.load-more').html('loading...');
			$('.load-more').addClass('loading');
						
			var loc = location.href;
			if(loc == elgg.get_site_url()){
				loc = location.href + 'news/featured';
			}

			$params = elgg.parse_str(elgg.parse_url(location.href).query);
			$params = $.extend($params, {
				path: loc,
				items_type: $list.hasClass('elgg-list-entity') ? 'entity' :
							$list.hasClass('elgg-list-river') ? 'river' :
							$list.hasClass('elgg-list-annotation') ? 'annotation' : 'river',
				offset: $list.children().length + (parseInt($params.offset) || 0)
			});
			url = "/ajax/view/page/components/ajax_list?" + $.param($params);
			console.log($params);
			elgg.get(url, function(data) {
				//$list.toggleClass('infinite-scroll-ajax-loading', false);
				
				if($(data).contents().length == 0){
					
					$('.load-more').html('no more posts');
					
				} else {

					$('.load-more').remove();
					
					$list.append(data);							
	
					$list.append('<div class="news-show-more load-more">click to load more</div>');
					
				}
			});
		 
	 };	
     
<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

