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
