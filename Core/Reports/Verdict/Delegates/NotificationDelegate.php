<?php
/**
 * Notification delegate for Verdicts
 */
namespace Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Events\EventsDispatcher;

class NotificationDelegate
{
    /** @var EventsDispatcher */
    protected $dispatcher;

    /** @var EntitiesBuilder $entitiesBuilder */
    protected $entitiesBuilder;

    public function __construct($dispatcher = null, $entitiesBuilder = null)
    {
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
    }

    public function onAction(Verdict $verdict)
    {
        $entityGuid = $verdict->getReport()->getEntityGuid();
        $entity = $this->entitiesBuilder->single($entityGuid);

        $readableAction = 'removed';

        switch ($verdict->getAction()) {
            case '2.1':
            case '2.2':
            case '2.3':
            case '2.4':
            case '2.5':
                $readableAction = 'marked as nsfw';
                break;
            case 'overturn':
                if (!$verdict->getReport()->isAppeal()) {
                    return;
                }
                $readableAction = 'restored';
                break;
        }

        if ($verdict->getReport()->isAppeal()) {
            $readableAction .= ' by the community appeal jury';
        } else {
            $readableAction .= '. You can appeal this decision';
        }
        
        $this->dispatcher->trigger('notification', 'all', [
            'to' => [$entity->getOwnerGuid()],
            'entity' => $entity,
            'from' => 100000000000000519,
            'notification_view' => 'report_actioned',
            'params' => [ 'action' => $readableAction  ],
        ]);
    }

}
