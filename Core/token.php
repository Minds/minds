<?php
/**
 * Simple page tokens
 */
namespace Minds\Core;

class token
{
    public static function generate($ts=null)
    {
        return;
        $site_secret = \get_site_secret();
        // Session token
        $st = $_SESSION['__elgg_session'];
        
        if (($site_secret)) {
            return md5($site_secret . $st . $ts);
        }
    }

    public static function validate($ts = null, $token=null)
    {
        return;
        if (!$ts) {
            $ts = \get_input('__elgg_ts');
        }
        if (!$token) {
            $token = \get_input('__elgg_token');
        }
        
        if (self::generate($ts) == $token) {
            return true;
        }
        
        return false;
    }
}
