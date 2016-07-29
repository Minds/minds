<?php
/**
 * {{plugin.name}}
 * @author {{plugin.author}}
 */

namespace Minds\Plugin\{{plugin.name}};

use Minds\Core;
use Minds\Components;
use Minds\Api;

class start extends Components\Plugin
{

    public function init()
    {

        //initialise our first api
        Api\Routes::add('v1/{{plugin.lc_name}}', 'Minds\\Plugin\\{{plugin.name}}\\Controllers\\api\\v1\\{{plugin.name}}');

    }

}
