<div class="oauth2-authorize">
		
	<div>
	    <?php echo elgg_echo('oauth2:authorize', array('<b>'.$vars['entity']->title.'</b>')); ?>
	</div>
	
	<br />
	
	<form name="authorize" action="<?php echo elgg_get_site_url(); ?>action/oauth2/authorize" method="get">
	
	    <?php echo elgg_view('input/securitytoken'); ?>
	
	    <input type="hidden" name="client_id" value="<?php echo $vars['client_id']; ?>" >
	    <input type="hidden" name="response_type" value="<?php echo $vars['response_type']; ?>" >
	    <input type="hidden" name="redirect_uri" value="<?php echo $vars['redirect_uri']; ?>" >
	
	    <input type="submit" name="submit" value="Authorize" class="elgg-button elgg-button-action">
	
	</form>
	
</div>