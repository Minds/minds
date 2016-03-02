<?php
/**
 * Minds Config manager
 *
 * @todo - move out events, hooks and views from config
 * @todo - make this not an array access but simple 1 param
 * @todo - make so we don't have a global $CONFIG.
 */
namespace Minds\Core;

class Config extends Config\Config
{
    public static $_;

    public static function _()
    {
        return self::$_ = Di\Di::_()->get('Config');
    }

    public static function build()
    {
        //error_log('[deprecated]: CONFIG should be called from the DI ' . print_r(debug_backtrace(), true));
        return self::_();
    }

}
