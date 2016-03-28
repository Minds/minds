<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
  <tbody>
    <tr>
      <td>
        <b>Hello @<?php echo $vars['user']->username?></b>
      </td>
    </tr>
    <tr>
      <td height="20px"></td>
    </tr>
    <tr>
      <td>
        Please click on the link below to reset your password.
      </td>
    </tr>
    <tr>
      <td height="20px"></td>
    </tr>
    <tr>
      <td>
        <a href="<?php echo $vars['link']; ?>">
          <?php echo $vars['link']; ?>
        </a>
      </td>
    </tr>
  </tbody>
</table>
