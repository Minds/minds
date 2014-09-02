<?php

$user = elgg_get_logged_in_user_entity();

$domain_link = "http://". $vars['domain'] . "/install.php?username=".urlencode($user->username) . "&name=".urlencode($user->name) . "&email=".urlencode($user->email) . "&ts=".time(); 

$ping = get_input('ping', false);
if($ping){
	elgg_set_viewtype('json');
	
	$ch = curl_init($domain_link);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,1500);
	curl_exec($ch);
	$errorno = curl_errno($ch);
	curl_close($ch);
	
	if(!$errono){
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
			
			<div class='loading-bar'>
				<div class="progress"></div>
			</div>
			
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

	var progress = 1;
	setInterval(function() {
		if(progress >= 100){
			return false;
		}
		progress = progress+1;
		$('.loading-bar .progress').css('width', progress + '%');
	}, 600);

    // Change message after a period of time
    setTimeout(function() {
   		$('#pingtest-results').fadeOut();
        $('#pingtest-fail').fadeIn();
    }, 36000);
    
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()  {
		if (xmlhttp.responseText == 1){
   	 		window.location = "<?php echo $domain_link; ?>";
		} else {
			console.log(xmlhttp.responseText);
			//$('#pingtest-results').fadeOut();
                            //   $('#pingtest-fail').fadeIn();
		}
  	}
	xmlhttp.open("GET","?domain=<?php echo $vars['domain'];?>&ping=true",true);
	xmlhttp.send();
</script>
