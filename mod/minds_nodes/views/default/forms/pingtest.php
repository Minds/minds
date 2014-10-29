<?php

$user = elgg_get_logged_in_user_entity();
$domain = $vars['domain'];

//due to dns propagation we sent to a temporary subdomain
if(strpos($domain, '.minds.com') === false){
	$domain = str_replace('.', '-', $domain) . '-custdom-001.minds.com';
}

$domain_link = "http://". $domain . "/install.php?username=".urlencode($user->username) . "&name=".urlencode($user->name) . "&email=".urlencode($user->email) . "&ts=".time(); 

$ping = get_input('ping', false);
if($ping){
	elgg_set_viewtype('json');
	
	$ch = curl_init($domain_link);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,3000);
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
 		 	<h2>Nearly there..</h2>
    			<h3>There was a delay in launching your site. Please wait a moment and then try <a href="<?= $domain_link ?>" going to <?= $domain_link?></a></h3>
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
    }, 60000);
    
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
	xmlhttp.open("GET","?domain=<?php echo $domain;?>&ping=true",true);
	xmlhttp.send();
</script>
