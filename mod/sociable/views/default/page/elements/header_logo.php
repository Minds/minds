<?php
/*
 * Project Name:            Sociable Theme
 * Project Description:     Theme for Elgg 1.8
 * Author:                  Shane Barron - SocialApparatus
 * License:                 GNU General Public License (GPL) version 2
 * Website:                 http://socia.us
 * Contact:                 sales@socia.us
 * 
 * File Version:            1.0
 * Last Updated:            5/11/2013
 */
$last = substr(elgg_get_site_entity()->name, 1);
$first = elgg_get_site_entity()->name[0];
$url = $CONFIG->url;
$username = elgg_get_logged_in_user_entity()->username;
$context = elgg_get_context();
$messages = messages_count_unread();
if (!elgg_is_logged_in()) {
    ?>
    <div id="sociaLogin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="sociaLoginLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="sociaLoginLabel"><?php echo elgg_echo("login"); ?></h3>
        </div>
        <div class="modal-body">
            <?php echo elgg_view_form("login"); ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-info socia_register" data-dismiss="modal">Register</button>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
        </div>
    </div>
    <div id="sociaRegister" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="sociaRegisterLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="sociaRegisterLabel"><?php echo elgg_echo("register"); ?></h3>
        </div>
        <div class="modal-body">
            <?php echo elgg_view_form("register"); ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-info socia_login" data-dismiss="modal">Login</button>
            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="span5">
        <div class="elgg-heading-site">
            <div class="elgg-heading-site-logo">
                <div class="logo-first"><?php echo $first; ?></div>
                <div class="logo-last"><?php echo $last; ?><br/>
                    <div class="elgg-heading-site-description">
                        <?php echo elgg_get_site_entity()->description; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="span7 pull-right">
        <ul class="nav nav-pills pull-right">
            <?php if (elgg_is_logged_in()) { ?>
                <?php if (elgg_is_admin_logged_in()) { ?>
                    <li class="active"><a href="<?php echo $CONFIG->url; ?>admin">Admin</a></li>
                <?php } ?>
                <li <?php if ($context == "profile") echo "class='active'"; ?>><a href="<?php echo $url; ?>profile/<?php echo $username; ?>">Profile</a></li>
                <li <?php if ($context == "dashboard") echo "class='active'"; ?>><a href="<?php echo $url; ?>dashboard">Dashboard</a></li>
                <li <?php if ($context == "messages") echo "class='active'"; ?>><a href="<?php echo $url; ?>messages/inbox/<?php echo $username; ?>"><b style="color:#DC1010;"><?php echo $messages; ?></b> Messages</a></li>
                <li <?php if ($context == "settings") echo "class='active'"; ?>><a href="<?php echo $url; ?>settings/user/<?php echo $username; ?>">Settings</a></li>
                <li><a href="<?php echo $url; ?>action/logout">Logout</a></li>
            <?php } else { ?>
                <li><a class="socia_login" href="<?php echo $CONFIG->url; ?>login">Login</a></li>
                <li><a class="socia_register" href="<?php echo $CONFIG->url; ?>register">Register</a></li>
                <?php } ?>
        </ul>
    </div>

</div>
<script>
    (function() {
        $(".elgg-heading-site").click(function() {
            window.location = elgg.config.wwwroot;
        });
    })();
</script>