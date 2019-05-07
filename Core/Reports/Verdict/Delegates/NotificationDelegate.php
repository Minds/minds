<?php
/**
 * Notification delegate for Verdicts
 */
namespace Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Events\EventsDispatcher;
use Minds\Common\Urn;

class NotificationDelegate
{
    /** @var EventsDispatcher */
    protected $dispatcher;

    /** @var EntitiesBuilder $entitiesBuilder */
    protected $entitiesBuilder;

    /** @var Urn $urn */

    public function __construct($dispatcher = null, $entitiesBuilder = null, $urn = null)
    {
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->urn = $urn ?: new Urn;
    }

    /**
     * Actioned notification
     * @param Verdict $verdict
     * @return void
     */
    public function onAction(Verdict $verdict)
    {
        $entityUrn = $verdict->getReport()->getEntityUrn();
        $entityGuid = $this->urn->setUrn($entityUrn)->getNss();

        $entity = $this->entitiesBuilder->single($entityGuid);

        if ($verdict->isUpheld()) {
            $readableAction = 'removed';

            switch ($verdict->getReport()->getReasonCode()) {
                case 2:
                    $readableAction = 'marked as nsfw';
                    break;
            }
        } else {
            $readableAction = 'restored';
            if (!$verdict->getReport()->isAppeal()) {
                return; // Not notifiable
            }
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
