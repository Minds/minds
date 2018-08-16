<?php


namespace Minds\Core\Queue\Runners;

use Minds\Core;
use Minds\Core\Queue;
use Minds\Core\Queue\Interfaces;
use Minds\Entities;
use Minds\Helpers\MagicAttributes;
use Minds\Core\Entities\Actions\Save;

/**
 * triggered when an admin marks a channel as explicit. It sets every post from that channel as explicit too.
 */
class MatureBatch implements Interfaces\QueueRunner
{
    public function run()
    {
        Core\Security\ACL::$ignore = true;
        $client = Queue\Client::Build();
        $client->setQueue("MatureBatch")
            ->receive(function ($data) {
                echo "Received a request to set all of the posts from a channel as explicit \n";

                $data = $data->getData();
                $user_guid = $data['user_guid'];

                $user = new Entities\User($user_guid);
                $value = (bool) $data['value'];

                $offset = '';

                $options = [
                    'owner_guid' => $user_guid
                ];

                $namespace = Core\Entities::buildNamespace(array_merge([
                    'type' => 'activity'
                ], $options));

                $activities = Core\Entities::get(array_merge([
                    'type' => 'activity',
                    'limit' => 1000,
                    'offset' => $offset,
                ], $options));

                foreach ($activities as $activity) {
                    try {
                        $this->setExplicit($activity, $value);

                        echo "Updated mature flag ($value) for {$activity->guid} \n";
                    } catch (\Exception $e) {
                        error_log($e);
                        echo "Skipped {$activity->guid} because of exception \n";
                    }
                }


                echo "Finished updating @{$user->name}'s activities' mature status";
            });
    }

    /**
     * @param Entities\Entity $entity
     * @param $value
     * @throws \IOException
     * @throws \Minds\Exceptions\StopEventException
     */
    private function setExplicit($entity, $value)
    {
        if (MagicAttributes::setterExists($entity, 'setMature')) {
            $entity->setMature($value);
        } elseif (method_exists($entity, 'setFlag')) {
            $entity->setFlag('mature', $value);
        }

        if (property_exists($entity, 'mature')) {
            $entity->mature = $value;
        }
        
        if (property_exists($entity, 'custom_data')) {
            $entity->custom_data['mature'] = $entity->getMature();
            $entity->custom_data[0]['mature'] = $entity->getMature();
        }

        if ($entity->entity_guid) {
            $attachment = Entities\Factory::build($entity->entity_guid);

            if ($attachment && $attachment->guid && $attachment instanceof Interfaces\Flaggable) {
                if (method_exists($attachment, 'setMature')) {
                    $attachment->setMature($value);
                } elseif (method_exists($attachment, 'setFlag')) {
                    $attachment->setFlag('mature', $value);
                }
                if (isset($attachment->mature)) {
                    $attachment->mature = $value;
                }
                $attachment->save();
            }
        }

        $save = new Save();
        $saved = $save->setEntity($entity)
            ->save();

        Core\Events\Dispatcher::trigger('search:index', 'all', [
            'entity' => $entity
        ]);
    }

}
