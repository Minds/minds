<?php
/**
 * Urn.
 *
 * @author emi
 */

namespace Minds\Common;

class Urn implements \JsonSerializable
{
    /** @var string */
    protected $urn;

    /** @var string */
    protected $nid;

    /** @var string */
    protected $nss;

    /**
     * Urn constructor.
     * @param string $urn
     * @throws \Exception
     */
    public function __construct($urn = null)
    {
        if ($urn) {
            $this->setUrn($urn);
        }
    }

    /**
     * @param string $urn
     * @return Urn
     * @throws \Exception
     */
    public function setUrn($urn)
    {
        if (is_numeric($urn)) {
            $urn = "urn:entity:{$urn}";
        }

        if (!static::isValid($urn)) {
            throw new \Exception('Invalid URN');
        }

        $this->urn = $urn;

        $parts = explode(':', $urn, 3);

        array_shift($parts); // Discard `urn` part
        $this->nid = array_shift($parts);
        $this->nss = array_shift($parts);

        return $this;
    }

    /**
     * @return string
     */
    public function getUrn()
    {
        return $this->urn;
    }

    /**
     * @return string
     */
    public function getNid()
    {
        return strtolower($this->nid);
    }

    /**
     * @return string
     */
    public function getNss()
    {
        return $this->nss;
    }

    /**
     * @return string[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @param int $index
     * @return string|null
     */
    public function getComponent($index)
    {
        return $this->components[$index] ?? null;
    }

    /**
     * @param string $urn
     * @return bool
     */
    public static function isValid($urn)
    {
        return is_numeric($urn) || (bool) preg_match('/^urn:[a-z0-9][a-z0-9-]{0,31}:([a-z0-9()+,\\-.:=@;$_!*\']|%[0-9a-f]{2})+$/i', $urn);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->urn;
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
        return $this->urn;
    }

    /**
     * @param $urn
     * @return Urn
     * @throws \Exception
     */
    public static function _($urn)
    {
        return new static($urn);
    }
}
