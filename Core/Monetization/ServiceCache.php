<?php
namespace Minds\Core\Monetization;

use Minds\Core;

class ServiceCache
{
    protected $cache;

    protected $service = '';
    protected $longTtl = 86400;
    protected $shortTtl = 3600;

    public function __construct($cache = null)
    {
        $this->cache = $cache ?: Core\Di\Di::_()->get('Cache');
    }

    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    public function getService()
    {
        return $this->service;
    }

    public function setLongTtl($longTtl)
    {
        $this->longTtl = $longTtl;
        return $this;
    }

    public function getLongTtl()
    {
        return $this->longTtl;
    }

    public function setShortTtl($shortTtl)
    {
        $this->shortTtl = $shortTtl;
        return $this;
    }

    public function getShortTtl()
    {
        return $this->shortTtl;
    }

    public function set($method, $meta, \DateTime $rangeStart, \DateTime $rangeEnd, $data)
    {
        $key = $this->buildStringKey($method, $meta, $rangeStart, $rangeEnd);
        $ttl = $this->buildTtl($rangeStart, $rangeEnd);

        return $this->cache->set($key, $data, $ttl);
    }

    public function get($method, $meta, \DateTime $rangeStart, \DateTime $rangeEnd)
    {
        $key = $this->buildStringKey($method, $meta, $rangeStart, $rangeEnd);

        return $this->cache->get($key);
    }

    public function destroy($method, $meta, \DateTime $rangeStart, \DateTime $rangeEnd)
    {
        $key = $this->buildStringKey($method, $meta, $rangeStart, $rangeEnd);

        return $this->cache->destroy($key);
    }

    // Helper functions

    protected function buildStringKey($method, $meta, \DateTime $rangeStart, \DateTime $rangeEnd)
    {
        if (!is_array($meta)) {
            $meta = [ $meta ];
        }

        $rawKey = array_merge([ $this->service, $method ], $meta);
        $rawKey[] = $rangeStart;
        $rawKey[] = $rangeEnd;

        $key = array_reduce($rawKey, function ($carry, $item) {
            if ($item instanceof \DateTime) {
                $segment = $item->format('Ymd');
            } else {
                $segment = (string) $item;
            }

            return $carry . ':' . $segment;
        }, 'monetization_service');

        return $key;
    }

    protected function buildTtl(\DateTime $rangeStart, \DateTime $rangeEnd)
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $end = clone $rangeEnd;
        $end->setTime(0, 0, 0);

        $diff = (int) $today->diff($end)->format('%R%a');

        if ($diff <= -1) {
            return $this->longTtl;
        }

        return $this->shortTtl;
    }
}
