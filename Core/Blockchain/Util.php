<?php

/**
 * Blockchain utilities
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

class Util
{
    const ADDRESS = 1;
    const NUMBER = 2;

    /**
     * Parses 256-bit hexadecimal data onto 0x-prefixed decimal strings
     * @param $hex
     * @param $mappings
     * @return array
     * @throws \Exception
     */
    public static function parseData($hex, $mappings)
    {
        if (!is_string($hex) || strpos($hex, '0x') !== 0) {
            return $hex;
        }

        $hex = substr($hex, 2); //remove 0x

        if (count($mappings) !== strlen($hex) / 64) {
            //in this case we're expecting a different amount of data
            throw new \Exception("Mappings don't match data length");
        }

        $split = str_split($hex, 64);

        $result = [];
        for ($i = 0; $i < count($mappings); ++$i) {
            switch ($mappings[$i]) {
                // we remove trailing zeroes for numbers, but keep them for addresses
                case self::NUMBER:
                    $result[] = '0x' . ltrim($split[$i], 0);
                    break;

                case self::ADDRESS:
                    $result[] = '0x' . substr($split[$i], 24);
                    break;
            }
        }

        return $result;
    }
}
