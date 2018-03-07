<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
    <tbody>
    <tr>
        <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
            <b>@<?php echo $vars['user']->username?>,</b>
        </td>
    </tr>
    <tr>
        <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
            <p>You have <?php echo $vars['amount'] ?> new unread notifications waiting for you. Log in to check them out!</p>
        </td>
    </tr>
    <tr>
        <td height="50px" style="text-align:center;">
            <a href="https://www.minds.com/notifications"
                    style="padding:16px 32px; background-color:#4caf50; color:#FFF; text-decoration:none; font-weight:bold; border-radius:3px;">
                See notifications
            </a>
        </td>
    </tr>
    </tbody>
</table>

<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
    <tbody>
    <tr>
        <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
            <a href="https://minds.com/?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']; ?>&validator=<?= $vars['validator'] ?>" style="text-decoration:none; color:inherit; font-weight:bold; font-style:italic;">The Minds Team</a>
        </td>
    </tr>
    </tbody>
</table>
