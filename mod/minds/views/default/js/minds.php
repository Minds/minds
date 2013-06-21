<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
	 
	 //Main js that needs to be loaded
	 elgg.provide('minds');
	 
	 minds.init = function() {	 

		$('.elgg-menu li a').tipsy({gravity: 'n'}); 
		$('.progress_indicator').tipsy({gravity: 'e'});		
		
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
		$(document).on('click', '.elgg-button-action.subscribe', minds.subscribe);
		$(document).on('click', '.elgg-menu-item-feature a', minds.feature);
		$(document).on('click', '.elgg-menu-item-delete a', minds.delete); 
	};
	 
	
	 elgg.register_hook_handler('init', 'system', minds.init);

	minds.subscribe = function(e){
		e.preventDefault();
		var button = $(this);
		elgg.action($(this).attr('href') + '&ajax=true', {
			success: function(data) {
				if(data.output == 'subscribed'){
					button.addClass('subscribed');
					button.html('Subscribed');
					button.attr('href', button.attr('href').replace('add', 'remove'));
				} else {
					button.removeClass('subscribed');
                                        button.html('Subscribe');
					button.attr('href', button.attr('href').replace('remove','add'));
				}
			},
			error: function(data){
			}
		});
	}	 
	
	minds.feature = function(e){
		e.preventDefault();
		var button = $(this);
                elgg.action($(this).attr('href') + '&ajax=true', {
                        success: function(data) {
				if(data.output == 'featured'){			
                                        button.html('un-feature');
				} else {
					button.html('feature');
				}
                         },
                   
                        error: function(data){
                        }
                });
	}

	minds.delete = function(e){
		e.preventDefault();
               var button = $(this);
		var item = button.parents('.elgg-item');
                elgg.action($(this).attr('href') + '&ajax=true', {
                        success: function(data) {
                  		item.remove(); 
                         },

                        error: function(data){
                        }
                });
        }

	 minds.onScroll = function(){
	 	$loadMoreDiv = $(document).find('.load-more');
	 	//go off the loadmore button being available
	 	if($loadMoreDiv){
	 		$list = $loadMoreDiv.parent();
	 		if($(window).scrollTop() + $(window).height() > $(document).height() * 0.40){
	 			if(!$loadMoreDiv.hasClass('loading')){
	 				$loadMoreDiv.trigger('click');
	 			}
	 		}
	 	}
	 	
	 }
	 
	 minds.listParams = {
	 	offset : 0,
	 	limit : 12
	 };

	 minds.loadMore = function() {
						
			$list = $(this).parent().find('.elgg-list:first').parent();
			$('.load-more').html('...');
			$('.load-more').addClass('loading');
			
			var loc =  elgg.normalize_url(elgg.parse_url(location.href).path);
			if(loc == elgg.get_site_url()){
				loc = location.href + 'news/featured';
			}

			$params = elgg.parse_str(elgg.parse_url(location.href).query);
			$params = $.extend($params, {
				path : loc,
				items_type: $list.find('.elgg-list').hasClass('elgg-list-entity') ? 'entity' :
							$list.find('.elgg-list').hasClass('elgg-list-river') ? 'river' :
							$list.hasClass('elgg-list-annotation') ? 'annotation' : 'river',
				offset: $list.find('.elgg-list').children().length + (parseInt($params.offset) || 0)
			});
			url = "/ajax/view/page/components/ajax_list?" + $.param($params);
			console.log(url);
			elgg.get(url, function(data) {
				//$list.toggleClass('infinite-scroll-ajax-loading', false);
				
				if($(data).contents().length == 0){
					
					$('.load-more').html('');
					
				} else {

					$('.load-more').remove();
					
					$list.append(data);							
	
					$list.append('<div class="news-show-more load-more">more</div>');
					
				}
			});
		 
	 };	
     
<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

