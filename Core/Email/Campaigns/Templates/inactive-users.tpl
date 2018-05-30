<table cellspacing="8" cellpadding="8" border="0" width="600" align="center">
  <tbody>
    <tr>
      <td>
        <p>
           It has been over a year since you last logged into your Minds account!
            The platform has made major leaps since you last checked in. 
        </p>
        <p>
           It is our site policy to release usernames for accounts that have not been active for over a year so that we can reopen the usernames to the public.
            We will provide a final warning before officially taking this action on your account.  
        </p>
        <p>
            If you would like to keep your username, <b>@<?php echo $vars['user']->username?></b>, please use the button below to login now and stop receiving these emails.
            If you believe you have received this email in error, please reply directly to info@minds.com. 
         </p>
      </td>
    </tr>
    <tr>
      <td height="50px" style="text-align:center; padding-top: 16px">
        <a href="<?php echo Minds\Core\Config::_()->site_url; ?>login?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']?>&validator=<?= $vars['validator'] ?>"
          style="padding:16px 32px; background-color:#4caf50; color:#FFF; text-decoration:none; font-weight:bold; border-radius:3px;color:#FFF!important;">
          Login Now
        </a>
      </td>
    </tr>
    <tr>
      <td>
        <p>
           Here are some of the updates you may have missed... 
        </p>
      </td>
    </tr>
    <tr>
      <td style="font-size: 18px;
        padding-top:8px;
        line-height: 1.5;
        font-family: 'Roboto', helvetica;
        color: #444;
        ">
        <ul>
          <li style="padding-bottom:8px">
            We launched our <a href="https://www.minds.com/monetization?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']; ?>&validator=<?= $vars['validator'] ?>">crypto reward system</a> and brand new mobile apps.
          </li>
          <li style="padding-bottom:8px">
            Our <a href="https://www.minds.com/boost?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']; ?>&validator=<?= $vars['validator'] ?>">Boost system</a> has helped us become the easiest platform on the Internet to gain viral reach and grow your following</li>
          <li style="padding-bottom:8px">
            Our new <a href="https://www.minds.com/wire?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']; ?>&validator=<?= $vars['validator'] ?>">Wire feature</a> allows you to exchange tokens directly with other users</li>
          <li style="padding-bottom:8px">We still value and protect your freedom of speech!</li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>
        <p>
            We look forward to seeing you soon! 
        </p>
      </td>
    </tr>
  </tbody>
</table>
