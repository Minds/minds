<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>
elgg.provide('minds.cms');

minds.cms.init = function() {
    	
    $('.cms-section-add > a').on('click', function(e){
    	e.preventDefault();
    	$.ajax({
    		url : elgg.get_site_url() + 'admin/cms/sections/' + $(this).data('group'),
    		method : 'post',
    		success : function(data){
    			alert('ok!');
    		}
    	});
    });
    	
}
	
elgg.register_hook_handler('init', 'system', minds.cms.init);