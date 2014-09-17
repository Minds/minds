<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  <?php if(elgg_is_logged_in()){?>
  ga('create', 'UA-35146796-1', 'minds.com', {'userId': '<?php echo elgg_get_logged_in_user_guid(); ?>'});
  <?php }else{?>
	ga('create', 'UA-35146796-1', 'minds.com');
  <?php } ?> 
  ga('send', 'pageview');

</script>
