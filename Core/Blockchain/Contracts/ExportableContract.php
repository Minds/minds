<?php

/**
 * Exportable
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Contracts;

abstract class ExportableContract implements \JsonSerializable, BlockchainContractInterface
{
    /** @var string $address */
    protected $address;

    /**
     * ExportableContract constructor.
     * @param $address
     */
    public function __construct($address)
    {
        $this->address = $address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public static function at($address)
    {
        return new static($address);
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return array
     */
    public function getExtra()
    {
        return [];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array_merge([
            'address' => $this->getAddress(),
            'abi' => $this->getABI()
        ], $this->getExtra() ?: []);
    }
}
