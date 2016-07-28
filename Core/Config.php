<?php
namespace Minds\Core;

/**
 * Minds Config Manager
 *
 * @todo - move out events, hooks and views from config
 * @todo - make this not an array access but simple 1 param
 * @todo - make so we don't have a global $CONFIG.
 */
class Config extends Config\Config
{
    public static $_;

    /**
     * Returns (and builds if necessary) the singleton instance
     * @deprecated This class should be always called from the DI
     * @return static
     */
    public static function _()
    {
        return self::$_ = Di\Di::_()->get('Config');
    }

    /**
     * Legacy method to build the singleton instance. Calls _().
     * @deprecated This class should be always called from the DI
     * @return static
     */
    public static function build()
    {
        //error_log('[deprecated]: CONFIG should be called from the DI ' . print_r(debug_backtrace(), true));
        return self::_();
    }
}
