<?php
namespace Minds\Core\Translation\Services;

use Minds\Core;

class Google implements TranslationServiceInterface
{
    protected $http;
    protected $config;

    protected $apiKey;

    public function __construct($http = null, $config = null)
    {
        $di = Core\Di\Di::_();

        $this->http = $http ?: $di->get('Http\Json');
        $this->config = $config ?: $di->get('Config');

        $this->apiKey = $this->config->get('google-api-key') ?: '';
    }

    /**
     * Returns a list of available. $target
     * will localize the human-readable names.
     *
     * @param string $target
     * @return array [ [ 'language' => ..., 'name' => ... ], ... ]
     */
    public function languages($target = 'en')
    {

        $url = 'https://www.googleapis.com/language/translate/v2/languages?' . http_build_query([
            'key' => $this->apiKey,
            'target' => $target
        ]);

        try {
            $response = $this->http->get($url);
        } catch (\Exception $e) { }

        if (!isset($response['data']['languages'])) {
            return false;
        }

        return $response['data']['languages'];
    }

    /**
     * Translates $content to $target language. Omitting $source will
     * attempt to auto-detect $content's language.
     *
     * @param string $content
     * @param string $target
     * @param string $source
     * @return array [ 'content' => ..., 'source' => '' ]
     */
    public function translate($content, $target = null, $source = null)
    {
        if (!$target) {
            $target = 'en';
        }

        if (!$content) {
            // Don't send empty strings to service
            return [
                'content' => '',
                'source' => $target
            ];
        }

        $url = 'https://www.googleapis.com/language/translate/v2';
        $data = [
            'key' => $this->apiKey,
            'target' => $target,
            'source' => $source,
            'q' => $content
        ];

        try {
            $response = $this->http->post($url, $data, [
                'headers' => [
                    'Content-Type: application/x-www-form-urlencoded',
                    'X-HTTP-Method-Override: GET'
                ]
            ]);
        } catch (\Exception $e) { }

        if (!isset($response['data']['translations'][0]['translatedText'])) {
            return false;
        }

        $source = '';

        if (isset($response['data']['translations'][0]['detectedSourceLanguage'])) {
            $source = $response['data']['translations'][0]['detectedSourceLanguage'];
        }

        return [
            'content' => $response['data']['translations'][0]['translatedText'],
            'source' => $source
        ];
    }
}
