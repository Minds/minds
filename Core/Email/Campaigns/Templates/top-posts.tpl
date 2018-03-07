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

    <tr>
        <td height="80px;" style="text-align:center">
           Here's some of our trending content:
        </td>
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

                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                            <tr>
                                <td>
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign==<?= $vars['topic']?>-<?= $vars['period'] ?>">
                                        <img src="<?php
                                            $src = '';
                                            if ($object->custom_data && $object->custom_data[0] && $object->custom_data[0]['src']) {
                                                $src = $object->custom_data[0]['src'];
                                            } else if ($object->custom_data && $object->custom_data['thumbnail_src']) {
                                                $src = $object->custom_data['thumbnail_src'];
                                            }
                                            echo $src;
                                            ?>" width="600px" alt="<?php echo $object->title ?>">
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
                                        <span style="line-height: 24px; vertical-align:middle; text-decoration:none;">@<?= $object->ownerObj['username'] ?></span>
                                    </a>
                                </td>
                                <td style="text-align:right">
                                    <?php if($object->type == "activity"){ ?>
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>" style="border:1px solid #ffc107; border-radius:3px; background:#ffc107; padding:8px; text-decoration:none; font-weight:bold; color:#FFF">
                                        Read on Minds
                                    </a>
                                    <?php } elseif($object->subtype == "video"){ ?>
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>" style="border:1px solid #ffc107; border-radius:3px; background:#ffc107; padding:8px; text-decoration:none; font-weight:bold; color:#FFF">
                                        Watch on Minds
                                    </a>
                                    <?php } elseif($object->subtype == "image"){ ?>
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>" style="border:1px solid #ffc107; border-radius:3px; background:#ffc107; padding:8px; text-decoration:none; font-weight:bold; color:#FFF">
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

<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
    <tbody>
    <tr>
        <td style="font-size: 16px;
            line-height: 22px;
            font-family: 'Lato', helvetica;">
            The Minds Team
        </td>
    </tr>
    </tbody>
</table>