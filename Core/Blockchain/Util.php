<?php

/**
 * Blockchain utilities
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

class Util
{
    /**
     * Converts an 0x-prefixed hexadecimal string onto a decimal number
     * @param string $hex
     * @return float|int
     */
    public static function toDec($hex)
    {
        if (!is_string($hex) || strpos($hex, '0x') !== 0) {
            return $hex;
        }

        return hexdec(substr($hex, 2));
    }

    /**
     * Converts a decimal number onto an 0x-prefixed hexadecimal string
     * @param number $dec
     * @return string
     */
    public static function toHex($dec)
    {
        if (!is_numeric($dec)) {
            return $dec;
        }

        return '0x' . dechex($dec);
    }

    /**
     * Parses 256-bit hexadecimal data onto 0x-prefixed decimal strings
     * @param $hex
     * @return array
     */
    public static function parseData($hex)
    {
        if (!is_string($hex) || strpos($hex, '0x') !== 0) {
            return $hex;
        }

        return array_map(function ($part) {
            return '0x' . ltrim($part, '0');
        }, str_split(substr($hex, 2), 64));
    }
}
