<?php
/**
 * Snapshot.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Snapshots;

use Minds\Helpers\Cql;
use Minds\Traits\MagicAttributes;

/**
 * Class Snapshot
 * @package Minds\Core\Channels\Snapshots
 *
 * @method string|int getUserGuid()
 * @method Snapshot setUserGuid(string|int $userGuid)
 * @method string getType()
 * @method Snapshot setType(string $type)
 * @method string getKey()
 */
class Snapshot
{
    use MagicAttributes;

    /** @var string|int */
    protected $userGuid;

    /** @var string */
    protected $type;

    /** @var string */
    protected $key;

    /** @var string */
    protected $jsonData;

    /**
     * @param string|string[] $key
     * @return Snapshot
     */
    public function setKey($key)
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        $this->key = implode("\t", $key);

        return $this;
    }

    /**
     * @param bool $raw
     * @return mixed
     */
    public function getJsonData($raw = false)
    {
        if ($raw) {
            return $this->jsonData;
        }

        return $this->jsonData ? json_decode($this->jsonData, true) : [];
    }

    /**
     * @param mixed $jsonData
     * @return Snapshot
     */
    public function setJsonData($jsonData)
    {
        $this->jsonData = is_string($jsonData) ? $jsonData : json_encode(Cql::toPrimitiveType($jsonData));
        return $this;
    }
}
