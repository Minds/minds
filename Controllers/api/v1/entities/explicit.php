<?php
/**
 * Minds Newsfeed API
 *
 * @version 1
 * @author Nicolas Ronchi
 */
namespace Minds\Controllers\api\v1\entities;

use Minds\Api\Factory;
use Minds\Core\Events\Dispatcher;
use Minds\Entities;
use Minds\Interfaces;

class explicit implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response(array());
    }

    /**
     * Sets the activity as explicit
     * @param array $pages
     *
     * API:: /v1/newsfeed/explicit
     */
    public function post($pages)
    {
        $entity = Entities\Factory::build($pages[0]);

        if (!$entity->canEdit()) {
            return Factory::response(array('status' => 'error', 'message' => 'Can´t edit this Post'));
        }

        $value = (bool) $_POST['value'];

        if ($entity->type === 'user') {
            $entity->setMatureChannel(true);
        } else {
            if (method_exists($entity, 'setMature')) {
                $entity->setMature($value);
            } elseif (method_exists($entity, 'setFlag')) {
                $entity->setFlag('mature', $value);
            }
            if (isset($entity->mature)) {
                $entity->mature = $value;
            }

            if (isset($entity->custom_data['mature'])) {
                $entity->custom_data['mature'] = $entity->getMature();
            }

            if (isset($entity->custom_data[0]['mature'])) {
                $entity->custom_data[0]['mature'] = $entity->getMature();
            }

            if ($entity->entity_guid) {
                $attachment = Entities\Factory::build($entity->entity_guid);

                if ($attachment && $attachment->guid && $attachment instanceof Interfaces\Flaggable) {
                    $attachment->setFlag('mature', $entity->getMature());
                    $attachment->save();
                }
            }
        }

        Dispatcher::trigger('search:index', 'all', [
            'entity' => $entity
        ]);
        
        $response = [ 'done' => (bool) $entity->save() ];

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
