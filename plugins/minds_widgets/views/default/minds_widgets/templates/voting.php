<?php /*<a class="entypo minds-vote-up" href="<?php echo elgg_get_site_url(); ?>widgets/<?php echo $vars['tab']; ?>/service/?vote=up&url=<?php echo urlencode(get_input('url')); ?>" title="Vote Up" onClick='window.open(this.href, "Vote Up", "width=800,height=600"); return false;'><span class="mind_graf" style="display:none;">&#128077;</span><span class="mind_fall">Up</span></a> <span class="mind_fall"> / </span>
<a class="entypo minds-vote-down" href="<?php echo elgg_get_site_url(); ?>widgets/<?php echo $vars['tab']; ?>/service/?vote=down&url=<?php echo urlencode(get_input('url')); ?>" title="Vote Down" onClick='window.open(this.href, "Vote Down", "width=800,height=600"); return false;'><span class="mind_graf" style="display:none;">&#128078;</span><span class="mind_fall">Down</span></a>
<script type="text/javascript">
    var style = document.createElement('style');
    style.textContent = '@import "' + '<?php echo elgg_get_site_url(); ?>widgets/<?php echo $vars['tab']; ?>/css' + '"';
    document.getElementsByTagName("body")[0].appendChild(style);
</script> */ ?>
<iframe  height="16" width="50" frameborder="0" seamless="true" scrolling="no"src="<?php echo minds_widgets_remove_url_schema(elgg_get_site_url()); ?>widgets/<?php echo $vars['tab']; ?>/service/?url=<?php echo urlencode(get_input('url')); ?>&embed=yes"></iframe>