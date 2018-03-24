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
        <td style="background:#FEFEFE; padding: 0;
                    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.07), 0 3px 1px -2px rgba(0, 0, 0, 0.1), 0 1px 5px 0 rgba(0, 0, 0, 0.07);
                    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.07), 0 3px 1px -2px rgba(0, 0, 0, 0.1), 0 1px 5px 0 rgba(0, 0, 0, 0.07);">
            <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                <?php if (isset($object->remind_object) && $object->remind_object) {
                    $remindOwnerObj = $object->ownerObj;
                ?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center" style="padding: 16px">
                            <tr>
                                <td>
                                    <svg fill="rgb(70, 144, 214)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle">
                                        <path d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M7 7h10v3l4-4-4-4v3H5v6h2V7zm10 10H7v-3l-4 4 4 4v-3h12v-6h-2v4z"/>
                                    </svg>
                                    <a href="https://www.minds.com/<?= $remindOwnerObj['username'] ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>"
                                            style="text-decoration:none;">
                                        <img src="https://www.minds.com/icon/<?= $remindOwnerObj['guid']?>/small"
                                                width="24px" style="border-radius:50%; vertical-align:middle;">
                                        <span style="line-height: 24px; vertical-align:middle; text-decoration:none; color: #4690d6;">@<?= $remindOwnerObj['username'] ?></span>
                                    </a>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <?php } else {
                 }?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center" style="padding: 16px">
                            <tr>
                                <td>
                                    <a href="https://www.minds.com/<?= $object->ownerObj['username'] ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>"
                                            style="text-decoration:none;">
                                        <img src="https://www.minds.com/icon/<?= $object->ownerObj['guid']?>/medium"
                                                width="50px" style="border-radius:50%; vertical-align:middle;">
                                        <span style="line-height: 24px; vertical-align:middle; text-decoration:none; color: #4690d6;">@<?= $object->ownerObj['username'] ?></span>
                                    </a>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center"
                                style="padding:16px;">
                            <tr>
                                <td colspan="2">
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>"
                                            style="text-decoration:none; font-weight:400; color:rgba(0,0,0,0.8); font-size:16px; display:block; padding-bottom: 12px;">
                                        <?php if($object->type === "activity") {
                                        echo htmlspecialchars_decode($object->message, ENT_QUOTES);
                                        } else {
                                        echo htmlspecialchars_decode($object->title, ENT_QUOTES);
                                        }
                                        ?>
                                    </a>
                                </td>
                        </table>
                    </td>
                </tr>
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
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center"
                                style="padding:16px;">
                            <?php
                            if (isset($object->title) && $object->title) {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>"
                                            style="text-decoration:none; font-weight:400; color:rgba(0,0,0,0.8); font-size:16px; display:block; padding-bottom: 12px;text-overflow: ellipsis;text-rendering: auto; white-space: pre-line; overflow: hidden; max-height: 40px; margin: 0; font-weight: bold">
                                        <?php
                                            echo htmlspecialchars_decode($object->title, ENT_QUOTES);
                                        ?>
                                    </a>
                                </td>
                            </tr>
                            <?php
                            }
                            if (isset($object->blurb) && $object->blurb) {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>"
                                            style="text-decoration:none; max-height:45px; font-weight:400; color:rgba(0,0,0,0.8); font-size:16px; display:block; padding-bottom: 12px;text-overflow: ellipsis;text-rendering: auto; white-space: pre-line; overflow: hidden; max-height: 40px; margin: 0;">
                                        <?php
                                            echo htmlspecialchars_decode($object->blurb);
                                        ?>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td></td>
                                <td style="text-align:right">
                                    <a href="<?= $object->getURL() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['topic']?>-<?= $vars['period'] ?>"
                                            style="border:1px solid #4690d6; border-radius:3px; padding:8px 16px; letter-spacing: 0.75px; text-decoration:none; text-transform: uppercase; color:#4690d6">
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

