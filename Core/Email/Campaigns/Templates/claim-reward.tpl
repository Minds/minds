<table cellspacing="8" cellpadding="8" border="0" width="558" align="center">
    <tbody>
    <tr>
        <td style="text-align:left;
        font-size: 16px;
        font-family: 'Lato', helvetica;">
           Dear <?php echo $vars['name']; ?>,
        </td>
    </tr>
    <tr>
        <td style="font-size: 16px;
        line-height: 22px;
        font-family: 'Lato', helvetica;">
            <p>
                Thank you for being a part of history by investing $<?php echo number_format($vars['amount']) ?> in the Minds Regulation Crowdfunding <a href="https://wefunder.com/minds" taget="_blank">campaign</a>. We reached $1 million dollars in only 19 days making us the fastest Reg C round ever!
            </p>
            <p>
                The response from our community was so overwhelmingly positive that it has taken us longer than anticipated to finalize the process with Wefunder and receive everyone’s funds, so we appreciate your patience.
            </p>
            <p style="font-weight: 600">
               To claim your investor rewards, please <a href="<?php echo Minds\Core\Config::_()->site_url; ?>claim-rewards/<?php echo $vars['uuid'] ?>-<?php echo $vars['validator'] ?>">
                 Click here</a>
            </p>
            <p>
                Over the last 6 months, we have been flying through the 2017 development roadmap with major upgrades already to Search, Blogs, Boost, Wallet, <a href="https://minds.com/localization">Languages</a>, Groups and <a href="https://www.minds.com/monetization">Monetization</a>. Within the next few months we will be launching into Production from Beta with revamps to mobile, rewards, digital currency, decentralization and more.
            </p>
            <p>
              Momentum is building as a rapidly growing number of people are demanding social platforms that support transparency and Internet freedom. Thank you for your support in pioneering this movement!
            </p>
        </td>
    </tr>
    <tr>
        <td style="font-size: 16px;
            line-height: 22px;
            font-family: 'Lato', helvetica;">
            The Minds Team
        </td>
    </tr>
    <tr>
        <td height="8px"></td>
    </tr>
    <tr>
        <td height="20px"></td>
    </tr>
    </tbody>
</table>
