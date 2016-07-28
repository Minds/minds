<?php
namespace Minds\Core;

/**
 * Minds Base Object - All objects should inherit from this
 * @todo Proper class capitalization
 */
class base
{
    public static $di;

    public function __construct()
    {
        self::$di = Di\Di::_();
        $this->init();
    }

    public function init()
    {
    }


    public function __destruct()
    {
    }
}
