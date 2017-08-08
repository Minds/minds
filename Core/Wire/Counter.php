<?php
/**
 * Used for counting wires either by a user's GUID or an entity's GUID and caching them
 * @author Marcelo.
 */

namespace Minds\Core\Wire;


use Minds\Core\Di\Di;
use Minds\Entities\Entity;

class Counter
{
    const CACHE_DURATION = 86400; // 1 day

    public static function getSumByReceiver($user_guid, $method, $timestamp = null)
    {
        $cache = Di::_()->get('Cache');
        $repository = Di::_()->get('Wire\Repository');

        if (($cached = $cache->get(static::getIndexName($user_guid, null, $method, $timestamp, false,
                false))) !== false) {
            return $cached;
        }

        $sum = $repository->getSumByReceiver($user_guid, $method);

        $cache->set(static::getIndexName($user_guid, null, $method, $timestamp, false, false), $sum,
            $timestamp ? static::CACHE_DURATION : false);

        return $sum;
    }

    public static function getSumBySender($user_guid, $method, $timestamp = null)
    {
        $cache = Di::_()->get('Cache');
        $repository = Di::_()->get('Wire\Repository');

        if (($cached = $cache->get(static::getIndexName($user_guid, null, $method, $timestamp, false,
                true))) !== false) {
            return $cached;
        }

        $sum = $repository->getSumBySender($user_guid, $method);

        $cache->set(static::getIndexName($user_guid, null, $method, $timestamp, false, true), $sum,
            $timestamp ? static::CACHE_DURATION : false);

        return $sum;
    }

    public static function getSumBySenderForReceiver($sender_guid, $receiver_guid, $method, $timestamp = null)
    {
        $cache = Di::_()->get('Cache');
        $repository = Di::_()->get('Wire\Repository');

        if (($cached = $cache->get(static::getIndexName($sender_guid, $receiver_guid, $method, $timestamp, false,
                true))) !== false) {
            return $cached;
        }

        $sum = $repository->getSumBySenderForReceiver($sender_guid, $receiver_guid, $method);

        $cache->set(static::getIndexName($sender_guid, $receiver_guid, $method, $timestamp, false, true), $sum,
            $timestamp ? static::CACHE_DURATION : false);

        return $sum;
    }

    public static function getSumByEntity($entity_guid, $method, $timestamp = null)
    {
        $cache = Di::_()->get('Cache');
        $repository = Di::_()->get('Wire\Repository');

        if (($cached = $cache->get(static::getIndexName($entity_guid, null, $method, $timestamp, true))) !== false) {
            return $cached;
        }

        $sum = $repository->getSumByEntity($entity_guid, $method, $timestamp);

        $cache->set(static::getIndexName($entity_guid, null, $method, $timestamp, true), $sum,
            $timestamp ? static::CACHE_DURATION : false);

        return $sum;
    }

    /**
     * @param string $guid1
     * @param string $guid2
     * @param string $method
     * @param string $timestamp
     * @param bool $entity
     * @param bool $sent
     * @return string
     */
    public static function getIndexName($guid1, $guid2, $method, $timestamp, $entity, $sent = false)
    {
        $guid = $guid1;
        if ($guid2) {
            $guid .= '.' . $guid2;
        }
        $lastPart = ':entity';
        if (!$entity) {
            $lastPart = ':' . $sent ? 'sent' : 'received';
        }
        if ($timestamp) {
            $lastPart .= ':' . $timestamp;
        }
        return 'counter:wire:sums:' . $guid . ':' . $method . $lastPart;
    }
}