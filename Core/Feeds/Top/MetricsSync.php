<?php
namespace Minds\Core\Feeds\Top;

use Minds\Traits\MagicAttributes;

/**
 * Class MetricsSync
 * @package Minds\Core\Feeds\Top
 * @method string getMetric()
 * @method string getPeriod()
 * @method string getType()
 * @method int getCount()
 * @method int getSynced()
 * @method int|string getGuid()
 */
class MetricsSync
{

    use MagicAttributes;

    private $guid;

    private $type;

    private $metric;

    private $count;

    private $period;

    private $synced;

}

