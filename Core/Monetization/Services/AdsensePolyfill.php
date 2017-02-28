<?php
namespace Minds\Core\Monetization\Services;

use Minds\Core;

class AdsensePolyfill extends Adsense
{
    public function getRevenuePerPage($dimension1, $start, $end, $pageToken = '', $pageSize = 50)
    {
        list($list, $offset) = parent::getRevenuePerPage($dimension1, $start, $end, $pageToken, $pageSize);

        $total = array_reduce($list, function ($carry, $item) {
            $carry += $item['revenue'];
            return $carry;
        }, 0.0);

        if ($total == 0) {
            // Probably AdSense is still not working
            list($views, $revenue) = $this->getTotalRevenueAndViews($dimension1, $start, $end);
            $rpv = (float) ($revenue / ($views ?: 1.0));

            array_walk($list, function (&$item) use ($rpv) {
                if ($item['views'] == 0) {
                    return;
                }

                $item['revenue'] = round((float) $item['views'] * $rpv, 2, PHP_ROUND_HALF_DOWN);
            });
        }

        return [ $list, $offset ];
    }
}
