<?php
/**
 * Minds Translations Engine
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Core\Translation;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Translation\Storage;

class Translations
{
    protected $http;
    protected $config;

    public function __construct($http = null, $config = null)
    {
        $di = Core\Di\Di::_();

        $this->http = $http ?: $di->get('Http\Json');
        $this->config = $config ?: $di->get('Config');
    }

    public function translateEntity($guid, $target = 'en')
    {
        $storage = new Storage(); 

        if (!$guid) {
            return false;
        }

        if (!$target) {
            $target = 'en';
        }

        $stored = $storage->get($guid, $target);

        if ($stored !== false) {
            // Saved in cache store
            return [
                'content' => $stored['content'],
                'source' => $stored['source_language']
            ];
        }

        $entity = Entities\Factory::build($guid);

        if (!$entity) {
            return false;
        }

        $message = '';

        if (method_exists($entity, 'getMessage')) {
            $message = $entity->getMessage();
        } elseif (property_exists($entity, 'message') || isset($entity->message)) {
            $message = $entity->message;
        }

        // TODO: Check comments support

        $translation = $this->translateText($message, $target);

        if ($translation) {
            $storage->set($guid, $target, $translation['source'], $translation['content']);
        }

        return $translation;
    }

    public function translateText($query, $target = 'en')
    {
        if (!$target) {
            $target = 'en';
        }

        if (!$query) {
            // Don't send empty strings to service
            return [
                'content' => '',
                'source' => $target
            ];
        }

        $apiKey = $this->config->get('google-api-key');
        $query = urlencode($query);
        $url = "https://www.googleapis.com/language/translate/v2?key={$apiKey}&target={$target}&q={$query}";

        $response = $this->http->get($url);

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
