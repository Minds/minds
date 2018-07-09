<?php
namespace Minds\Core\I18n;

use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Session;

class I18n
{
    const DEFAULT_LANGUAGE = 'en';
    const DEFAULT_LANGUAGE_NAME = 'English';

    /** @var Config */
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    public function getLanguages()
    {
        $languages = $this->config->get('i18n')['languages'] ?: [
            static::DEFAULT_LANGUAGE => static::DEFAULT_LANGUAGE_NAME
        ];

        return $languages;
    }

    public function getLanguage()
    {
        $user = Session::getLoggedInUser();

        if (!$user) {
            return static::DEFAULT_LANGUAGE;
        }

        return $user->getLanguage() ?: static::DEFAULT_LANGUAGE;
    }

    public function serveIndex()
    {
        $dist = realpath(__MINDS_ROOT__ . '/../front/dist');
        $language = $this->getLanguage();
        $defaultLanguage = static::DEFAULT_LANGUAGE;

        $file = "{$dist}/{$language}/index.php";

        if (!file_exists($file)) {
            $file = "{$dist}/{$defaultLanguage}/index.php";
        }

        include($file);
    }
}
