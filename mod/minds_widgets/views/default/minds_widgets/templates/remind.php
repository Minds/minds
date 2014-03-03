<?php /*<a class="entypo minds-remind" href="<?php echo minds_widgets_remove_url_schema(elgg_get_site_url()); ?>widgets/<?php echo $vars['tab']; ?>/service/" title="ReMind (repost)" onClick='window.open(this.href + "?url=" + encodeURIComponent(document.URL), "Remind", "width=800,height=600"); return false;'><span class="mind_graf" style="display:none;">&#59159;</span><span class="mind_fall">Remind</span></a>
<script type="text/javascript">
    
    var style = document.createElement('style');
    style.textContent = '@import "' + '<?php echo minds_widgets_remove_url_schema(elgg_get_site_url()); ?>widgets/<?php echo $vars['tab']; ?>/css' + '"';
    document.getElementsByTagName("body")[0].appendChild(style);
</script> */ ?>

<iframe  height="16" width="20" frameborder="0" seamless="true" scrolling="no" src="<?php echo minds_widgets_remove_url_schema(elgg_get_site_url()); ?>widgets/<?php echo $vars['tab']; ?>/service/?url=<?php echo urlencode(get_input('url')); ?>&embed=yes"></iframe>