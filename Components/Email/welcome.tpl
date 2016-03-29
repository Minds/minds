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
        Welcome to Minds! Here’s 200 free views to introduce yourself and be seen by the community. All you need to do is post your first status.
      </td>
    </tr>
    <tr>
      <td height="80px;" style="text-align:center">
        <a href="<?php echo Minds\Core\Config::_()->site_url; ?>newsfeed?message=<?php echo urlencode('Hi Minds, I’m new here. Say hello. ' . Minds\Core\Config::_()->site_url . $vars['user']->username )?>&newUser=true"
          style="padding:16px 32px; background-color:#546e7a; color:#FFF; text-decoration:none; font-weight:bold; border-radius:3px;">
          POST!
        </a>
      </td>
    </tr>
  </tbody>
</table>


<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
  <tbody>
    <tr>
      <td style="text-align:center">
        <a href="<?php echo Minds\Core\Config::_()->site_url; ?>newsfeed">
          <img src="<?php echo Minds\Core\Config::_()->site_url; ?>assets/tutorial.png" align="center" width="450px" height="750px"/>
        </a>
      </td>
    </tr>
  </tbody>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
  <tbody>
    <tr>
      <td style="text-align:center">
        <a href="https://www.minds.com/archive/view/440854538476326912">
          <img src="<?php echo Minds\Core\Config::_()->site_url; ?>assets/mobile-video.png" align="center" width="600px" height="378px"/>
        </a>
      </td>
    </tr>
  </tbody>
</table>
