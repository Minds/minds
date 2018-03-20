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
        Welcome to Minds! Our crypto <a href="https://www.minds.com/newsfeed/793558593763016704">launch</a> is imminent. Here are 200 free views. Please make your first post and stay tuned for the next evolution
      </td>
    </tr>
    <tr>
      <td height="80px;" style="text-align:center">
        <a href="<?php echo Minds\Core\Config::_()->site_url; ?>newsfeed;message=<?php echo urlencode('Hi Minds, I’m new here. Say hello. ' . Minds\Core\Config::_()->site_url . $vars['user']->username )?>;newUser=true"
          style="padding:16px 32px; background-color:#546e7a; color:#FFF; text-decoration:none; font-weight:bold; border-radius:3px;">
          POST!
        </a>
      </td>
    </tr>
  </tbody>
</table>

