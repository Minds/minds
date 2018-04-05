<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
  <tbody>
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;
        color: #444;
        ">
        Here are some recent posts on Minds you might find interesting...
      </td>
    </tr>
  </tbody>
</table>

<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
  <tbody>
    <?php foreach($vars['posts'] as $users_map => $users) {
        foreach($users['posts'] as $guid) { ?>
        <tr>
            <td style="text-align:center;"> 
            <a href="https://minds.com/newsfeed/<?= $guid ?>?__e_ct_guid=<?= $vars['guid']?>&campaign=<?= $vars['campaign']; ?>" target="_blank">
                <img src="https://d15u56mvtglc6v.cloudfront.net/emails/<?= $guid ?>.png" width="558"/>
            </a>
            </td>
        </tr>
    <?php }
     } 
    ?>
  <tbody>
</table>

<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
  <tbody>
    <tr>
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;
        color:#444;
        ">
        Did you know that you can now support these channels on Minds?
      </td>
    </tr>
    <tr>
      <td style="font-size: 16px;
        line-height: 22px;
        letter-spacing: 0.2px;
        font-family: 'Lato', helvetica;
        color: #444;
        ">
        Just go to their channel and click the “Wire Me” icon to send them points or USD to support their work and keep them independent!
      </td>
    </tr>
  </tbody>
</table>