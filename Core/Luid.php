<?php

/**
 * Minds Long Unique Identifier
 *
 * @author emi
 */

namespace Minds\Core;

use Minds\Exceptions\InvalidLuidException;
use Minds\Helpers\Text;

/**
 * Class Luid
 * @package Minds\Core
 */
class Luid implements \JsonSerializable
{
    protected $components = [];

    /**
     * Luid constructor.
     * @param string|Luid|null $luid
     * @throws InvalidLuidException
     * @throws \Exception
     */
    public function __construct($luid = null)
    {
        if ($luid) {
            if ($luid instanceof static) {
                // Re-parse cloning
                $luid = $luid->build();
            } else if (!is_string($luid)) {
                throw new InvalidLuidException('Constructed with an invalid LUID');
            }

            $this->parse($luid);
        }
    }

    /**
     * Sets the type component
     * @param string $value
     * @return Luid
     */
    public function setType($value)
    {
        $this->set('_type', $value);
        return $this;
    }

    /**
     * Gets the type component
     * @return string|null
     */
    public function getType()
    {
        return $this->get('_type');
    }

    /**
     * Gets a component
     * @param $descriptor
     * @return mixed|null
     */
    public function get($descriptor)
    {
        return isset($this->components[$descriptor]) ?
            $this->components[$descriptor] : null;
    }

    /**
     * Sets a component
     * @param string $descriptor
     * @param string $value
     * @return $this
     */
    public function set($descriptor, $value)
    {
        $this->components[$descriptor] = (string) $value;
        return $this;
    }

    /**
     * Deletes a component
     * @param string $descriptor
     * @return $this
     */
    public function delete($descriptor)
    {
        if (isset($this->components[$descriptor])) {
            unset($this->components[$descriptor]);
        }

        return $this;
    }

    /**
     * Magic caller
     * @param $name
     * @param $arguments
     * @return Luid|mixed|null
     */
    public function __call($name, $arguments)
    {
        $prefix = substr($name, 0, 3);
        $descriptor = substr($name, 3);

        if ($prefix === 'get') {
            return $this->get(Text::snake($descriptor));
        } elseif ($prefix === 'set') {
            return $this->set(Text::snake($descriptor), (string) $arguments[0]);
        }

        throw new \BadMethodCallException("{$name} is not a valid method of " . get_class($this));
    }

    /**
     * Parses and imports a string-encoded representation of a LUID
     * @param string $luid
     * @return Luid
     * @throws InvalidLuidException
     */
    public function parse($luid = null)
    {
        if (!$luid || !is_string($luid) || !base64_decode($luid, true)) {
            throw new InvalidLuidException("{$luid} is not a valid LUID");
        }

        $json = json_decode(base64_decode($luid), true);

        if (!$json || !is_array($json) || !isset($json['_type'])) {
            throw new InvalidLuidException("{$luid} is not a valid LUID");
        }

        $this->components = [];

        foreach ($json as $key => $value) {
            $this->components[$key] = (string) $value;
        }

        return $this;
    }

    /**
     * Returns a string-encoded representation of the LUID
     * @return string
     * @throws \Exception
     */
    public function build()
    {
        $components = $this->components;

        if (!$components) {
            throw new \Exception('No components');
        }

        if (!isset($components['_type']) || !$components['_type']) {
            throw new \Exception('No type specified');
        }

        ksort($components, SORT_STRING);
        $luid = base64_encode(json_encode($components));

        return $luid;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->build();
        } catch (\Exception $e) {
            return '';
        }
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
        return $this->__toString();
    }

    /**
     * @param string $luid
     * @return bool
     * @throws \Exception
     */
    public static function isValid($luid)
    {
        try {
            new static($luid);
        } catch (InvalidLuidException $e) {
            return false;
        }

        return true;
    }
}
