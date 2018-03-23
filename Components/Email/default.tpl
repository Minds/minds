<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title></title>
      <style>
        p { 
          font-family: Roboto,Arial,sans-serif;
          font-size: 18px;
          line-height: 1.5;
          color: #444;
        }
        a {
          color: #4690df !important;
        }
      </style>
  </head>
  <body style="margin:0; padding:0;">

    <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center" style="width:100%!important">
      <tbody>
        <tr>
          <td>
            <!-- START HEADER -->
            <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
              <tbody>
                <tr>
                    <td height="20"></td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" style="font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:20px">
                      <h3 style="margin-bottom: 0;margin-top: 84px;color: #444;font-size: 24px;">@<?php echo $vars['username'] ?></h3>
                    </td>
                    <td bgcolor="#ffffff" style="font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:20px">
                      <!-- Email body -->

                      <p align="right">
                        <a href="https://www.minds.com/?__e_ct_guid=<?= $vars['guid']?>" style="text-decoration:none;">
                          <img src="<?php echo $vars['cdn_assets_url']; ?>assets/logos/bulb.jpg" alt="Minds.com" align="middle" height="80px"/>
                        </a>
                      </p>
                    </td>
                </tr>
                <tr>
                  <td height="20"></td>
                </tr>
              </tbody>
            </table>
            <!-- END HEADER -->

            <!-- START BODY -->
            <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
              <tbody>
                <tr>
                  <td><?php echo $vars['body'] ?></td>
                </tr>
              </tbody>
            </table>
            <!-- END BODY -->

            <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
              <tbody>
                <tr>
                  <td>
                    <p><b>The Minds Team</b></p>
                  </td>
                </tr>
              </tbody> 
            </table>


            <!-- START FOOTER -->
            <?php if(isset($vars['username']) && isset($vars['email'])){ ?>
            <table cellspacing="0" cellpadding="0" border="0" width="300" align="center">
              <tbody>
                <tr>
                  <td height="20"></td>
                </tr>
                <tr>
                  <td>
                    <a href="https://www.minds.com/emails/unsubscribe/<?= $vars['username']?>/<?= $vars['email']?>/<?= $vars['campaign']?><?= '/' . $vars['topic']?>?__e_ct_guid=<?= $vars['guid']?>" align="center" style="color:#888">
                      un-subscribe
                    </a>
                    from future emails.
                  </td>
                </tr>
              </tbody>
            </table>
            <?php } ?>
            <!-- END FOOTER -->

          </td>
        </tr>
      </tbody>
    </table>


  </body>

</html>
