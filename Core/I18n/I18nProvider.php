<?php
/**
 * Minds i18n Provider
 */

namespace Minds\Core\I18n;

use Minds\Core\Di\Provider;

class I18nProvider extends Provider
{
    public function register()
    {
        $this->di->bind('I18n', function ($di) {
            return new I18n();
        }, [ 'useFactory' => true ]);
    }
}
