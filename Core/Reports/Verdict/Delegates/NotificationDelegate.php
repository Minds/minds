<?php
/**
 * Notification delegate for Verdicts
 */

namespace Minds\Core\Reports\Verdict\Delegates;

use Minds\Common\Urn;
use Minds\Core\Di\Di;
use Minds\Core\Entities\Resolver;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Reports\Verdict\Verdict;

class NotificationDelegate
{
    /** @var EventsDispatcher */
    protected $dispatcher;

    /** @var EntitiesBuilder $entitiesBuilder */
    protected $entitiesBuilder;

    /** @var Urn $urn */
    protected $urn;

    /** @var Resolver */
    protected $entitiesResolver;

    public function __construct($dispatcher = null, $entitiesBuilder = null, $urn = null, $entitiesResolver = null)
    {
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->urn = $urn ?: new Urn;
        $this->entitiesResolver = $entitiesResolver ?: new Resolver();
    }

    /**
     * Actioned notification
     * @param Verdict $verdict
     * @return void
     * @throws \Exception
     */
    public function onAction(Verdict $verdict)
    {
        $entityUrn = $verdict->getReport()->getEntityUrn();

        $entity = $this->entitiesResolver->single($this->urn->setUrn($entityUrn));

        if (!$entity) {
            $entityGuid = $this->urn->setUrn($entityUrn)->getNss();

            $entity = $this->entitiesBuilder->single($entityGuid);
        }

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
            'params' => ['action' => $readableAction],
        ]);
    }

}
