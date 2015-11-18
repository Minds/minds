<?php
namespace Minds\Helpers;

use Minds\Core;

/**
 * Helper for exporting
 */
class Export
{
    public static function sanitize($array)
    {
        $return = array();

        foreach ($array as $k => $v) {
            if (is_numeric($v) || is_string($v)) {
                if (strlen((string) $v) < 12) {
                    $return[$k] = $v;
                } else {
                    $return[$k] = (string) $v;
                }
                $return[$k] = strip_tags(htmlspecialchars_decode($v, ENT_QUOTES));
                $return[$k] = html_entity_decode($return[$k]);
            } elseif (is_bool($v)) {
                $return[$k] = $v;
            } elseif (is_object($v) || is_array($v)) {
                $return[$k] = self::sanitize($v);
            } else {
                $return[$k] = $v;
            }
        }

        return $return;
    }
}
