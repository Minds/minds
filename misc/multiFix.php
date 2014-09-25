<?php
require(dirname(dirname(__FILE__)) . '/engine/start.php');

use minds\entities;

elgg_set_ignore_access(true);


minds\core\plugins::('minds')->activate();
