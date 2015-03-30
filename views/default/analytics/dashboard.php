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
        <h3>Online Users</h3>
        <ul>
            <?php foreach($vars['users'] as $user): ?>
                <li><?= $user->username ?></li>
            <?php endforeach; ?>
    </div>
</div>
