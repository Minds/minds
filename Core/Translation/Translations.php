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

    const MAX_CONTENT_LENGTH = 5000;

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

        $entity = null; // Lazily-loaded if needed
        $translation = [];

        foreach ([ 'message', 'title', 'blurb', 'description' ] as $field) {
            $stored = $storage->get($guid, $field, $target);

            if ($stored !== false) {
                // Saved in cache store
                $translation[$field] = [
                    'content' => $stored['content'],
                    'source' => $stored['source_language']
                ];
                continue;
            }

            if (!$entity) {
                $entity = Entities\Factory::build($guid);
            }

            if (!$entity) {
                continue;
            }

            $content = '';

            switch ($field) {
                case 'message':
                    if (method_exists($entity, 'getMessage')) {
                        $content = $entity->getMessage();
                    } elseif (property_exists($entity, 'message') || isset($entity->message)) {
                        $content = $entity->message;
                    }
                    break;

                case 'description':
                    if (method_exists($entity, 'getDescription')) {
                        $content = $entity->getDescription();
                    } elseif (property_exists($entity, 'description') || isset($entity->description)) {
                        $content = $entity->description;
                    }
                    break;

                case 'title':
                case 'blurb':
                    if (!$entity->custom_type) {
                        continue 2; // exit switch AND continue foreach
                    }

                    if (property_exists($entity, $field) || isset($entity->{$field})) {
                        $content = $entity->{$field};
                    }
                    break;
            }

            if (strlen($content) === 0) {
                continue;
            }

            if (strlen($content) > static::MAX_CONTENT_LENGTH) {
                $content = substr($content, 0, static::MAX_CONTENT_LENGTH);
            }

            $translation[$field] = $this->translateText($content, $target);

            if ($translation[$field]) {
                $storage->set($guid, $field, $target, $translation[$field]['source'], $translation[$field]['content']);
            }
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
