<?php
namespace Minds\Components;

use Minds\Core\Di\Di;

/**
 * Base Plugin component object
 * @todo Override ElggPlugin completely.
 */
class Plugin extends \ElggPlugin
{
    protected $di;

    public function __construct($plugin)
    {
        parent::__construct($plugin);
        $this->di = Di::_();
    }

    /**
     * Bootstrap method for legacy plugins
     * @todo Deprecate after migrating all plugins.
     * @param  array $flags  Deprecated.
     * @return null
     */
    public function start($flags = null)
    {
        //only legacy plugins use the start function
        $this->registerViews();
        $this->registerLanguages();
    }

    /**
     * TBD
     * @todo Review this function usage
     * @return null
     */
    public function init()
    {
    }
}
