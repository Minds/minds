
<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
  <tbody>

    <?php foreach($vars['featured'] as $object){ ?>
    <tr>
      <td style="background:#FEFEFE; border:1px solid #DDD; padding: 0;">
        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">

          <tr>
            <td>
              <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                <tr>
                  <td>
                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=retention-<?= $vars['period'] ?>" style="color: #444 !important;">
                      <?php if($object->subtype == "blog"){ ?>
                      <img src="<?= $object->getIconUrl(400) ?>" width="600px" alt="<?php echo $object->title ?>">
                      <?php } else { ?>
                      <img src="<?= $object->getIconUrl('xlarge') ?>" width="600px" alt="<?php echo $object->title ?>">
                      <?php } ?>
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
                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=retention-<?= $vars['period'] ?>" style="text-decoration:none; font-weight:bold; color:#444 !important; font-size:16px; display:block; padding-bottom: 12px;"><?= htmlspecialchars_decode($object->title, ENT_QUOTES) ?></a>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a href="https://www.minds.com/<?= $object->ownerObj['username'] ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=retention-<?= $vars['period'] ?>" style="text-decoration:none; color:#444 !important;">
                      <img src="https://www.minds.com/icon/<?= $object->owner_guid?>/small" width="24px" style="border-radius:50%; vertical-align:middle;">
                      <span style="line-height: 24px; vertical-align:middle; text-decoration:none;">@<?= $object->ownerObj['username'] ?></span>
                    </a>
                  </td>
                  <td style="text-align:right">
                    <?php if($object->subtype == "blog"){ ?>
                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=retention-<?= $vars['period'] ?>" style="border:1px solid #ffc107; border-radius:3px; background:#ffc107; padding:8px; text-decoration:none; font-weight:bold; color:#FFF !important">
                      Read on Minds
                    </a>
                    <?php } elseif($object->subtype == "video"){ ?>
                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=retention-<?= $vars['period'] ?>" style="border:1px solid #ffc107; border-radius:3px; background:#ffc107; padding:8px; text-decoration:none; font-weight:bold; color:#FFF !important">
                      Watch on Minds
                    </a>
                    <?php } elseif($object->subtype == "image"){ ?>
                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=retention-<?= $vars['period'] ?>" style="border:1px solid #ffc107; border-radius:3px; background:#ffc107; padding:8px; text-decoration:none; font-weight:bold; color:#FFF !important">
                      View on Minds
                    </a>
                    <?php } ?>

                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
      </td>
    </tr>
    <tr>
      <td height="20px"></td>
    </tr>
    <?php } ?>

  </tbody>
</table>
