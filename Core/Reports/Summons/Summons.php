<?php
/**
 * Summon
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons;

use Exception;
use JsonSerializable;
use Minds\Traits\MagicAttributes;

/**
 * Class Summon
 * @package Minds\Core\Reports\Summons
 * @method string getReportUrn()
 * @method Summons setReportUrn(string $reportUrn)
 * @method string getJuryType()
 * @method Summons setJuryType(string $juryType)
 * @method int|string getJurorGuid()
 * @method Summons setJurorGuid(int|string $jurorGuid)
 * @method string getStatus()
 * @method int getTtl()
 * @method Summons setTtl(int $ttl)
 */
class Summons implements JsonSerializable
{
    use MagicAttributes;

    /** @var string */
    protected $reportUrn;

    /** @var string */
    protected $juryType;

    /** @var int|string */
    protected $jurorGuid;

    /** @var $status */
    protected $status;

    /** @var int */
    protected $ttl;

    /**
     * @param string $status
     * @return $this
     * @throws Exception
     */
    public function setStatus($status)
    {
        if (!in_array($status, ['awaiting', 'accepted', 'declined'])) {
            throw new Exception('Invalid status');
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAwaiting()
    {
        return $this->status === 'awaiting';
    }

    /**
     * @return bool
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /**
     * @return bool
     */
    public function isDeclined()
    {
        return $this->status === 'declined';
    }

    /**
     * @return array
     */
    public function export()
    {
        return [
            'report_urn' => $this->reportUrn,
            'jury_type' => $this->juryType,
            'juror_guid' => (string) $this->jurorGuid,
            'status' => $this->status,
            'ttl' => $this->ttl,
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->export();
    }
}
