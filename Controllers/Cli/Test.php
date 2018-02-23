<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Exceptions\ProvisionException;

class Test extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
        define('__MINDS_INSTALLING__', true);
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec()
    {
        $namespace = Core\Entities::buildNamespace([
            'type' => 'object',
            'subtype'=> 'video',
            'network'=>'732337264197111809'
        ]);

        $this->out($namespace);

    }

    private function getTrendingActivities()
    {
        $result = Core\Di\Di::_()->get('Trending\Repository')->getList([
            'type' => 'newsfeed',
            'limit' => 12
        ]);
        ksort($result['guids']);
        $options['guids'] = $result['guids'];

        $activities = Core\Entities::get(array_merge([
                'type' => 'activity'
            ]
            , $options));

        $activities = array_filter($activities, function ($activity) {
            if ($activity->paywall) {
                return false;
            }

            if ($activity->remind_object && $activity->remind_object['paywall']) {
                return false;
            }

            return true;
        });

        return $activities;
    }
}
