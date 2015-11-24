<?php
/**
 * Minds boost pages
 */
namespace Minds\Controllers\Legacy;

use Minds\Core;
use Minds\Interfaces;
use Minds\Helpers;

class search extends Core\page implements Interfaces\page
{

    public function get($pages)
    {
        $this->forward('api/v1/search?q=' . $_GET['q']);
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
