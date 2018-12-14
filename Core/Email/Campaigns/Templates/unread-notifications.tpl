<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
    <tbody>
    <tr>
        <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
            <p>You have <?php if ($vars['count'] <=5) { echo $vars['count']; } else { echo "5+"; }?> unread notification<?= $vars['count'] > 1 ? 's' : ''?> waiting for you. Log in to check them out!</p>
        </td>
    </tr>
    <tr>
        <td height="50px" style="text-align:center;">
            <a href="https://www.minds.com/notifications?__e_ct_guid=<?= $vars['guid']?>&campaign=when&topic=unread_notifications"
                    style="padding:16px 32px; background-color:#4caf50; color:#FFF !important; text-decoration:none; font-weight:bold; border-radius:3px;">
                Click to View
            </a>
        </td>
    </tr>
    </tbody>
</table>
