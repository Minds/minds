<?php
/**
 * Steward events.
 */

namespace Minds\Core\Steward;

use Minds\Core\Events\Dispatcher;
use Minds\Interfaces\ModuleInterface;
use Minds\Core\Di\Di;

class Events implements ModuleInterface
{
    private $autoReporter;

    public function __construct($autoReport = null)
    {
        $this->autoReporter = $autoReports ?: Di::_()->get('Steward\AutoReporter');
    }

    public function onInit()
    {
    }

    public function register()
    {
        Dispatcher::register('create', 'elgg/event/activity', function ($event, $namespace, $entity) {
            //$this->autoReporter->validate($entity);
        });

        Dispatcher::register('update', 'elgg/event/activity', function ($event, $namespace, $entity) {
            //$this->autoReporter->validate($entity);
        });
    }
}
