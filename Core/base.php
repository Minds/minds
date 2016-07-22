<?php
/**
 * Minds base object. All classes inherit this.
 */
namespace Minds\Core;

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
