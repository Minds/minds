<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>

elgg.provide('minds.nodes');

minds.nodes.init = function() {
	
	/**
	 * Step 1. Selecting the tier
	 */
	$(document).on('click', '#tier-select-button', function(e){
		e.preventDefault();
		$('.domain').removeClass('hide');
		$(window).scrollTop($('.domain').offset().top);
		
		$('.domain .cell input').enable();
		
		minds.nodes.tier = $(this).attr('data-guid');
		minds.nodes.price = $(this).data('price');
		
		if($(this).data('price') == 0){
			$('.domain .paid').hide();
			$('.domain .cell.free').show();
		} else {
			$('.domain .paid').css('display', 'table-row');
			$('.domain .cell.free').show();
		}
		
	});
	
	
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
		    clearTimeout (timer);
		    timer = setTimeout(callback, ms);
		  };
	})();
	
	/**
	 * Step 2. Select the domain. Do a quick check to see if it's available 
	 * @todo should we reserver?
	 */
	$(document).on('keyup', '.domain input', function(e){
		e.preventDefault();
		
		if($(this).parent().hasClass('paid')){
			/**
			 * We can't check personal domains..
			 */
			minds.nodes.domain = $(this).val();
			$('.availability').show().text('Please follow the DNS setup options when using your own domain');
			$('.account').removeClass('hide');
			return true;
		} else {
			minds.nodes.domain = $(this).val() + '.minds.com';
		}
		
		delay(function(){
			$('.availability').show().text('Checking, please wait...');
			$.ajax({
				url: elgg.get_site_url() + 'action/checkdomain',
				data : {
					domain : minds.nodes.domain
				},
				success: function(data){
					$('.availability').show().text(data);
					if(data == 'ok'){
						$('.availability').show().text('Ok! That domain is free.');
						
						if(elgg.is_logged_in()){
							console.log(minds.nodes.price);
							if(minds.nodes.price == 0){
								//launch this thing
								alert('good to go');
							} else {
								//probe payment
							}
						} else {
							$('.account').removeClass('hide');
							$('.account input').enable();
							$(window).scrollTop($('.account').offset().top);
						}
						
					} else {
						$('.account').addClass('hide');
					}
				},
			});
		}, 500);
	});
	
	/**
	 * Step 3. Create an account. 
	 * Only if a user is not logged
	 */
	$(document).on('click', '.account .create', function(e){
		$('.account .input').hide();
		$('.account .response .cell').text('password typed');
		$('.payment').removeClass('hide');
	});
	
	
	/**
	 * Step 4. Payment. Bypasses if free. 
	 * 
	 * 1) Accept card details (really?)
	 * 2) Poll minds.com to see if the payment has been verified. 
	 */
	
}
elgg.register_hook_handler('init', 'system', minds.nodes.init);
