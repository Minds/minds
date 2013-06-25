<?php
	
	$widget_video_width = $vars['entity']->widget_video_width;
	$widget_video_height = $vars['entity']->widget_video_height;
	$widget_video_url = $vars['entity']->widget_video_url;
	$widget_video_caption = $vars['entity']->widget_video_caption;
	
	if (!isset($widget_video_width)){
		$widget_video_width = '250';
	}
	if (!isset($widget_video_height)){
		$widget_video_height = '250';
	}

?>

<div class="contentWrapper" align="center">
	  <a id="media<?php echo $vars['entity']->getGUID();?>" class="media" href="<?php echo $widget_video_url;?>"><?php echo $widget_video_title;?></a>    <div class="clearfloat">
    </div>
</div>
<?php if (isset($widget_video_url)) { ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#media<?php echo $vars['entity']->getGUID();?>').media({width:<?php echo $widget_video_width;?>, height:<?php echo $widget_video_height;?>, autoplay: true});
    });
</script>
<?php } ?>