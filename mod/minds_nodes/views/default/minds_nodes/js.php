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
			$('.payment').hide();
		} else {
			$('.domain .paid').css('display', 'table-row');
			$('.domain .cell.free').show();
			$('.payment').show();
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
		
		//remove spaces
		if (e.keyCode == 32) { 
      		$(this).val($(this).val().replace(/ +?/g, ''));
		}
		
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
								//skip to referrer
								$('.launch .response').css('display', 'table-row');
							} else {
								//payment is the nect step
								$('.payment').removeClass('hide');
								$('.payment input').enable();
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
		elgg.action( elgg.get_site_url() + 'action/register',{
			data : {
				u : $('.account input[name=username]').val(),
				e : $('.account input[name=email]').val(),
				p : $('.account input[name=password]').val(),
				tcs : true
			},
			success : function(data){
		
				if(data.error){
					$('.account .response .response').text('Sorry, we couldn\'t create an account with those details. Please try another username.');
					return false;
				}
				
				$('.account .input').hide();
					
				$('.payment').removeClass('hide');
				$('.payment input').enable();
				$('.account .response .response').text('Your account was created.');
				
				if(minds.nodes.price == 0){
					$('.launch .response').css('display', 'table-row');
				}
				
			}, 
			error : function(data){
				$('.account .response .response').text('Sorry, we couldn\'t create an account with those details. Please try another username.');
			}
		});
		
	
	});
	
	
	/**
	 * Step 4. Payment. Bypasses if free. 
	 * 
	 * 1) Accept card details (really?)
	 * 2) Poll minds.com to see if the payment has been verified. 
	 */
	$(document).on('click', '.payment .create', function(e){
		$('.payment .response .cell').text('Please wait whilst we authorize your request. This may take a few moments...');
		$('.payment .input').hide();
		elgg.action( elgg.get_site_url() + 'action/payment',
			{
				data : {
					tier_guid : minds.nodes.tier,
					type : minds.nodes.card_type.toLowerCase(),
					number : $('input[name=number]').val(),
					sec : $('input[name=sec]').val(),
					month : $('select[name=month] option:selected').text(),
					year : $('select[name=year] option:selected').text(),
					name : $('input[name=name]').val(),
					name2 : $('input[name=name2]').val()
				},
				success: function(data){
					if(data.success){
						$('.payment .response .cell').text('Success. Your payment is now being processed. Please proceed to launch your node by following the steps below.');
						minds.nodes.transaction_id = data.success.transaction_id;
						
						$('.launch .response').css('display', 'table-row');
					} else {
						$('.payment .response .cell').text('We could not authorize your request. Please check your details or use another card.');
						$('.payment .input').css('display', 'table-row');
					}
				}
		});
	});
	$(document).on('click', '.payment .type a', function(e){
		$(this).css('color', '#333');
		minds.nodes.card_type = $(this).text();
	});
	
	/**
	 * Final step. 
	 * Launch
	 */
	$(document).on('click', '.launch .create', function(e){
		
		elgg.action( elgg.get_site_url() + 'action/registernewnode',{
				data : {
					domain : minds.nodes.domain,
					tier_guid : minds.nodes.tier,
					transaction_id : minds.nodes.transaction_id,
					referrer : $('.user-lookup').val()
				},
				success : function(data){
					window.location.href = elgg.get_site_url() + 'nodes/ping?domain=' + minds.nodes.domain;
				}
		});
		
	});
}
elgg.register_hook_handler('init', 'system', minds.nodes.init);
