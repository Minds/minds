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
    protected $http;
    protected $config;

    public function __construct($http = null, $cache = null, $config = null)
    {
        $di = Core\Di\Di::_();

        $this->http = $http ?: $di->get('Http\Json');
        $this->cache = $cache ?: $di->get('Cache');
        $this->config = $config ?: $di->get('Config');
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

        $apiKey = $this->config->get('google-api-key');
        $url = "https://www.googleapis.com/language/translate/v2/languages?key={$apiKey}&target={$target}";

        try {
            $response = $this->http->get($url);
        } catch (\Exception $e) { }

        if (!$response || !isset($response['data']['languages'])) {
            // Cache failure for 30 minutes (just in case anything happened at Google Side)
            $this->cache->set("translation:languages:{$target}", [ ], 30 * 60);
            return [];
        }

        $this->cache->set("translation:languages:{$target}", $response['data']['languages'], 6 * 60 * 60);

        return $response['data']['languages'];
    }
}
