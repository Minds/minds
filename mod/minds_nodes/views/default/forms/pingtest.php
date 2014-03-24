<?php

$user = elgg_get_logged_in_user_entity();

$domain_link = "http://". $vars['domain'] . "/install.php?username=".urlencode($user->username) . "&name=".urlencode($user->name) . "&email=".urlencode($user->email);

$ping = get_input('ping', false);
if($ping){
	elgg_set_viewtype('json');
	if($result = file_get_contents($domain_link)){
		echo true;
	} else {
		echo false;
	}
	exit;
}

?>

<div id="pingtest-results">
<div class="minds-body-header">
	<div class="inner">
		<div class="elgg-head clearfix">
			 <h2>Please wait...</h2>
			<h3>Your node is currently launching. You will be forwarded shortly.</h3>
		</div>	
	</div> 
</div>
</div>

<div id="pingtest-fail" style=display:none;>
<div class="minds-body-header">
        <div class="inner">
                <div class="elgg-head clearfix">
 		 	<h2>Hmmmmm... something is not right</h2>
    			<h3>Your new node (<?php echo $vars['domain']; ?>) could not be reached. If you are using a custom domain, it may be that you need to modify your DNS settings.</p>
    			<h4>DNS details</h4>
 		   	<div class="dns">
      				<p><label>Domain:</label> <?php echo $vars['domain']; ?><br />
        			<label>IP Address:</label> <?php echo $CONFIG->multisite_server_ip; ?></p>
   			 </div>
    
   			 <br />
    
    
  			  <p>If you believe this to be a mistake, you could try <a href="<?php echo $domain_link; ?>">going there anyway...</a></p>
		</div>
	<div>
</div>
</div>

<script>

    // Change message after a period of time
    setTimeout(function() {
        //  $('#pingtest-results').html('<p>Sorry, the test timed out trying to reach <?php echo $vars['domain'];?>. You could try <a href="<?php echo $domain_link; ?>">going there anyway...</a></p>');
   		$('#pingtest-results').fadeOut();
        $('#pingtest-fail').fadeIn();
    }, 1000000);
    
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()  {
		if (xmlhttp.responseText == 1){
   	 		window.location = "<?php echo $domain_link; ?>";
		} else {
			//$('#pingtest-results').fadeOut();
                            //   $('#pingtest-fail').fadeIn();
		}
  	}
	xmlhttp.open("GET","?domain=<?php echo $vars['domain'];?>&ping=true",true);
	xmlhttp.send();
</script>
