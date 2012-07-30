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
	 
	 //Comments (AJAX) JS
	  elgg.provide('minds.comments');
	  	  
	  minds.comments.init = function() {
		  $('.ajax-comment-save')
			.removeAttr('onsubmit')
			.unbind('submit')
			.bind('submit', minds.comments.saveComment);
			
			
	  };
	 
	 
	 minds.comments.saveComment = function(event) {
        event.preventDefault();

        var     values = $(this).serialize(),
        		action = $(this).attr('action');
				
				
  

        var input = $('input[name=generic_comment]', $(this));
		
		var responses = $(this).parents('.elgg-river-responses');

        input.addClass('ajax-input-processing');

        elgg.action(action + '?' + values, {
	    contentType : 'application/json',
            success : function(json) {
				
				var ul = responses.find('ul.elgg-river-comments');
								

				if (ul.length < 1) {
					responses.prepend(json.output);
				} else {
					ul.append($(json.output).find('li:first'));
				};
								
				
				responses.find(input).val('');
								

                input
                .removeClass('ajax-input-processing')
                .val('')
                .parents('div.ajax-comments-input:first')
                .toggle();
            }
        });
    }
	
	elgg.register_hook_handler('init', 'system', minds.comments.init);
     
<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

