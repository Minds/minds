<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
	 
	 elgg.provide('minds.social');
	 
	 minds.social.init = function() { 
	 	$('.social-post-icons').on('click','input', minds.social.post_options);
	 };
	 
	 minds.social.post_options = function(){
	 	console.log(this.checked);
	 	if(this.checked && $(this).attr('linked') == 'no'){
	 		if($(this).attr('name')=='facebook'){
	 			var url = elgg.get_site_url() + 'social/fb/popup';
	 		} else {
	 			var url = elgg.get_site_url() + 'social/twitter/popup';
	 		}
	 		window.open(url,'Connect','height=300,width=500');
	 	}
	 }
	
elgg.register_hook_handler('init', 'system', minds.social.init);	
     
<?php if (FALSE) : ?>
    </script>
<?php endif; ?>

