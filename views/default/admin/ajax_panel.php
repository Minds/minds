<div id="ajax-admin-panel">
    
    <h1>Configure your site...</h1>
</div>

<script>
    $(document).ready(function(){
	
	$('.elgg-child-menu a').click(function(){
	    
	    var href = $(this).attr('href');
	    
	    $('#ajax-admin-panel').fadeOut(400, function() {
		$('#ajax-admin-panel').load(href, function() {
		    $('#ajax-admin-panel').fadeIn();
		});
	    });
	    
	    return false;
	});
	
    });
    </script>