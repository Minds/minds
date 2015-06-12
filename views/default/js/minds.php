<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
	 
	 //Main js that needs to be loaded
	 elgg.provide('minds');
	 
	 minds.init = function() {	
	 	var sidebarOpen = $.cookie('sidebarOpen') == "true" ? true : false;
	 	if($(window).width() < 720)
	 		sidebarOpen = false;

		if($("select[name=node]").length != 0)	
		$("select[name=node]").select2();
	 		
	 	$(document).on('click', '.menu-toggle', function(){
	 		$('.hero').removeClass('sidebar-active-default');
	 		$('.global-sidebar').removeClass('show-default');
	 		if(sidebarOpen){
	 			$('.global-sidebar').removeClass('show');
	 			$('.hero').removeClass('sidebar-active');
	 			$('body').removeClass('sidebar-active');
	 			
	 			sidebarOpen = false;
	 		//	$.removeCookie('sidebarOpen'); 
	 			$.cookie('sidebarOpen', 'false', { path: '/' });
	 		} else {
	 			$('.global-sidebar').addClass('show');
	 			$('.hero').addClass('sidebar-active');
	 			$('body').addClass('sidebar-active');
	 			sidebarOpen = true
	 			$.cookie('sidebarOpen', 'true', { path: '/' });
	 		}
			if($('.elgg-list.mason').length > 0){
				$('.elgg-list.mason').masonry().masonry('reloadItems');
			}

	 	});
	 	
	 	$('img').error(function(){
	 		$(this).remove();
	 	});
		

        var UA = navigator.userAgent;
        if(UA.match(/Android/i) != null){

            $('.banners').show();

        }

	//	if(!elgg.is_logged_in() && !$.cookie('promptMobile')){
		/*	setTimeout(function(){ 
                $('.minds-mobile-popup').parent().show();

                $.cookie('promptMobile', true) }, 4000);
	//	}
		
		$(document).on('click', ".minds-mobile-popup-wrapper .cancel", function(){
		    $('.minds-mobile-popup').hide();
        });
		*/
		/**
		 * Save form input, incase people refresh
		 */
		$(document).on('change', '.elgg-form-blog-save', function(e){	
		var form = $('.elgg-form-blog-save');
		
			if(form.length > 0){
				localStorage.setItem("blog-form", JSON.stringify(form.serializeArray()));
			}
		});
		$(document).on('updated-tinymce', function(e){
			var form = $('.elgg-form-blog-save');
                        if(form.length > 0){
				form.find('textarea').val(tinyMCE.activeEditor.getContent());
				form.trigger('change');
			}
		});
		$(document).on('click', '.elgg-menu-item-add', function(e){
			localStorage.removeItem("blog-form");
		});

		/**
		 * Do we have a saved form?
		 */
		$(document).ready(function (){	
			var form = $('.elgg-form-blog-save');
                        if(form.length > 0 && form.attr('autosave') != "off"){
				var data = localStorage.getItem("blog-form");
				if (null == data || $.isEmptyObject(data)) return; // nothing to do
				$.each(JSON.parse(data), function (i, kv) {
					// find form element, set its value
					var $input = form.find('[name=' + kv.name + ']');

					// how to set it's value?
					if ($input.is(':checkbox') || $input.is(':radio')) {
						$input.filter(function () { return $(this).val() == kv.value; }).first().attr('checked', 'checked');
					} else {
						$input.val(kv.value);
					}
				}); 
			}
		});

		/**
		 * Make our newsfeed icons centred @todo make less hacky
		 */
		$(window).on("load resize", function(){
			setTimeout(function(){

                if( $('.thumbnail-wrapper img').height() / 2 > 360){
				$('.thumbnail-wrapper img.thumbnail').css('margin-top', ($('.thumbnail-wrapper').height() - $('.thumbnail-wrapper img').height()) /2); 
                }

				/*var img = $('.carousel-inner > .item > img');
				var img_height = img.height();
				var img_offset = img.offset().top;
				var ratio = img.height() / img.width();
				
				var container_height = $('.carousel-inner > .item').height();
				
				if((img_height + img_offset) < container_height){
					
					if(!img.attr('data-css-top'))
						img.data('css-top', img_offset);
					
					img.css('top', 'auto');
					img.css('bottom', 0);
					
				} else if(img.width() > 1000) {
				
					img.css('top', img.data('css-top'));
					
				}*/

			
				
			}, 2000);
		});
	
	 	
	 	$(".carousel-admin-items").sortable({
			items:                'div.carousel-admin-wrapper',
			connectWith:          '.carousel-admin-items',
			helper: 			  'clone',
			handle:               '.drag',
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
		
		if($(".carousel-colorpicker").length){
			/*$(".text-color").minicolors({
					letterCase: 'uppercase',	
					change: function(){ 
							$(this).parent().find('textarea').css('color', $(this).val());
					}	
				});*/
			$(".text-color").on('keyup', function(){
				$(this).css('color', $(this).val());
				$(this).parents('.carousel-admin-wrapper').find('textarea').css('color', $(this).val());
			});
			$(".shadow-color").spectrum({
   				showAlpha: true,
				showButtons: false,
				preferredFormat: 'rgb',
				move: function(color) {
					color = color.toRgbString();
					console.log(color);
					$(this).val(color);
					$(this).parents('.carousel-admin-wrapper').find('textarea').css('background', color); 
				},
				chose: function(color){
					this.move(color);
				}
			});
		}
		
		$(".bg_wrapper > img, .cms-section .cms-section-bg img").css('cursor','s-resize');
		$(".bg_wrapper > img").draggable({
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
				
				input = wrapper.parent().find('#top_offset');
				if(input.length > 0)
					input.val(ui.position.top);
			},
            stop: function(event, ui) {
            //####
			}
        });
        
        /**
         * Should really standardise this... this is pretty dumb!!
         */
       	$(".blog-banner-editable .carousel .item img").css('cursor', "move");
		$(".blog-banner-editable .carousel .item img").draggable({
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
        
        $("").draggable({
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
			//	wrapper.parent().find('#top_offset').val(ui.position.top);
			},
            stop: function(event, ui) {
            //####
			}
        });
	 
		$('.carousel-admin-wrapper > textarea').on('keyup', function(){
			var px = $(this).val().length*36 + 20;
			console.log(px);
			if(px < 880 && px > 200){
				$(this).css('width', px);
				} else if(px < 880)  {
				$(this).css('height', 200);
			}
		});	

		if($('#fb-share').length){
			var addr = $('meta[property="og:url"]').attr('content');
			addr = addr.replace('http:', 'https:');
			var url = 'https://graph.facebook.com/?id=' + addr;
			var total = 0;
			$.get( url, function(data) { 
				var shares = data.shares; 
				if($.isNumeric(shares)){ 
					total = shares; 
				}
		
				addr = addr.replace('https:', 'http:');
				url = 'https://graph.facebook.com/?id=' + addr;

				$.get( url, function(data) { 
					var shares = data.shares; 
					 if($.isNumeric(shares)){
						total = total+shares;
					}
					$('#fb-share .count').html(total); 
				});
			});
		} 

		var $list = $('.elgg-list.mason');
		//$list.masonry();   
		$('.elgg-list.mason').imagesLoaded(function(){
			
			$list.find('.rich-image').each(function(){ 
				var image = $(this); 
				if(image.context.naturalWidth < 2 ||
				image.readyState == 'uninitialized'){    
					 /*$(image).unbind("error").attr(
					    "src", "path/to/image/no-image.gif"
					 );*/
					//       $(this).hide();
					$(this).remove();
				} 
		 	});
			$('.minds-content-loading').remove();
			$list.show();
			$('.load-more').show();
			$list.masonry(
			{
				stamp: '.minds-fixed-post-box'
			}
			);                        
		});
		$('.elgg-list.mason').imagesLoaded().always(function(){
			console.log('all images loaded');
		});
		
		$('.hero > .topbar').on('mouseenter', '.right', function(e){ $('.topbar .right .social-login').show(); });
		$('.hero > .body').on('click', function(e){ $('.topbar .right .social-login').hide(); });
		
		$('.elgg-form-blog-save, .elgg-form-archive-save').submit(function(e){
 			var c = confirm("Please confirm you understand the selected license, and that you either own or have permission under the terms of the license.");
			return c;
		});

		if($(window).width() > 700){
			//$('.elgg-menu li a').tipsy({gravity: 'n'}); 
			$('.progress_indicator').tipsy({gravity: 'e'});		
			$('.elgg-input-text').tipsy({gravity: 'w'});
			$('.tooltip.s').tipsy({gravity: 's'});
			$('.tooltip.n').tipsy({gravity: 'n'});
			$('.tooltip.w').tipsy({gravity: 'w'});
			$('.tooltip.e').tipsy({gravity: 'e'});
		}
	
		$('.elgg-form-wall-add textarea').focus( function(e){ $(this).parent().find('.elgg-button-submit').css('display','block');});

		$('.thumbnail-tile').hover(
			function(){
		   		$(this).children('.hover').fadeIn('fast');
			},
			function(){
		   		$(this).children('.hover').fadeOut('fast');
		}); 

		//handle cookie session messages
		var msgs = $.cookie('mindsMessages');
		if(msgs && !elgg.is_logged_in()){
			var msgs = JSON.parse(msgs);
			if(msgs.error)
				elgg.register_error(msgs.error);

			if(msgs.success)
				elgg.system_message(msgs.success);

			$.removeCookie('mindsMessages', { path: '/' });
		}
		//elgg.system_message(msg);
	
		$(document).on('click', '.load-more', minds.loadMore);
		$(document).on('trigger', '.load-more', minds.loadMore);
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
		
		if($(this).hasClass('ajax-non-action')){
			elgg.get($(this).attr('href'), {
                success: function(data) {
          			item.effect('drop'); 
                 },
				error: function(data){
                }
       		 });
			
			return true;
		}
		
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
			elgg.post($(this).attr('href') + '?ajax=true', {
            	success: function(data) {
					if(item.length){
					//we are in a list so we want to add a remind item in
					}
					button.css('color', '#4690D6');        
				/*	button.effect('explode'); */      
		  		 },
				error: function(data){}
            });
        }

	 minds.onScroll = function(){
	 	$loadMoreDiv = $(document).find('.load-more');
	 	//go off the loadmore button being available
	 	if($loadMoreDiv){
	 		$list = $loadMoreDiv.parent();
	 		if($(window).scrollTop() + $(window).height() > $(document).height() * 0.1){
	 			if(!$loadMoreDiv.hasClass('loading')){
	 				$loadMoreDiv.trigger('trigger');
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
		$list.find('.load-more').html('<p class="dots">...</p><p class="message"> loading, please hold tight </p>');
		
		$list.find('.load-more').addClass('loading');
			
		var loc =  elgg.normalize_url(elgg.parse_url(location.href).path);
		if($list.find('.load-more').attr('next-uri'))
			loc = elgg.normalize_url(elgg.parse_url($list.find('.load-more').attr('next-uri')).path);


/*		if(loc == elgg.get_site_url()){
			loc = location.href + 'news/featured';
		}
*/
		var offset = 0;

		$params = elgg.parse_str(elgg.parse_url(location.href).query);
			
		if(loc.indexOf('trending') > -1 <?php if(Minds\Core\plugins::isActive('analytics')){ echo "|| loc.indexOf('view') > -1"; }?> || $params.filter == 'trending' || loc.indexOf('search') > -1){
			offset = $list.find('.elgg-list').children().length;
		} else {
			offset = $('.load-more').attr('data-load-next');
		}
		//console.log(offset);
		if(!offset){
			window.lock_autoscroll = false;
			return false;
		}
		$params = $.extend($params, {
			path : loc,
			items_type: $list.find('.elgg-list').hasClass('elgg-list-entity') ? 'entity' :
				$list.find('.elgg-list').hasClass('minds-list-river') ? 'river' :
				$list.hasClass('elgg-list-annotation') ? 'annotation' : 
				$list.find('.elgg-list').hasClass('minds-search-list') ? 'search' : 'entity',
			offset:offset,
			limit:12 
		});
		url = "/ajax/view/page/components/ajax_list?" + $.param($params);
			
		elgg.get(url, {
			success: function(data) {
			//$list.toggleClass('infinite-scroll-ajax-loading', false);
				
				if($(data).contents().length == 0){
					
					$list.find('.load-more').html('<p>Sorry, there is no more content.</p>');
					
				} else {
				
					$list.find('.load-more').remove();
				
					var el = $(data).contents().unwrap();
					
					if(loc == elgg.get_site_url() || loc == elgg.get_site_url() + 'blog/list/featured' || loc == elgg.get_site_url() + 'channels/featured'){
						offset = $(data).find('li.elgg-item:last').attr('featured_id');
   					} else {
						offset = $(data).find('li.elgg-item:last').attr('id'); 
                   	}
                   	
                   	$list.append('<div class="news-show-more load-more" data-load-next="'+offset+'"><p>Downloading images...</p></div>');
					
					el.imagesLoaded().always(function(){
						el.find('.rich-image').each(function(){ 
							var image = $(this);
							if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
								image.remove();
							}	

						});
						$list.find('.elgg-list').append(el).masonry('appended', el);
						window.lock_autoscroll = false;
						$list.find('.load-more').remove();
						$list.append('<div class="news-show-more load-more" data-load-next="'+offset+'">click for more</div>');
					});

					// Trigger a hook for extra tasks after content is loaded
					elgg.trigger_hook('loadMore', 'minds');
				}
			},
			error : function(data){
				$list.find('.load-more').html('<p>Sorry, there is no more content.</p>');
			}
			
		});
		 
	 };	
	 
$('.post-post-preview').on('preview', function(e, url){
	var $elem = $(this);
	var $form = $elem.closest('form');
	
	url = url.replace("http://", '');
	url = url.replace("https://", '');
	
	if (url) {
		elgg.get('https://iframely.com/iframely', {
			dataType: "json",
			data: {
				uri: url
			},
			beforeSend: function() {
				console.log('sending');
				//$preview.addClass('elgg-state-loading');
			},
			success: function(data) {
				console.log(data.links);
				
				
				$('.post-post-preview').show();
		
				if(data.meta.title){
					$('.post-post-preview .post-post-preview-title').val(data.meta.title);
				}
				
				if(data.meta.description){
					$('.post-post-preview .post-post-preview-description').html(data.meta.description);
				}
				
				
				data.links.forEach(function(link) {
					console.log($.inArray('thumbnail', link.rel));
					if($.inArray('thumbnail', link.rel)>=0){
						$('.post-post-preview .post-post-preview-icon-img').attr('src', link.href);
						$('.post-post-preview .post-post-preview-icon').val(link.href);
					}
				});
				
				$('.post-post-preview .post-post-url').val(url);
			//	var icon = data.
				//$('.deck-post-preview .deck-post-preview-icon').html('<img src=""');
				
				//$elem.closest('.wall-input-url').show();
				//urlLoaded =true;
			}
		});
	}
	/*} else if (!$preview.html()) {
		$preview.html(framework.wall.loadedPreviewHtml);
		if (typeof oembed !== 'undefined') {
			$preview.find('a[title^=oembed]').oembed(null, {
				embedMethod: 'fill',
				maxWidth: 500
			});
		}
	}*/
});


$('#post-input-box').on('keyup', function(e){
	var $form = $(this).closest('form');

	var text = $(this).val();
	var match = text.match(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig);
	if (!match) {
		return;
	}

	if (match instanceof Array) {
		var url = match[0];
	} else {
		var url = match;
	}

	if (url.length) {
		$('.post-post-preview').trigger('preview', url);
	}
});

var urlLoaded = false;

<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

