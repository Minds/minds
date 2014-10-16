<?php
/**
 * The basket
 * 
 * @todo remove much of the logic out of here
 */

use minds\plugin\market\entities;

$basket = new entities\basket();

echo count($basket->getItems());
echo $basket->total();
