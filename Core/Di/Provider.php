<?php
/**
 * Minds Dependency Injection
 */
namespace Minds\Core\Di;

class Provider
{
    protected $di;

    public function __construct()
    {
        $this->di = Di::_();
    }
}
