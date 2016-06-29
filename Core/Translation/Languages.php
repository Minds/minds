<?php
/**
 * Minds Translations Engine
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Core\Translation;

use Minds\Core;

class Languages
{
    protected $cache;
    protected $service;

    public function __construct($cache = null, $service = null)
    {
        $di = Core\Di\Di::_();

        $this->cache = $cache ?: $di->get('Cache');
        $this->service = $service ?: $di->get('Translation\Service');
    }

    public function getLanguages($target = 'en')
    {
        if (!$target) {
            $target = 'en';
        }

        $cached = $this->cache->get("translation:languages:{$target}");
        if ($cached !== false) {
            return $cached;
        }

        $languages = $this->service->languages($target);

        if (!$languages) {
            // Cache failure as an empty array for 30 minutes
            // (just in case anything happened at Google Side)
            $this->cache->set("translation:languages:{$target}", [ ], 30 * 60);
            return [];
        }

        // Cache for 12 hours
        $this->cache->set("translation:languages:{$target}", $languages, 12 * 60 * 60);

        return $languages;
    }
}
