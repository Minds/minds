<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
  <tbody>
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
        <a href="https://www.minds.com/wire?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']; ?>&topic=<?= $vars['topic']?>&validator=<?= $vars['validator'] ?>&key=<?= $vars['key']; ?>">
            <img src="https://s3.amazonaws.com/mindsfs/emails/sept-5/Wire.png" width="550px">
        </a>
      </td>
    </tr>
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
        <p>Another channel on Minds just wired you! Be sure to hit the button below to login and see who it was from.</p> 
      </td>
    </tr>
    <tr>
      <td height="50px" style="text-align:center;">
        <a href="<?php echo Minds\Core\Config::_()->site_url; ?>wallet/tokens/transactions/<?php echo $vars['contract'] ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']?>&topic=<?= $vars['topic']?>&validator=<?= $vars['validator'] ?>&key=<?= $vars['key']; ?>"
          style="padding:16px 32px; background-color:#4caf50; color:#ffffff !important; text-decoration:none; font-weight:bold; border-radius:3px;">
          See who wired you!
        </a>
      </td>
    </tr>   
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
        <p>On your channel, click “see breakdown” in the blue earnings console for full details about your wire or check your notifications.</p>
      </td>
    </tr>
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
        <p> <a href="<?php echo Minds\Core\Config::_()->site_url; ?>wire?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']?>&topic=<?= $vars['topic']?>&validator=<?= $vars['validator'] ?>&key=<?= $vars['key']; ?>">Wire</a> is the peer-to-peer payment and crowdfunding system on Minds. Minds tokens are accepted through Wire. It makes it easier than ever to support other channels or earn rewards for sharing great content.</p>
      </td>
    </tr>
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
        <p>You can exchange your tokens for extra views on the Minds network using Boost, or you can Wire them to other channels to show your support.</p>
      </td>
    </tr>
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;">
        <p>Give it a try and let us know what you think! </p>
      </td>
    </tr>
  </tbody>
</table>