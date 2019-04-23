<?php
    $wireUrl = "{$vars['site_url']}wallet/tokens/transactions/{$vars['contract']}?{$vars['tracking']}";
    $avatarUrl = "{$vars['sender']->getIconUrl()}";
    $wireDate = date('M d, Y', ($vars['timestamp'])); 
    $amount = number_format($vars['amount'], 2);
?>
<table cellspacing="8" cellpadding="8" border="0" width="600" align="center">
  <tbody>
        <tr>
            <td>
                <table>
                    <tr>
                        <td style="width: 30%;">
                            <a href="<?php echo $wireUrl; ?>">
                                <img alt="<?php echo $vars['sender']->get('name'); ?>" src="<?php echo "{$vars['site_url']}api/v2/media/magicproxy?size=90&amp;src={$avatarUrl}&amp;roundX=45&amp;roundY=45"; ?>"/>
                            </a>
                        </td>
                        <td style="width: 70%">
                            <h4 <?php echo $emailStyles->getStyles('m-clear', 'm-fonts', 'm-header'); ?>>@<?php echo $vars['sender']->get('name'); ?> wired you</h4>
                            <p <?php echo $emailStyles->getStyles('m-fonts', 'm-subtitle', 'm-clear'); ?>>Transfer Date and Amount:</p>
                            <p <?php echo $emailStyles->getStyles('m-fonts', 'm-subtitle', 'm-clear'); ?>>
                                <?php echo $wireDate; ?>; +<?php echo $amount ?> tokens
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <a href="<?php echo $wireUrl; ?>">
                                 <img src="<?php echo $vars['cdn_assets_url']; ?>assets/emails/cta_view_wire.png" width="142" alt="View Wire"/>
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
