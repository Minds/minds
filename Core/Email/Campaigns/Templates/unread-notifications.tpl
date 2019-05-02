<?php
     $avatarUrl = $vars['user']->getIconUrl();
     $notificationsUrl = "{$vars['site_url']}notifications?{$vars['tracking']}";
?>
<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
    <tbody>
    <tr>
        <td>
                <table>
                    <tr>
                        <td style="width: 30%;">
                            <a href="<?php echo $notificationsUrl; ?>">
                                <img alt="<?php echo $vars['username']; ?>" src="<?php echo "{$vars['site_url']}api/v2/media/magicproxy?size=90&amp;src={$avatarUrl}&amp;roundX=45&amp;roundY=45"; ?>"/>
                            </a>
                        </td>
                        <td style="width: 70%">
                            <h4 <?php echo $emailStyles->getStyles('m-clear', 'm-fonts', 'm-header'); ?>>
                                You have <?php { echo $vars['amount']; }?> unread notification<?= $vars['amount'] > 1 ? 's' : ''?>.
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <a href="<?php echo $notificationsUrl; ?>">
                                 <img src="<?php echo $vars['cdn_assets_url']; ?>assets/emails/cta_view_notifications.png" width="142" alt="View Notifications"/>
                            </a>
                        </td>
                    </tr>
                </table>
        </td>
    </tr>
    </tbody>
</table>
