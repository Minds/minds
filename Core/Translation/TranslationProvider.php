<?php
/**
 * Minds Translation Provider
 */

namespace Minds\Core\Translation;

use Minds\Core\Di\Provider;

class TranslationProvider extends Provider
{
    public function register()
    {
        /**
         * Translation bindings
         */
        $this->di->bind('Translation\Service', function($di){
            return new Services\Google();
        }, ['useFactory'=>true]);
    }
}
