<?php


namespace Minds\Controllers\api\v2\analytics;


use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers\Counters;
use Minds\Interfaces;


class views implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        $viewsManager = new Core\Analytics\Views\Manager();

        switch ($pages[0]) {
            case 'boost':
                $expire = Di::_()->get('Boost\Network\Expire');
                $metrics = Di::_()->get('Boost\Network\Metrics');
                $manager = Di::_()->get('Boost\Network\Manager');

                $urn = "urn:boost:newsfeed:{$pages[1]}";

                $boost = $manager->get($urn, [ 'hydrate' => true ]);
                if (!$boost) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Could not find boost'
                    ]);
                }
                
                $count = $metrics->incrementViews($boost);

                if ($count > $boost->getImpressions()) {
                    $expire->setBoost($boost);
                    $expire->expire();
                }

                Counters::increment($boost->getEntity()->guid, "impression");
                Counters::increment($boost->getEntity()->owner_guid, "impression");

                try {
                    // TODO: Ensure client_meta campaign matches this boost

                    $viewsManager->record(
                        (new Core\Analytics\Views\View())
                            ->setEntityUrn($boost->getEntity()->getUrn())
                            ->setClientMeta($_POST['client_meta'] ?? [])
                    );
                } catch (\Exception $e) {
                    error_log($e);
                }

                return Factory::response([
                    'status' => 'success',
                    'impressions' => $boost->getImpressions(),
                    'impressions_met' => $count,
                ]);
                break;
            case 'activity':
                $activity = new Entities\Activity($pages[1]);

                if (!$activity->guid) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Could not find activity post'
                    ]);
                }

                try {
                    Core\Analytics\App::_()
                        ->setMetric('impression')
                        ->setKey($activity->guid)
                        ->increment();

                    if ($activity->remind_object) {
                        Core\Analytics\App::_()
                            ->setMetric('impression')
                            ->setKey($activity->remind_object['guid'])
                            ->increment();

                        Core\Analytics\App::_()
                            ->setMetric('impression')
                            ->setKey($activity->remind_object['owner_guid'])
                            ->increment();
                    }

                    Core\Analytics\User::_()
                        ->setMetric('impression')
                        ->setKey($activity->owner_guid)
                        ->increment();
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                }

                try {
                    $viewsManager->record(
                        (new Core\Analytics\Views\View())
                            ->setEntityUrn($activity->getUrn())
                            ->setClientMeta($_POST['client_meta'] ?? [])
                    );
                } catch (\Exception $e) {
                    error_log($e);
                }

                break;
        }

        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}
