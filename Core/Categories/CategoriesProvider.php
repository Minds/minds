<?php
/**
 * Minds Categories Provider
 */

namespace Minds\Core\Categories;

use Minds\Core\Di\Provider;

class CategoriesProvider extends Provider
{
    public function register()
    {
        /**
         * Categories repository
         */
        $this->di->bind('Categories\Repository', function ($di) {
            return new Repository;
        }, ['useFactory'=>true]);
    }
}
