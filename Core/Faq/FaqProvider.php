<?php

/**
 * Minds FAQ Provider
 *
 * @author Mark Harding
 */

namespace Minds\Core\Faq;

use Minds\Core\Di\Provider;

class FaqProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Faq', function ($di) {
            return new Manager();
        }, [ 'useFactory'=> true ]);
    }
}
