<?php 
	
	$icon_types = "general";
	if($plugin_icon_types = elgg_get_plugin_setting("icon_types", "image_captcha")){
		$icon_types = $plugin_icon_types;
	}
	
	$imageW = '35';
	
	if($icon_types == "fruit"){
		$values = array('apple','strawberry','lemon','cherry','pear');
		$imageH = '33';
	} else {
		$values     = array('house','folder','monitor','man','woman','lock','rss');
		$imageH = '35';
	}
	
	$imageExt = 'jpg';
	$imagePath = elgg_get_site_url() . 'mod/image_captcha/_graphics/icons/' . $icon_types . "/";
	
	$rand = mt_rand(0,(sizeof($values)-1));
	
	shuffle($values); // randomize icons on every reload
	
	$s3Capcha = "<label>" . elgg_echo("image_captcha:label", array(elgg_echo("image_captcha:icon_types:" . $icon_types . ":" . $values[$rand]))) ."</label>";
	$s3Capcha .= "<div>";
	
	for($i=0; $i < sizeof($values); $i++){
	    $value2[$i] = mt_rand();
	    $image_url = $imagePath . $values[$i] . '.' . $imageExt;
	    
	    $s3Capcha .= "<div>";
	    $s3Capcha .= "<span>" . $values[$i] . " <input type='radio' name='image_captcha' value='" . $value2[$i] . "'></span>";
	    $s3Capcha .= "<div style='background: url(\"" . $image_url . "\") bottom left no-repeat;' class='img'></div>";
	    $s3Capcha .= "</div>";
	}
	
	$s3Capcha .= "</div>";
	
	// save in session
	$_SESSION['image_captcha'] = $value2[$rand];
	
	$captcha_output = "<div id='image_captcha'>" . $s3Capcha . "</div>";

	?>
	<script type="text/javascript" src="<?php echo $vars["url"]; ?>mod/image_captcha/vendors/s3capcha/s3capcha.js"></script>
	<?php echo $captcha_output; ?>
	<div class="clearfloat"></div>
	<style type="text/css">
		#image_captcha div {
		    float: left;
		}   
		
		#image_captcha .img {
			cursor:pointer;
			display:none;
			width: <?php echo $imageW; ?>px;
			height: <?php echo $imageH; ?>px;
		}
	</style>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#image_captcha').s3Capcha();
		});
	</script>