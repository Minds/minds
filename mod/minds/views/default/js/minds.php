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
	
     
<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

