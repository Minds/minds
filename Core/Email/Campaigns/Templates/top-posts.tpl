<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
    <tbody>

    <?php foreach($vars['posts'] as $object){ ?>
    <tr>
        <td style="background:#FEFEFE; padding: 0;
                    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.07), 0 3px 1px -2px rgba(0, 0, 0, 0.1), 0 1px 5px 0 rgba(0, 0, 0, 0.07);
                    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.07), 0 3px 1px -2px rgba(0, 0, 0, 0.1), 0 1px 5px 0 rgba(0, 0, 0, 0.07);">
            <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                <?php
                $postOwner = $object->ownerObj;
                $obj = $object;

                if (isset($object->remind_object) && $object->remind_object) {
                    $postOwner = $object->remind_object['ownerObj'];
                    $obj = new Minds\Entities\Activity($object->remind_object);

                ?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center"
                                style="padding: 16px; padding-bottom: 0;">
                            <tr>
                                <td>
                                    <img src="<?php echo Minds\Core\Config::_()->get('cdn_assets_url') ?>front/public/assets/repeat-icon.png"
                                            width="24px" style="border-radius:50%; vertical-align:middle;">

                                    <img src="<?php echo Minds\Core\Config::_()->get('cdn_url') . '/icon/' . $object->ownerObj['guid']?>/small/<?php echo $object->ownerObj['icontime']?>"
                                            width="24px" style="border-radius:50%; vertical-align:middle;">
                                    <span style="line-height: 24px; vertical-align:middle; text-decoration:none; color: #333;">
                                        <strong>
                                            <?= $object->ownerObj['name'] ?>
                                        </strong>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellspacing="0" cellpadding="0" border="0" width="600" align="center"
                                            style="padding:8px 16px;">
                                        <tr>
                                            <td colspan="2" style="text-decoration:none; font-weight:400; color:rgba(0,0,0,0.8); font-size:16px; display:block; padding-bottom: 12px;">
                                                <?php if($object->type === "activity") {
                                                echo htmlspecialchars_decode($object->message, ENT_QUOTES);
                                                } else {
                                                echo htmlspecialchars_decode($object->title, ENT_QUOTES);
                                                }
                                                ?>
                                            </td>
                                    </table>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <?php } else {
                 }?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center"
                            <?php
                            if (isset($object->remind_object) && $object->remind_object) {
                            ?>
                                style="padding: 0 0 16px 16px">
                            <?php
                            } else {
                            ?>
                                style="padding: 16px 0">
                            <?php
                            }
                            ?>
                            <tr>
                                <td style="display: flex; align-items: center;">
                                    <img src="<?php echo Minds\Core\Config::_()->get('cdn_url') . '/icon/' . $postOwner['guid']?>/icon/<?= $postOwner['guid'] . '/medium/' . $postOwner['icontime'] ?>"
                                            width="50px" style="border-radius:50%; vertical-align:middle;">
                                    <div style="margin-left: 16px">
                                        <span style="letter-spacing:0.25px; line-height: 18px; text-decoration:none; color: #333;">
                                            <strong>
                                                <?= $postOwner['name'] ?>
                                            </strong>
                                        </span>
                                        <span style="display:block; line-height:18px; text-decoration:none; color: rgb(136, 136, 136); font-size:11px; letter-spacing: 0.75px">
                                                <?= strtoupper(date('M d, Y, g:i:s a', $obj['time_created'])) ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <?php
                if ($obj->message || $obj->title) {
                ?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center"
                                style="padding:16px;">
                            <tr>
                                <td colspan="2" style="text-decoration:none; font-weight:400; color:rgba(0,0,0,0.8); font-size:16px; display:block; padding-bottom: 12px;">
                                    <?php if($obj->type === "activity") {
                                        echo htmlspecialchars_decode($obj->message, ENT_QUOTES);
                                    } else {
                                        echo htmlspecialchars_decode($obj->title, ENT_QUOTES);
                                    }
                                    ?>
                                </td>
                        </table>
                    </td>
                </tr>
                <?php
                 }

                //if a thumbnail exists
                $src = '';
                if ($obj['custom_data'] && $obj['custom_data'][0] && $obj['custom_data'][0]['src']) {
                    $src = $obj['custom_data'][0]['src'];
                } elseif ($obj['custom_data'] && $obj['custom_data']['thumbnail_src']) {
                    $src = $obj['custom_data']['thumbnail_src'];
                } elseif ($obj['custom_data']) {
                    $src = $obj['custom_data'];
                } elseif ($obj['thumbnail_src']) {
                    $src = $obj['thumbnail_src'];
                }

                if ($src) {
                ?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                            <tr>
                                <td>
                                    <a href="<?= $obj->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign==<?= $vars['topic']?>-<?= $vars['period'] ?>">
                                        <img src="<?php echo $src ?>" width="600px" alt="<?php echo $obj->title ?>">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center"
                                style="padding:16px;">
                            <?php
                            if (isset($obj->title) && $obj->title) {
                            ?>
                            <tr>
                                <td colspan="2" style="text-decoration:none; font-weight:400; color:rgba(0,0,0,0.8); font-size:16px; display:block; padding-bottom: 12px;text-overflow: ellipsis;text-rendering: auto; white-space: pre-line; overflow: hidden; max-height: 30px; margin: 0; font-weight: bold">
                                    <?php
                                        echo htmlspecialchars_decode($obj->title, ENT_QUOTES);
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                            if (isset($obj->blurb) && $obj->blurb) {
                            ?>
                            <tr>
                                <td colspan="2" style="text-decoration:none; font-weight:400; color:rgba(0,0,0,0.8); font-size:16px; display:block; padding-bottom: 12px;text-overflow: ellipsis;text-rendering: auto; white-space: pre-line; overflow: hidden; max-height: 30px; margin: 0;">
                                    <?php
                                        echo htmlspecialchars_decode($obj->blurb);
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td></td>
                                <td style="text-align:right">
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>"
                                            style="border:1px solid #4690d6; border-radius:340p; padding:8px 16px; letter-spacing: 0.75px; text-decoration:none; text-transform: uppercase; color:#4690d6">
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

