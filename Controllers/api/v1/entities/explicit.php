<?php
/**
 * Minds Newsfeed API
 *
 * @version 1
 * @author Nicolas Ronchi
 */
namespace Minds\Controllers\api\v1\entities;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Events\Dispatcher;

class explicit implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response(array());
    }

    public function post($pages)
    {
        return Factory::response(array());
    }

    /**
     * Sets the activity as explicit
     * @param array $pages
     *
     * API:: /v1/newsfeed/explicit
     */
    public function put($pages)
    {
        $activity = Entities\Factory::build($pages[0]);

        if (!$activity->canEdit()) {
            return Factory::response(array('status' => 'error', 'message' => 'Can´t edit this Post'));
        }

        $value = (bool) $_POST['value'];
        $activity->setMature($value);
        
        if (isset($activity->custom_data['mature'])) {
            $activity->custom_data['mature'] = $activity->getMature();
        }

        if (isset($activity->custom_data[0]['mature'])) {
            $activity->custom_data[0]['mature'] = $activity->getMature();
        }

        if ($activity->entity_guid) {
            $attachment = Entities\Factory::build($activity->entity_guid);

            if ($attachment && $attachment->guid && $attachment instanceof Interfaces\Flaggable) {
                $attachment->setFlag('mature', $activity->getMature());
                $attachment->save();
            }
        }

        Dispatcher::trigger('search:index', 'all', [
            'entity' => $activity
        ]);
        
        $response = [ 'done' => (bool) $activity->save() ];

    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
