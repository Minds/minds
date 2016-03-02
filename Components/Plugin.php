<?php
/**
 * A base object for plugins
 *
 *
 * @todo this is a work in progress and will replace the ElggPlugin object
 */

namespace Minds\Components;

use Minds\Core\Di\Di;

class Plugin extends \ElggPlugin
{

    protected $di;

    public function __construct($plugin)
    {
        parent::__construct($plugin);
        $this->di = Di::_();
    }

    public function start($flags = null)
    {
        //only legacy plugins use the start function
        $this->registerViews();
        $this->registerLanguages();
    }

    public function init()
    {
    }
}
