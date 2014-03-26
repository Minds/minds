<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
	 
	 //Main js that needs to be loaded
	 elgg.provide('minds');
	 
	 minds.init = function() {	 
	 	
	 /*	$('a').on('click', function(e){
	 		e.preventDefault();
	 		
	 		window.history.pushState("string", "Title", $(this).attr('href'));

	 		$.ajax({
				//type: 'GET',
				url: $(this).attr('href'),
				
	 			data:'async=true',
	 			xhr: function(){
					// get the native XmlHttpRequest object
					var xhr = $.ajaxSettings.xhr() ;
					// set the onprogress event handler
					xhr.addEventListener('progress', function(evt){
						console.log('progress', evt.loaded/evt.total*100)
					});
					
					// set the onload event handler
					// return the customized object
					return xhr ;
				},
	 			success: function(data){
	 				$('.body').html(data);
	 			}
	 		})
	 		
	 		 

	 	});
	 	*/
	 	
	 	$(".carousel-admin-items").sortable({
			items:                'div',
			connectWith:          '.carousel-admin-items',
			helper: 			  'clone',
			//handle:               '.carousel-admin-wrapper',
			forcePlaceholderSize: true,
			//placeholder:          'elgg-widget-placeholder',
			opacity:              0.8,
			//revert:               500,
			distance: 				0,
			stop:                 function(e, ui){ 
									console.log('you just moved me to '+ ui.item.index()); 
									//ui.item.find('#order').val(ui.item.index());
									
									$('.carousel-admin-items > div').each(function(i,j){
										console.log($(this));
										$(this).find('#order').val($(this).index());
									});
									
									

								}
		});
	 	

		if($('#fb-share').length){
			var addr = $('meta[property="og:url"]').attr('content');
			var url = 'https://graph.facebook.com/?id=' + addr;
			$.get( url, function(data) { var shares = data.shares; if($.isNumeric(shares)){ $('#fb-share .count').html(shares); } } );
		} 

		$('.elgg-list.mason').imagesLoaded(function(){
				var $list = $('.elgg-list.mason');
				$($list.get().reverse()).masonry({
                	//itemSelector: '.elgg-item'
                });                        
		});
		
		$('.hero > .topbar').on('mouseenter', '.right', function(e){ $('.topbar .right .social-login').show(); });
		$('.hero > .body').on('click', function(e){ $('.topbar .right .social-login').hide(); });
		
		$('.elgg-form-blog-save, .elgg-form-archive-save').submit(function(e){
 			var c = confirm("Please confirm you understand the selected license, and that you either own or have permission under the terms of the license.");
			return c;
		});

		$('.elgg-menu li a').tipsy({gravity: 'n'}); 
		$('.progress_indicator').tipsy({gravity: 'e'});		
		$('.elgg-input-text').tipsy({gravity: 'w'});
		$('.tooltip.s').tipsy({gravity: 's'});
		$('.tooltip.n').tipsy({gravity: 'n'});
		$('.tooltip.w').tipsy({gravity: 'w'});
		$('.tooltip.e').tipsy({gravity: 'e'});

		$('.elgg-form-wall-add textarea').focus( function(e){ $(this).parent().find('.elgg-button-submit').css('display','block');});

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
		if(elgg.is_logged_in()){
			$(document).on('click', '.subscribe-button a', minds.subscribe);
		}
		$(document).on('click', '.elgg-menu-item-feature a', minds.feature);

		$(document).on('click', 'li .elgg-menu-item-delete a', minds.remove); 
		$(document).on('click', '.elgg-menu-item-remind a', minds.remind);

		$(document).on('keydown', '.minds-search .elgg-input-text', minds.search);
	};
	 
	
	 elgg.register_hook_handler('init', 'system', minds.init);
	
	minds.wallPost = false;
	
	minds.search = function(e){
		if($(this).val().indexOf('/') == 0 && e.which == 13){
			e.preventDefault();
			var data = {};
			//data.to_guid = elgg.get_logged_in_user_guid();
			data.body = $(this).val();
			data.ref = 'news';
			
			data.body = data.body.replace('/', '');

			elgg.action('wall/add?body=' + data.body  +'&ref=' + data.ref, {
				success: function(data) {
					    $('.elgg-input-text').val("");;
						$('.elgg-list.elgg-list-river.elgg-river').first('.elgg-list.elgg-list-river.elgg-river').prepend(data.output);
						minds.wallPost = false;
				},
				error: function(data){
        	                }
	                });

		}	
	}

	minds.subscribe = function(e){
		e.preventDefault();
		var button = $(this);
		elgg.action($(this).attr('href') + '&ajax=true', {
			success: function(data) {
				if(data.output == 'subscribed'){
					button.addClass('subscribed');
					button.html('<span class="text">Subscribed</span>');
					button.attr('href', button.attr('href').replace('add', 'remove'));
				} else {
					button.removeClass('subscribed');
                                        button.html('<span class="text">Subscribe</span>');
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

	minds.remove = function(e){
		e.preventDefault();
               var button = $(this);
		var item = button.parents('.elgg-item');
                elgg.action($(this).attr('href') + '&ajax=true', {
                        success: function(data) {
                  		item.effect('drop'); 
                         },

                        error: function(data){
                        }
                });
        }

        minds.remind = function(e){
                e.preventDefault();
                var button = $(this);
                var item = $(this).parent('elgg-list');
		elgg.action($(this).attr('href') + '&ajax=true', {
                        success: function(data) {
				if(item.length){
				//we are in a list so we want to add a remind item in
				}
        			button.css('color', '#4690D6');        
			/*	button.effect('explode'); */      
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
	 		if($(window).scrollTop() + $(window).height() > $(document).height() * 0.25){
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
		if(window.lock_autoscroll){
			return false;
		}
		
		window.lock_autoscroll = true;
		
		$list = $(this).parent().find('.elgg-list:first').parent();
		$('.load-more').html('...');
		$('.load-more').addClass('loading');
			
		var loc =  elgg.normalize_url(elgg.parse_url(location.href).path);
/*		if(loc == elgg.get_site_url()){
			loc = location.href + 'news/featured';
		}
*/
		var offset = 0;

		$params = elgg.parse_str(elgg.parse_url(location.href).query);
			
		if(loc.indexOf('trending') > -1 || loc.indexOf('view') > -1 || $params.filter == 'trending' || loc.indexOf('search') > -1){
			offset = $list.find('.elgg-list').children().length;
		} else {
			offset = $('.load-more').attr('data-load-next');
			/*	 if(loc == elgg.get_site_url()){
                                        offset = $list.find('li.elgg-item:last').attr('featured_id');
                                } else {
                                        offset = $list.find('li.elgg-item:last').attr('id'); 
                                }*/
		}
		console.log(offset);
		if(!offset){
			window.lock_autoscroll = false;
			return false;
		}
		$params = $.extend($params, {
			path : loc,
			items_type: $list.find('.elgg-list').hasClass('elgg-list-entity') ? 'entity' :
				$list.find('.elgg-list').hasClass('elgg-list-river') ? 'river' :
				$list.hasClass('elgg-list-annotation') ? 'annotation' : 
				$list.find('.elgg-list').hasClass('minds-search-list') ? 'search' : 'river',
				offset:offset 
		});
		url = "/ajax/view/page/components/ajax_list?" + $.param($params);
			
		elgg.get(url, function(data) {
			//$list.toggleClass('infinite-scroll-ajax-loading', false);
				
				if($(data).contents().length == 0){
					
					$('.load-more').html('');
					
				} else {

					$('.load-more').remove();
				
					var el = $(data).contents().unwrap();
					
					if(loc == elgg.get_site_url()){
                                   		offset = $(data).find('li.elgg-item:last').attr('featured_id');
                               		} else {
                                        	offset = $(data).find('li.elgg-item:last').attr('id'); 
                                	}
					
					el.imagesLoaded(function(){
						 $list.find('.elgg-list').append(el).masonry('appended', el);
						window.lock_autoscroll = false;
					});
	/*		
$list.find('.elgg-list:first').append(data);
                                        $list.find('.elgg-list:first > ul:last').contents().unwrap();
					$('.elgg-list.mason').masonry()
						.imagesLoaded( function() {
  							$('.elgg-list.mason').masonry().masonry('reloadItems').masonry();
						});
					*/
					$list.append('<div class="news-show-more load-more" data-load-next="'+offset+'">more</div>');

					// Trigger a hook for extra tasks after content is loaded
					elgg.trigger_hook('loadMore', 'minds');
				}
			});
		 
	 };	
     
<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

