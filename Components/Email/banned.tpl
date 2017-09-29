<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
  <tbody>
    <tr>
      <td>
        <b>Hello @<?php echo $vars['user']->username ?></b>
      </td>
    </tr>
    <tr>
      <td height="20px"></td>
    </tr>
    <tr>
      <td>
        You have been banned from Minds due to the following reason:
        <ul>
          <li>
            <?php echo $vars['reason'] ?>
          </li>
        </ul>
        Please reply this email to appeal.
      </td>
    </tr>
    <tr>
      <td>
        Minds.
      </td>
    </tr>
  </tbody>
</table>
