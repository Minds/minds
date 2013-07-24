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
				echo elgg_view('input/text', array('name'=>'params[profile_id]', 'value'=>elgg_get_plugin_setting('profile_id', 'analytics')));
			}
	    	?>
	    </div>
	</div>
</div>
<div class="elgg-module elgg-module-info">
  <div class="elgg-head">
                <h3><?php echo elgg_echo('analytics:tracking_id'); ?></h3>

        </div>
        <div class="elgg-body">
            <div>
                <?php
   	                   //authenticated, so show revoke button
                           echo elgg_view('input/text', array('name'=>'params[tracking_id]', 'value'=>elgg_get_plugin_setting('tracking_id', 'analytics')));
                ?>
            </div>
        </div>
</div>
