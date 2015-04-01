<span class="wallet-icon">
    <a href="<?= elgg_get_site_url() ?>wallet" class="entypo">&#59418;
    <span class="points"><?= \Minds\Helpers\Counters::get(\Minds\Core\session::getLoggedinUser()->guid, 'points', false) ?></span>
</span>
