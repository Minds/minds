<div class="minds-dashboard analytics">

    <div class="minds-module anyltics-requests">
        <h3>API Requests</h3>
        <ul>
            <li><b>Last 5 minutes</b>: <?= $vars['requests'][0]; ?>
            <li><b>Last 10 minutes</b>: <?= $vars['requests'][0] + $vars['requests'][5]; ?>
            <li><b>Last 15 minutes</b>: <?= $vars['requests'][0] + $vars['requests'][5] + $vars['requests'][10]; ?>
            <li><b>Requests per second</b>: <?= $vars['rps'] ?>
        </ul>
    </div>


     <div class="minds-module users">
     <h3>Online Users (<?= $vars['user_count'] ?>)</h3>
        <ul>
            <?php foreach($vars['users'] as $user): ?>
                <li><?= $user->username ?></li>
            <?php endforeach; ?>
    </div>

    <div class="minds-module boosts">
        <h3>Boosts</h3>
        <ul>
            <li>All time served impressions <?= $vars['globals']['boosts'] ?></li>
            <li></li>
            <li><b>Review (newsfeed):</b> <?= $vars['boosts']['review'] ?></li>
            <li><b>Review backlog (newsfeed):</b> <?= round($vars['boosts']['review_backlog']) ?> hours</li>
            <br/>
            <li><b>Approved (newsfeed):</b> <?= $vars['boosts']['approved']?></li>
            <li><b>Approved backlog:</b> <?= round($vars['boosts']['approved_backlog']) ?> hours</li>
            <li><b>Approved burn rate:</b> <?= $vars['boosts']['impressions_met'] / $vars['boosts']['approved_backlog'] ?> impressions/hour</li>
            <li><b>Impressions remaining (newsfeed):</b> <?= $vars['boosts']['impressions'] - $vars['boosts']['impressions_met']?></li>
            <br/>
             <li><b>Approved (suggested):</b> <?= $vars['boosts_suggested']['approved']?></li>
            <li><b>Impressions remaining (suggested):</b> <?= $vars['boosts_suggested']['impressions'] - $vars['boosts_suggested']['impressions_met']?></li>
        </ul>
        
    </div>

</div>
<div class="minds-dashboard analytics">
     <div class="minds-module anyltics-requests">
        <h3>Leaderboard</h3>
        <ul>
           <?php foreach($vars['leaderboard'] as $user): ?>
            <li><?= $user['user']->username ?>: (<?= $user['points']?>)</li>
           <?php endforeach;?>
        </ul>
    </div>
</div>
