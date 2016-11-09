<?php
namespace Minds\Core\I18n;

use Minds\Core\Session;

class I18n
{
    public function getLanguage()
    {
        $defaultLanguage = 'en';
        $user = Session::getLoggedInUser();

        if (!$user) {
            return $defaultLanguage;
        }

        return $user->getLanguage() ?: $defaultLanguage;
    }
}
