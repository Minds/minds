<?php
namespace Minds\Helpers;

/**
 * Helper to validate JSON
 * @todo This class might be either deprecated or merged into a more complete JSON helper.
 */
class Validation
{
    /**
     * Check if the passed string is a valid JSON
     * @return boolean
     */
    public static function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }

        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
