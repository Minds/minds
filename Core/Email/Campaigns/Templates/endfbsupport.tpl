<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
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
        <td height="20px"></td>
    </tr>
    <tr>
        <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
            We're ending Facebook sign in support. From now on you'll have to log in using your Mind's username: <b>@<?php echo $vars['user']->username?></b>
            Please click on the link below to reset your password.
        </td>
    </tr>
    <tr>
        <td height="20px"></td>
    </tr>
    <tr>
        <td height="50px" style="text-align:center;">
            <a href="<?php echo $vars['link']; ?>" style="padding:16px 32px; background-color:#4caf50; color:#FFF; text-decoration:none; font-weight:bold; border-radius:3px;">
                <?php echo $vars['link']; ?>
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