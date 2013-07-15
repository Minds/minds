<?php
/**
 * Analytics settings
 * 
 */
$client = analytics_register_client();
?>
<!--- Authentication SETUP --->
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
		<h3><?php echo elgg_echo('analytics:auth'); ?></h3>
        
	</div>
	<div class="elgg-body">
	    <div>
	    	<?php 
	    		if(!$client->getAccessToken()){
	    			//not authenticated, so show authentication button
					$authUrl = $client->createAuthUrl();
  					echo "<a class='login' href='$authUrl'>Authenticate</a>";
				} else {
					//authenticated, so show revoke button
				}
	    	?>
	    </div>
	</div>
</div>