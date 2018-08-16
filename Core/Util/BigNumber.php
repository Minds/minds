<?php

/**
 * Big Number implementation (BCMath)
 *
 * @author emi
 */

namespace Minds\Core\Util;

use JsonSerializable;

class BigNumber implements JsonSerializable
{
    /** @var string $value */
    protected $value;

    /** @var int $scale */
    protected $scale;

    /**
     * BigNumber constructor.
     * @param $value
     * @param int $scale
     * @throws \Exception
     */
    public function __construct($value, $scale = 0)
    {
        $this->scale = (int) $scale;
        $this->value = $this->normalize($value);
    }

    /**
     * Magic casting to string.
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Returns the value as a string.
     * @return string
     */
    public function toString()
    {
        return (string) $this->value;
    }

    /**
     * Returns the value as a double/float.
     * !!!WARNING!!! for really big numbers it might lose some precision
     * @return float
     */
    public function toDouble()
    {
        return (double) $this->toString();
    }

    /**
     * Returns the value as a double/float.
     * !!!WARNING!!! for really big numbers it might lose some precision
     * @return float
     */
    public function toInt()
    {
        return (int) $this->toString();
    }

    /**
     * Sets the current decimal scale.
     * @param int $scale
     * @return BigNumber
     */
    public function setScale($scale)
    {
        $this->scale = (int) $scale;
        return $this;
    }

    /**
     * Gets the current decimal scale.
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Adds value to another.
     * @param $rightOperand
     * @return BigNumber
     * @throws \Exception
     */
    public function add($rightOperand)
    {
        return $this->immutable(bcadd($this->value, $this->normalize($rightOperand), $this->scale));
    }

    /**
     * Subtracts value to another.
     * @param $rightOperand
     * @return BigNumber
     * @throws \Exception
     */
    public function sub($rightOperand)
    {
        return $this->immutable(bcsub($this->value, $this->normalize($rightOperand), $this->scale));
    }

    /**
     * Multiplies value to another.
     * @param $rightOperand
     * @return BigNumber
     * @throws \Exception
     */
    public function mul($rightOperand)
    {
        return $this->immutable(bcmul($this->value, $this->normalize($rightOperand), $this->scale));
    }

    /**
     * Divides value to another.
     * @param $rightOperand
     * @return BigNumber
     * @throws \Exception
     */
    public function div($rightOperand)
    {
        return $this->immutable(bcdiv($this->value, $this->normalize($rightOperand), $this->scale));
    }

    /**
     * Raises value to another.
     * @param $rightOperand
     * @return BigNumber
     * @throws \Exception
     */
    public function pow($rightOperand)
    {
        return $this->immutable(bcpow($this->value, $this->normalize($rightOperand), $this->scale));
    }

    /**
     * Gets the square root of value.
     * @return BigNumber
     * @throws \Exception
     */
    public function sqrt()
    {
        return $this->immutable(bcsqrt($this->value, $this->scale));
    }

    /**
     * Inverts the value's sign.
     * @return BigNumber
     * @throws \Exception
     */
    public function neg()
    {
        return $this->mul(-1);
    }

    /**
     * Compares value to another, returning true if equal.
     * Equivalent to ===.
     * @param $rightOperand
     * @return bool
     * @throws \Exception
     */
    public function eq($rightOperand)
    {
        return bccomp($this->value, $this->normalize($rightOperand), $this->scale) === 0;
    }

    /**
     * Compares value to another, returning true if value is less than another.
     * Equivalent to <.
     * @param $rightOperand
     * @return bool
     * @throws \Exception
     */
    public function lt($rightOperand)
    {
        return bccomp($this->value, $this->normalize($rightOperand), $this->scale) === -1;
    }

    /**
     * Compares value to another, returning true if value is less than or equal to another.
     * Equivalent to <=.
     * @param $rightOperand
     * @return bool
     * @throws \Exception
     */
    public function lte($rightOperand)
    {
        return bccomp($this->value, $this->normalize($rightOperand), $this->scale) <= 0;
    }

    /**
     * Compares value to another, returning true if value is greater than another.
     * Equivalent to >.
     * @param $rightOperand
     * @return bool
     * @throws \Exception
     */
    public function gt($rightOperand)
    {
        return bccomp($this->value, $this->normalize($rightOperand), $this->scale) === 1;
    }

    /**
     * Compares value to another, returning true if value is greater than or equal to another.
     * Equivalent to >=.
     * @param $rightOperand
     * @return bool
     * @throws \Exception
     */
    public function gte($rightOperand)
    {
        return bccomp($this->value, $this->normalize($rightOperand), $this->scale) >= 0;
    }

    /**
     * Converts value to another base.
     * @param int $base
     * @return string
     * @throws \Exception
     */
    public function toBase($base)
    {
        if ($base < 2 || $base > 36) {
            throw new \Exception('Invalid base');
        }

        $base = (string) $base;
        $sign = bccomp($this->value, '0') === -1 ? '-' : '';
        $dec = ltrim($this->value, '-');
        $based = '';

        do {
            $based = base_convert(bcmod($dec, $base), 10, (int) $base) . $based;
            $dec = bcdiv($dec, $base, '0');
        } while (bccomp($dec, 0) === 1);

        return $sign . $based;
    }

    /**
     * Converts the value to hexadecimal.
     * @return string
     * @throws \Exception
     */
    public function toHex($prefix = false)
    {
        return ($prefix ? '0x' : '') . $this->toBase(16);
    }

    /**
     * Creates a new instance using a value represented in another base.
     * @param $based
     * @param $base
     * @return static
     * @throws \Exception
     */
    public static function fromBase($based, $base)
    {
        if ($base < 2 || $base > 36) {
            throw new \Exception('Invalid base');
        }

        $base = (string) $base;

        if (!$based) {
            return new static(0);
        }

        $digits = array_reverse(str_split($based));
        $dec = '0';

        for ($i = 0; $i < count($digits); $i++) {
            $mul = bcpow($base, (string) $i, '0');
            $part = bcmul(base_convert($digits[$i], (int) $base, 10), $mul, '0');

            $dec = bcadd($dec, $part, '0');
        }

        return new static($dec, 0);
    }

    /**
     * Creates a new instance using an hexadecimal value.
     * @param $value
     * @return BigNumber
     * @throws \Exception
     */
    public static function fromHex($value)
    {
        if (stripos($value, '0x') === 0) {
            $value = substr($value, 2);
        }

        return static::fromBase($value, 16);
    }

    /**
     * Creates a new instance with the value converted to plain decimals (used by Eth). (x)
     * @param $value
     * @param int $decimalPlaces
     * @return static
     * @throws \Exception
     */
    public static function toPlain($value, $decimalPlaces)
    {
        $decimal = (new static(10))->pow((int) $decimalPlaces);
        return (new static($value))->mul($decimal);
    }

    /**
     * Creates a new instance with the value converted from plain decimals (used by Eth). (/)
     * @param $value
     * @param int $decimalPlaces
     * @return static
     * @throws \Exception
     */
    public static function fromPlain($value, $decimalPlaces)
    {
        $decimal = (new static(10))->pow((int) $decimalPlaces);
        return (new static($value, $decimalPlaces))->div($decimal);
    }

    /**
     * Factory method.
     * @param $value
     * @param int $base
     * @return static
     */
    public static function _($value, $base = 0)
    {
        return new static($value, $base);
    }

    /**
     * Creates a new instance of BigNumber with the provided value.
     * @param $value
     * @return static
     * @throws \Exception
     */
    protected function immutable($value) {
        return new static($value, $this->scale);
    }

    /**
     * Normalizes a value.
     * Accepts Cassandra types, exp notation and any numeric value.
     * @param $value
     * @return string
     * @throws \Exception
     */
    protected function normalize($value)
    {
        if (
            is_object($value) &&
            strpos(trim(get_class($value), '\\'), 'Cassandra') === 0
        ) {
            $value = $value->value();
        }

        try { // Avoid exceptions
            $stringValue = @strtolower((string) $value);

            if (preg_match("/^-?[0-9]+(\.[0-9]+)?e[-+]?[0-9]+$/", $stringValue)) {
                $parts = explode('e', $stringValue, 2);
                $value = bcmul($parts[0], bcpow('10', $parts[1], $this->scale), $this->scale);
            }
        } catch (\Exception $e) { }

        $value = (string) $value;

        if ($value === '' || !is_numeric($value)) {
            throw new \Exception('Expected a numeric value');
        }

        return $value;
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
        return $this->toString();
    }
}
