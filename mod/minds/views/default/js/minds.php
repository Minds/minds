<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
	 
	 //Main js that needs to be loaded
	 elgg.provide('minds');
	 
	 minds.init = function() {
		 

		$(".elgg-button.elgg-button-dropdown").mouseenter(function(){ 
			$("#login-dropdown-box").slideToggle("fast"); 
			$(this).toggleClass("elgg-state-active");
		});
		
		$("#login-dropdown").mouseleave(function(){
		  $(".elgg-button.elgg-button-dropdown").toggleClass("elgg-state-active");
		  $("#login-dropdown-box").slideToggle("fast"); 
		});
	 };
	 
	
	 elgg.register_hook_handler('init', 'system', minds.init);
	 
	 
	 //Extend the river
	 elgg.provide('river.extend');
	 
	 river.extend.init = function() {
		 
	
		 
	 };
	 var riverOffset = 20;
	 river.extend.trigger = function() {
			
			$list = $(this).parent();
			$('.news-show-more').html('loading...');
			
			$params = elgg.parse_str(elgg.parse_url(location.href).query);
			$params = $.extend($params, {
				path: location.href,
				items_type: $list.hasClass('elgg-list-entity') ? 'entity' :
							$list.hasClass('elgg-list-river') ? 'river' :
							$list.hasClass('elgg-list-annotation') ? 'annotation' : 'river',
				offset: riverOffset
			});
			url = "/ajax/view/page/components/ajax_list?" + $.param($params);
						
			elgg.get(url, function(data) {
				//$list.toggleClass('infinite-scroll-ajax-loading', false);
				
				if($(data).contents().length == 0){
					$('.news-show-more').removeAttr('onclick');
					$('.news-show-more').html('no more posts');
				} else {

				$('.elgg-list.elgg-list-river.elgg-river').append(data);
												
				
				
				riverOffset += +10;
			
				$('.news-show-more').html('more');
				
				}
			});
		 
	 };
	 
	
	 elgg.register_hook_handler('init', 'system', river.extend.init);
	
     
<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

