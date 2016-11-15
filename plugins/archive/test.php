<?php
require_once dirname(dirname(dirname(__FILE__))) . '/engine/start.php';

$video = new Minds\plugin\archive\entities\video();

$video->upload('/mnt/uploads/testMskf');
echo "done ($video->guid) \n";
