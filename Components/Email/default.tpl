<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title></title>
  </head>
  <body style="margin:0; padding:0;">

    <table cellspacing="0" cellpadding="12" border="0" width="100%" align="center">
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
                      <!-- Email body -->

                      <p align="center">
                        <a href="https://www.minds.com/?__e_ct_guid=<?= $vars['guid']?>" style="text-decoration:none;">
                        <img src="https://www.minds.com/assets/logos/medium.png" alt="Minds.com" align="middle" width="200px" height="80px"/>
                        </a>
                      </p>
                    </td>
                </tr>
                <tr>
                  <td height="40"></td>
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

            <!-- START FOOTER -->
            <table cellspacing="0" cellpadding="0" border="0" width="300" align="center">
              <tbody>
                <tr>
                  <td height="20"></td>
                </tr>
                <tr>
                  <td>
                    <a href="https://www.minds.com/emails/unsubscribe/<?= $vars['username']?>/<?= $vars['email']?>?__e_ct_guid=<?= $vars['guid']?>" align="center" style="color:#888">
                      un-subscribe
                    </a>
                    from future emails.
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- END FOOTER -->

          </td>
        </tr>
      </tbody>
    </table>


  </body>

</html>
