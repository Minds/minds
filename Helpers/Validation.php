<?php
/**
 * Validate php
 */
namespace Minds\Helpers;

class Validation
{
    /**
     * Check if is json
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
