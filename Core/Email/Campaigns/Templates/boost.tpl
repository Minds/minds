<?php
    $boostUrl = "{$vars['site_url']}boost/console?{$vars['tracking']}";
    $avatarUrl = "{$vars['user']->getIconUrl()}";
?>

<table cellspacing="8" cellpadding="8" border="0" width="600" align="center">
  <tbody>
        <tr>
            <td>
                <table>
                    <tr>
                        <td style="width: 30%;">
                            <a href="<?php echo $boostUrl; ?>">
                                <img alt="<?php echo $vars['username']; ?>" src="<?php echo "{$vars['site_url']}api/v2/media/magicproxy?size=90&amp;src={$avatarUrl}&amp;roundX=45&amp;roundY=45"; ?>"/>
                            </a>
                        </td>
                        <td style="width: 70%">
                            <h4 <?php echo $emailStyles->getStyles('m-clear', 'm-fonts', 'm-header'); ?>><p>Your boost of <?php echo $vars['boost']['impressions'] ?> views is complete</p></h4>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <a href="<?php echo $boostUrl; ?>">
                                 <img src="<?php echo $vars['cdn_assets_url']; ?>assets/emails/cta_view_boost.png" width="142" alt="View Boost"/>
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <p <?php echo $emailStyles->getStyles('m-clear', 'm-fonts'); ?>>
                    For any issues, including the recipient not receiving tokens, please contact us at <a href="mailto:info@minds.com">info@minds.com</a>.
                </p>
            </td>
        </tr>
    </tbody>
</table>
