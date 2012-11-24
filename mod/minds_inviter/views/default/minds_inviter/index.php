<?php
/**
 * Minds Inviter Index
 *
 */
 
$services = minds_inviter_retrieve_services();
?>

	<div id="provider_container">
		
	<?php foreach($services as $service){ ?>
			<div class="provider">
				<a class="provider_launch" rel="<?php echo $service;?>" href="#" ><?php echo elgg_echo('minds_inviter:service:'. $service);?></a>
			</div>
	<?php } ?>
	
	</div>
		<script>

		function popupcenter(pageURL, title,w,h) {
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		}

			$(document).ready(function(){
				$(".provider_launch").click(function(){
					popupcenter("<?php echo elgg_get_site_url(); ?>invite/handler/"+$(this).attr('rel'),'provider',600,600);
				});
			});
			
</script>