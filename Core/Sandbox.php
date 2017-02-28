<?php
namespace Minds\Core;

use Minds\Core\Di\Di;
use Minds\Entities;

class Sandbox
{
    public static function user($default, $sandbox = 'default')
    {
        $config = Di::_()->get('Config')->get('sandbox');

        if (!$config) {
            return $default;
        }

        if (!$config['enabled']) {
            return $default;
        }

        $guid = $config[$sandbox]['guid'];
        error_log(json_encode($config));

        error_log(':: [Sandbox] Sandboxing user ' . $guid);
        return new Entities\User($guid);
    }
}
