<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>

elgg.provide('minds.payments');

minds.payments.init = function() {
	
	/**
	 * Step 1. Selecting the tier
	 */
	$(document).on('click', '.tax-options .option', function(e){
		e.preventDefault();
		
		$('.tax-form-container').hide();
		
		if($(this).hasClass('w9')){
			$('.w9-form').show();
		} else {
			$('.w8ben-form').show();
		}
		
	});
	
}
elgg.register_hook_handler('init', 'system', minds.payments.init);
