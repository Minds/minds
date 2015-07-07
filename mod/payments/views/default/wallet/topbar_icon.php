<?php 

$count = \Minds\Helpers\Counters::get(\Minds\Core\session::getLoggedinUser()->guid, 'points', false);
$points = $count;
if($count > 1000)
    $points = round(floatval($count / 1000)) . 'K';
if($count > 1000000)
    $points = round(floatval($count / 1000000)) . 'M';
?>

<span class="wallet-icon">
    <a href="<?= elgg_get_site_url() ?>wallet" class="entypo">&#59418;
    <span class="points"><?= $points ?></span>
</span>
