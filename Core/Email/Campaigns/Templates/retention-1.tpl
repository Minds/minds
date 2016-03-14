<?php

?>
<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
  <tbody>

    <?php foreach($vars['featured'] as $object){ ?>
    <tr>
      <td style="background:#FEFEFE; border:1px solid #DDD; padding: 0;">
        <table cellspacing="0" cellpadding="4" border="0" width="400" align="center">

          <tr>
            <table cellspacing="0" cellpadding="0" border="0" width="400" align="center">
              <tr>
                <td>
                  <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>">
                    <img src="<?= $object->getIconUrl(400) ?>" width="400px"  />
                  </a>
                </td>
              </tr>
            </table>
          </tr>

          <tr>
            <table cellspacing="0" cellpadding="0" border="0" width="400" align="center" style="padding:16px;">
              <tr>
                <td width="50" style="padding-right:8px">
                  <a href="https://www.minds.com/<?= $object->ownerObj['username'] ?>?__e_ct_guid=<?= $vars['guid']?>">
                    <img src="https://www.minds.com/icon/<?= $object->owner_guid?>" width="50px" style="border-radius:3px"/>
                  </a>
                </td>
                <td>
                  <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>" style="text-decoration:none; font-weight:bold; color:#333"><?= htmlspecialchars_decode($object->title, ENT_QUOTES) ?></a>
                  <br />
                  <span style="line-height: 24px;">by <a href="https://www.minds.com/<?= $object->ownerObj['username'] ?>?__e_ct_guid=<?= $vars['guid']?>">@<?= $object->ownerObj['username'] ?></a></span>
                </td>
                <td>
                  <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>" style="border:1px solid #ffc107; padding:8px; text-decoration:none; font-weight:bold; color:#333">Read</a>
                </td>
              </tr>
            </table>
           </tr>

        </table>
      </td>
    </tr>
    <?php } ?>

  </tbody>
</table>
