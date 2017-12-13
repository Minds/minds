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

        return '0x' . self::dec_to_hex($dec);
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

    private static function dec_to_hex($dec)
    {
        $sign = ""; // suppress errors
        if ($dec < 0) {
            $sign = "-";
            $dec = abs($dec);
        }

        $hex = [
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 'a',
            11 => 'b',
            12 => 'c',
            13 => 'd',
            14 => 'e',
            15 => 'f'
        ];

        $h = null;
        do {
            $h = $hex[($dec % 16)] . $h;
            $dec /= 16;
        } while ($dec >= 1);

        return $sign . $h;
    }
}
