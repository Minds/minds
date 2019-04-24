<table cellspacing="8" cellpadding="8" border="0" width="600" align="center">
  <tbody>
    <tr>
        <td>
            <p>Minds is one of the easiest places on the Internet to share your ideas and expand your reach.</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                By spending just one token, you can Boost your post and receive at least 1,000 views from the network. 
                If your post gains traction, you will earn more tokens and can grow your channel even quicker.
            </p>      
        </td>
    </tr>
    <tr>
        <td align="center">
            <p>
              <a href="<?php echo "{$vars['site_url']}newsfeed/subscribed?{$vars['tracking']}"?>">
                <img src="<?php echo $vars['cdn_assets_url']; ?>assets/emails/cta_make_a_post.png" width="142" alt="Make a Post"/>
              </a>
            </p>
        </td>
    </tr>

    <?php if ($vars['suggestions']): ?>
    <tr>
      <td align="center">
        <p>If you don't feel like posting, then here are some more suggested channels for you to subscribe to.</p>
      </td>
    </tr>
    <tr>
        <td>
            <?php echo $vars['suggestions']; ?>
        </td>
    </tr>
    <?php endif; ?>
    <tr>
        <td>
            <p>Also, be sure to download our mobile app using the links below:</p>      
        </td>
    </tr>
     <tr align="center">
      <td>
        <p>
          <a href="https://itunes.apple.com/us/app/minds-com/id961771928?ls=1&mt=8" style="text-decoration: none">
            <img src="<?php echo $vars['cdn_assets_url']; ?>assets/ext/appstore.png" width="142" alt="Apple App Store"/>
          </a>
          <a href="<?php echo "{$vars['site_url']}mobile?{$vars['tracking']}"?>" style="text-decoration: none">
            <img src="<?php echo $vars['cdn_assets_url']; ?>assets/photos/minds-android-app.png" width="142" alt="Google Play"/>
          </a>
        </p>
      </td>
    </tr>
    <tr>
        <td>
            <p>Thank you for being a pioneer of the free and open internet.</p>
        </td>
    </tr>     
  </tbody>
</table>

