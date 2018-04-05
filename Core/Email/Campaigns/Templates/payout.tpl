<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
    <tbody>
    <tr>
        <td height="20px"></td>
    </tr>
    <tr>
        <td>
            We have sent a payout of $<?php echo $vars['amount'];?> to you
        </td>
    </tr>
    <tr>
        <td height="20px"></td>
    </tr>

    <tr>
        <td style="font-weight:bold; font-size: 18px; letter-spacing:0.2px">
            Bank Account: <?php echo $vars['bankAccount'];?>
        </td>
    </tr>
    <tr>
        <td style="font-weight:bold; font-size: 18px; letter-spacing:0.2px">
            Date of Dispatch: <?php echo $vars['dateOfDispatch'];?>
        </td>
    </tr>

    <tr>
        <td height="20px"></td>
    </tr>
    </tbody>
</table>