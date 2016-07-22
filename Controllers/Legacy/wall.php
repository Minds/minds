<?php
/**
 * Minds boost pages
 */
namespace Minds\Controllers\Legacy;

use Minds\Core;
use Minds\Interfaces;
use Minds\Helpers;

class wall extends core\page implements Interfaces\page
{
    public function get($pages)
    {
        forward('archive/thumbnail/' . $pages[1]);
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
