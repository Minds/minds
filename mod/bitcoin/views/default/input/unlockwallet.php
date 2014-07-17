
<a id="unlock-<?php echo $vars['wallet']->guid; ?>" rel="popup" href="#unlock-<?php echo $vars['wallet']->guid; ?>-form">Unlock wallet</a>

<div id="unlock-<?php echo $vars['wallet']->guid; ?>-form" class="elgg-module-popup hidden">
    <?php echo elgg_view_form('bitcoin/unlock', null, $vars); ?>
</div>
<script>
    $(document).ready(function(){
		
	$('#unlock-<?php echo $vars['wallet']->guid; ?>').click(function(){
	    
	    $('#unlock-<?php echo $vars['wallet']->guid; ?>-form').find( "form" ).on( "submit", function( event ) {
		<?php
		    $url = elgg_add_action_tokens_to_url(elgg_get_site_url() . 'action/bitcoin/unlock', false);
		?>
		
		elgg.action("<?php echo $url; ?>&" + $('#unlock-<?php echo $vars['wallet']->guid; ?>-form').find( "form" ).serialize(), { 
		    contentType : 'application/json',
		    success : function(data) {
			window.location.reload();
		    }
		});
		
		event.preventDefault();
		
	    });
	    
	    <?php /*$('#unlock-<?php echo $vars['wallet']->guid; ?>-form').dialog({
	
		modal: true,
		height: 300,
		width: 350
		
	    }).show();*/ ?>
	});
    });
</script>