<div id="ajax-admin-panel">
    
   <?php echo $vars['default'];?>
   
</div>

<script>
    $(document).ready(function(){
	
	$('.elgg-child-menu a').click(function(){
	    
	    var href = $(this).attr('href');
	    
	    $('#ajax-admin-panel').fadeOut(0, function() {
			$('#ajax-admin-panel').load(href, function() {
				window.history.pushState("string", "Admin panel", href);
			    $('#ajax-admin-panel').fadeIn(0);
			});
	    });
	    
	    return false;
	});
	
    });
    </script>
