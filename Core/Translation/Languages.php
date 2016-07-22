<?php
/**
 * Minds Translations Engine
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Core\Translation;

use Minds\Core;
use Minds\Entities\User;

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

    public function getLanguages($target = 'en', array $preferred = [])
    {
        if (!$target) {
            $target = 'en';
        }

        $cached = $this->cache->get("translation:languages:{$target}");
        if ($cached !== false) {
            return $this->sortAndPrependPreferred($cached, $preferred);
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

        return $this->sortAndPrependPreferred($languages, $preferred);
    }

    public function changeUserLanguage(&$user, $target)
    {
        if (!$user || !($user instanceof User) || !$target) {
            return false;
        }

        if ($user->defaultLang && $user->defaultLang == $target) {
            return false;
        }

        $user->defaultLang = $target;
        return true;
    }

    protected function sortAndPrependPreferred(array $languages, array $prepend)
    {
        array_walk($languages, function (&$language) use ($prepend) {
            if (in_array($language['language'], $prepend)) {
                $language['isPreferred'] = true;
            }
        });

        usort($languages, function ($a, $b) {
            $aPreferred = isset($a['isPreferred']);
            $bPreferred = isset($b['isPreferred']);

            if ($aPreferred && $bPreferred) {
                return 0;
            } elseif (!$aPreferred && !$bPreferred) {
                if (isset($a['name']) && isset($b['name'])) {
                    if ($a['name'] == $b['name']) {
                        return 0;
                    }

                    return $a['name'] > $b['name'] ? 1 : -1;
                }

                return 0;
            }

            return $aPreferred ? -1 : 1;
        });

        return $languages;
    }
}
