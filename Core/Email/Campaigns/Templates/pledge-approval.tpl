<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
    <tbody>
    <tr>
        <td height="20px"></td>
    </tr>
    <tr>
        <td>
            Your token <?php echo $vars['action'] ?> for
            <?php echo $vars['amount'] ?> ETH was approved.

            <?php if ($vars['isPresale']): ?>
                You will be able to buy your tokens as soon as the sale starts.
            <?php else: ?>
                You can buy your tokens now at the
                <a href="<?php echo Minds\Core\Config::_()->site_url; ?>token">Minds Token page</a>.
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td height="20px"></td>
    </tr>
    </tbody>
</table>
