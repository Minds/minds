<?php

/**
 * Text Helpers
 *
 * @author emi
 */

namespace Minds\Helpers;

class Text
{
    public static function slug($text, $charLimit = 0)
    {
        if (!$text) {
            return '';
        }

        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $transliteratedText = @iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $text);

        if ($transliteratedText) {
            $text = $transliteratedText;
        }

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // apply character limit (if any) and trim
        if ($charLimit > 0) {
            $text = trim(substr($text, 0, $charLimit), '-');
        }

        // lowercase
        $text = strtolower($text);

        return $text;
    }

    public static function camel($text)
    {
        return lcfirst(str_replace(['_', ':'], '', ucwords($text, '_:')));
    }

    public static function snake($text)
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $text));
    }
}
