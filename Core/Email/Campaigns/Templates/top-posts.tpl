<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
    <tbody>
    <tr>
        <td style="text-align:center">
            <b>Hello @<?php echo $vars['user']->username?></b>
        </td>
    </tr>
    <tr>
        <td height="8px"></td>
    </tr>
   </tr>
    <tr>
        <td height="20px"></td>
    </tr>
    </tbody>
</table>



<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
    <tbody>

    <?php foreach($vars['posts'] as $object){ ?>
    <tr>
        <td style="background:#FEFEFE; border:1px solid #DDD; padding: 0;">
            <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">

                <?php //if a thumbnail exists
                $src = '';
                if ($object->custom_data && $object->custom_data[0] && $object->custom_data[0]['src']) {
                    $src = $object->custom_data[0]['src'];
                } elseif ($object->custom_data && $object->custom_data['thumbnail_src']) {
                    $src = $object->custom_data['thumbnail_src'];
                } elseif ($object->thumbnail_src) {
                    $src = $object->thumbnail_src;
                }

                if ($src) {
                ?> 
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                            <tr>
                                <td>
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign==<?= $vars['topic']?>-<?= $vars['period'] ?>">
                                        <img src="<?php echo $src ?>" width="600px" alt="<?php echo $object->title ?>">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center" style="padding:16px;">
                            <tr>
                                <td colspan="2">
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>" style="text-decoration:none; font-weight:bold; color:#333; font-size:16px; display:block; padding-bottom: 12px;">
                                        <?php if($object->type === "activity") {
                                            echo htmlspecialchars_decode($object->message, ENT_QUOTES);
                                        } else {
                                            echo htmlspecialchars_decode($object->title, ENT_QUOTES);
                                        }
                                        ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="https://www.minds.com/<?= $object->ownerObj['username'] ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>" style="text-decoration:none;">
                                        <img src="https://www.minds.com/icon/<?= $object->owner_guid?>/small" width="24px" style="border-radius:50%; vertical-align:middle;">
                                        <span style="line-height: 24px; vertical-align:middle; text-decoration:none; color: #4690d6;">@<?= $object->ownerObj['username'] ?></span>
                                    </a>
                                </td>
                                <td style="text-align:right">
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>" style="border:1px solid #4690d6; border-radius:3px; padding:8px 16px; letter-spacing: 0.75px; text-decoration:none; text-transform: uppercase; color:#4690d6">
                                        View
                                    </a>
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

