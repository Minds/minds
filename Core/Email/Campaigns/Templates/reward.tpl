<table cellspacing="8" cellpadding="8" border="0" width="600" align="center">
  <tbody>
    <tr>
      <td style="text-align:center">
        <b>Hello @<?php echo $vars['user']->username?></b>
      </td>
    </tr>
    <tr>
      <td>
        Here's 1,000 points which can be exchanged for 1,000 views on the content of your choice! Stay subscribed to receive more. Enjoy!
      </td>
    </tr>
  </tbody>
</table>

<table cellspacing="8" cellpadding="8" border="0" width="600" align="center">
  <tbody>
    <tr>
      <td height="20px"></td>
    </tr>
    <tr>
      <td height="20px"></td>
      <td height="20px" style="text-align:center;">
        <a href="<?php echo Minds\Core\Config::_()->site_url; ?>newsfeed?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']?>&validator=<?= $vars['validator'] ?>"
          style="padding:16px 32px; background-color:#4caf50; color:#FFF; text-decoration:none; font-weight:bold; border-radius:3px;">
          Claim 1000 views
        </a>
      </td>
      <td height="20px"></td>
    </tr>
    <tr>
      <td height="20px"></td>
    </tr>
  </tbody>
</table>


<table cellspacing="8" cellpadding="8" border="0" width="600" align="center">
  <tbody>

    <tr>
      <td>
        <ul>
          <li>Tap the button above and login.</li>
          <li>Make a post.</li>
          <li>Hit the Boost button.</li>
          <li>Choose "Full Network:".</li>
          <li>Enter the amount of points you want to spend.</li>
          <li>Confirm the Boost.</li>
          <li>Check back in a couple days to see the results!</li>
        </ul>
      </td>
    </tr>

    <tr>
      <td height="20px"></td>
    </tr>
    <tr>
      <td style="text-align:center;">
        <a href="<?php echo Minds\Core\Config::_()->site_url; ?>blog/featured?__e_ct_guid=<?= $vars['guid']?>&campaign=july-2016"
          style="text-decoration:none; color:#333">
          <img src="https://www.minds.com/assets/tutorial.png" width="450px" align="center">
        </a>
      </td>
    </tr>

  </tbody>
</table>

<table cellspacing="8" cellpadding="8" border="0" width="600" align="center">
  <tbody>

    <?php foreach($vars['featured'] as $object){ ?>
    <tr>
      <td style="background:#FEFEFE; border:1px solid #DDD; padding: 0;">
        <table cellspacing="0" cellpadding="4" border="0" width="600" align="center">

          <tr>
            <td>
              <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                <tr>
                  <td>
                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>">
                      <img src="<?= $object->getIconUrl(600) ?>" width="600px" alt="<?php echo $object->title ?>">
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <td>
              <table cellspacing="0" cellpadding="0" border="0" width="600" align="center" style="padding:16px;">
                <tr>
                  <td colspan="2">
                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>" style="text-decoration:none; font-weight:bold; color:#333; font-size:16px; display:block; padding-bottom: 12px;"><?= htmlspecialchars_decode($object->title, ENT_QUOTES) ?></a>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a href="https://www.minds.com/<?= $object->ownerObj['username'] ?>?__e_ct_guid=<?= $vars['guid']?>" style="text-decoration:none;">
                      <img src="https://www.minds.com/icon/<?= $object->owner_guid?>/small" width="24px" style="border-radius:50%; vertical-align:middle;">
                      <span style="line-height: 24px; vertical-align:middle; text-decoration:none;">@<?= $object->ownerObj['username'] ?></span>
                    </a>
                  </td>
                  <td style="text-align:right">
                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>" style="border:1px solid #ffc107; border-radius:3px; background:#ffc107; padding:8px; text-decoration:none; font-weight:bold; color:#FFF">Read on Minds</a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
      </td>
    </tr>
    <?php } ?>

  </tbody>
</table>
