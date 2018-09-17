<table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
    <tbody>

    <tr>
        <td>
            <p style="color: #444; margin: 0 0 32px 16px;">Here are some recent top blogs:</p>
        </td>
    </tr>

    <?php foreach($vars['entities'] as $object){ 
        $postOwner = $object->getOwnerObj();
        $obj = $object; 
    ?>
    <tr>
        <td style="background:#FFF; padding: 0; border: 1px solid #ececec;">
            <table cellspacing="0" cellpadding="0" border="0" width="600" align="left">
                <tr>
                    <td>
                        <table cellspacing="8" cellpadding="8" border="0" width="600" align="left">
                            <tr>
                                <td width="50">
                                    <img src="<?php echo Minds\Core\Config::_()->get('cdn_url') . '/icon/' . $postOwner['guid']?>/icon/<?= $postOwner['guid'] . '/medium/' . $postOwner['icontime'] ?>"
                                        width="50" style="border-radius:50%; vertical-align:middle;">
                                </td>
                                <td>
                                    <strong style="color: #444; width: 100%; display: block;">
                                        <?= $postOwner['name'] ?>
                                    </strong>
                                    <span style="display:block; line-height:18px; text-decoration:none; color: #888; font-size:11px; letter-spacing: 0.75px">
                                            <?= strtoupper(date('M d, Y, g:i:s a', $obj->getTimeCreated())) ?>
                                    </span>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <?php
                $src = 'https://cdn.minds.com/fs/v1/banners/' . $obj->getGuid() . '/' . $obj->getHeaderTop();
                ?>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="left">
                            <tr>
                                <td>
                                    <a href="<?= $obj->getUrl() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign'];?>&topic=<?= $vars['topic']?>&type=missed-blogs">
                                        <img src="<?php echo $src ?>" width="600px" alt="<?php echo $obj->getTitle() ?>">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="left"
                                style="padding:16px;">
                            <tr>
                            <?php
                            if ($obj->getTitle()) {
                            ?>
                                <td width="500">
                                    <h3 style="font-size:16px; color: #444; margin: 0;">
                                        <?php echo htmlspecialchars_decode($obj->getTitle(), ENT_QUOTES);?>
                                    </h3>
                                </td>
                            <?php } ?>
                                <td style="text-align:right" width="100">
                                    <a href="<?= $object->getUrl() ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign'];?>&topic=<?= $vars['topic']?>&type=missed-blogs"
                                            style="border:1px solid #4690d6; border-radius:340p; padding:8px 16px; letter-spacing: 0.75px; text-decoration:none; text-transform: uppercase; color:#4690d6 !important">
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

