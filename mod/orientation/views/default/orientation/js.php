<?php if(0){ ?><script type="application/javascript"><?php }?>

$('.orientation-content .connect-network').on('click', function(e){
	e.preventDefault();
	
	window.open($(this).attr('href'), "Authorize", "width=1200,height=500");
});
