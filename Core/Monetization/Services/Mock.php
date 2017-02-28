<?php
namespace Minds\Core\Monetization\Services;

use Minds\Core;

class Mock
{
    protected $analytics;
    protected $client;

    public function __construct($config = null)
    {
    }

    public function getTotalRevenueAndViews($dimension1, $start, $end)
    {
        $start = $start->getTimestamp();
        $end = $end->getTimestamp() + 86400;

        $revenue = (($end - $start) / 1440) / 10.1;

        return [($end - $start) / 1440, $revenue];
    }

    public function getTotalRevenue($dimension1, $start, $end)
    {
        list($views, $revenue) = $this->getTotalRevenueAndViews($dimension1, $start, $end);
        return $revenue;
    }

    public function getTotalViews($dimension1, $start, $end)
    {
        list($views, $revenue) = $this->getTotalRevenueAndViews($dimension1, $start, $end);
        return $views;
    }

    public function getRevenuePerPage($dimension1, $start, $end, $pageToken = '', $pageSize = 50)
    {
        return [ [], '' ];
    }
}
