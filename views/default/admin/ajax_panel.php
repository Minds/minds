<div id="ajax-admin-panel">
    
    <h1>Configure your site...</h1>
</div>

<script>
    $(document).ready(function(){
	
	$('.elgg-child-menu a').click(function(){
	    
	    var href = $(this).attr('href');
	    
	    $('#ajax-admin-panel').load(href);
	    
	    return false;
	});
	
    });
    </script>