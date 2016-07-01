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
    protected $cache;
    protected $service;

    const MAX_CONTENT_LENGTH = 1000;

    public function __construct($cache = null, $service = null)
    {
        $di = Core\Di\Di::_();

        $this->cache = $cache ?: $di->get('Cache');
        $this->service = $service ?: $di->get('Translation\Service');
    }

    public function translateEntity($guid, $target = null)
    {
        $storage = new Storage(); 

        if (!$guid) {
            return false;
        }

        if (!$target) {
            $target = 'en';
        }

        $stored = $storage->get($guid, 'message', $target);

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

        if (strlen($message) > static::MAX_CONTENT_LENGTH) {
            $message = substr($message, 0, static::MAX_CONTENT_LENGTH);
        }

        $translation = $this->translateText($message, $target);

        if ($translation) {
            $storage->set($guid, 'message', $target, $translation['source'], $translation['content']);
        }

        return $translation;
    }

    public function translateText($content, $target = null, $source = null)
    {
        if (!$target) {
            $target = 'en';
        }

        return $this->service->translate($content, $target, $source);
    }
}
