<?php
namespace Minds\Core\Monetization;

use Minds\Core;

class Ads
{
    protected $user;

    protected $service;
    protected $config;

    protected $lastOffset = '';

    public function __construct($service = null, $config = null)
    {
        $this->service = $service ?: Core\Di\Di::_()->get('Monetization\DefaultService');
        $this->config = $config ?: Core\Di\Di::_()->get('Config');
    }

    public function setUser($user)
    {
        if (is_object($user)) {
            $user = $user->guid;
        }

        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTotalRevenue($start, $end)
    {
        return $this->service->getTotalRevenue($this->user, $start, $end);
    }

    public function getList($start, $end, $offset = '', $count = 50)
    {
        $payouts = Core\Di\Di::_()->get('Monetization\Payouts');

        list($items, $offset) = $this->service->getRevenuePerPage($this->user, $start, $end, $offset, $count);
        $this->lastOffset = $offset;

        $list = [];
        foreach ($items as $item) {
            $revenue = $payouts->calcUserAmount($item['revenue']);

            $list[] = [
                'entity' => [ // @todo: read metadata from DB?
                    'guid' => explode('/', trim($item['url'], '/'))[2],
                    'title' => $item['title']
                ],
                'views' => $item['views'],
                'revenue' => $revenue,
                'rpm' => ($revenue / ($item['views'] ?: 1)) * 1000
            ];
        }

        return $list;
    }

    public function getLastOffset()
    {
        return $this->lastOffset;
    }

    // Helpers methods
    // @note: No spec tests for this as they're just for user-facing data

    public function getOverview()
    {
        $payouts = Core\Di\Di::_()->get('Monetization\Payouts');
        $retentionDays = $this->config->get('payouts')['retentionDays'];

        return [
            'today' => $payouts->calcUserAmount($this->getTotalRevenue(new \DateTime('today'), new \DateTime('today'))),
            'last7' => $payouts->calcUserAmount($this->getTotalRevenue(new \DateTime('7 days ago'), new \DateTime('today'))),
            'lastRetentionDays' => $retentionDays,
            'lastRetentionAmount' => $payouts->calcUserAmount($this->getTotalRevenue(new \DateTime($retentionDays . ' days ago'), new \DateTime('today'))),
        ];
    }

    public function getPayoutsList($offset = '', $count = 50)
    {
        $payouts = Core\Di\Di::_()->get('Monetization\Payouts');
        $payouts->setUser($this->user);

        $dateRange = $payouts->getPayoutDateRange();
        $list = $dateRange ? $this->getList(
            $dateRange['start'], $dateRange['end'], $offset, $count
        ) : [];

        return [
            'list' => $list,
            'dates' => [
                'start' => $dateRange ? $dateRange['start']->getTimestamp() : false,
                'end' => $dateRange ? $dateRange['end']->getTimestamp() : false,
            ],
        ];
    }
}
