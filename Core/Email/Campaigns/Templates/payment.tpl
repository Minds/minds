<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
    <tbody>
    <tr>
        <td height="20px"></td>
    </tr>
    <tr>
        <td style="font-weight:bold; font-size: 18px; letter-spacing:0.2px">
            You have received a payment on Minds:
        </td>
    </tr>

    <tr>
        <td height="20px"></td>
    </tr>
    <tr>
        <td>
            <table cellspacing="0" cellpadding="10"
                    style="width:100%; border: 1px solid #d1d1d1; font-size:18px; letter-spacing: 0.2px">
                <thead style="background-color:#e1e1e1; color: #333; font-weight: bold; text-transform: uppercase">
                <tr>
                    <td>
                        Description
                    </td>
                    <td style="text-align: right">
                        Amount
                    </td>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>
                        <?php echo $vars['description'];?>
                    </td>
                    <td style="text-align: right">
                        <?php echo $vars['amount'];?>
                    </td>
                </tr>
                </tbody>

            </table>
        </td>
    </tr>
    </tbody>
</table>