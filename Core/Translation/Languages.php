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

    public function __construct($http = null)
    {
        $di = Core\Di\Di::_();

        $this->http = $http ?: $di->get('Http\Json');
        $this->config = $config ?: $di->get('Config');
    }

    public function getLanguages($target = 'en')
    {
        if (!$target) {
            $target = 'en';
        }

        $apiKey = $this->config->get('google-api-key');
        $url = "https://www.googleapis.com/language/translate/v2/languages?key={$apiKey}&target={$target}";

        // TODO: Read from in-memory cache

        try {
            $response = $this->http->get($url);
        } catch (\Exception $e) { }

        if (!$response || !isset($response['data']['languages'])) {
            return [];
        }

        // TODO: Save to in-memory cache

        return $response['data']['languages'];
    }
}
