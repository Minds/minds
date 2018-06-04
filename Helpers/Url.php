<?php

/**
 * URL Helpers
 *
 * @author emi
 */

namespace Minds\Helpers;

use Minds\Core\Di\Di;

class Url
{
    public static function normalize($fullUrl = '')
    {
        if (!$fullUrl) {
            return '';
        }

        $url = parse_url($fullUrl);
        $mindsUrl = parse_url(Di::_()->get('Config')->get('site_url'));

        if (!$url) {
            return '';
        }

        if (isset($url['scheme']) && !in_array(strtolower($url['scheme']), ['http', 'https'])) {
            return '';
        } elseif (!isset($url['scheme'])) {
            $url['scheme'] = 'https';
        }

        if (!isset($url['host'])) {
            $url['scheme'] = $mindsUrl['scheme'];
            $url['host'] = $mindsUrl['host'];

            if (isset($mindsUrl['port'])) {
                $url['port'] = $mindsUrl['port'];
            }
        }

        return static::unparse_url($url);
    }

    public static function unparse_url(array $parsed_url)
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? '/' . ltrim($parsed_url['path'], '/') : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }
}
