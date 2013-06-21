<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<a class="minds-subscribe" href="<?php echo elgg_get_site_url(); ?>widgets/<?php echo $vars['tab']; ?>/service/?channel_guid=<?php echo elgg_get_logged_in_user_guid(); ?>" onClick='window.open(this.href, "Subscribe to channel...", "width=800,height=600"); return false;'>Subscribe to @<?php echo elgg_get_logged_in_user_entity()->username;?>'s channel...</a>
