<?php
/**
 * Email Notification delegate for Strikes
 */
namespace Minds\Core\Reports\Strikes\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Reports\Strikes\Strike;
use Minds\Core\Events\EventsDispatcher;
use Minds\Common\Urn;
use Minds\Core\Email\Campaigns\Custom;

class EmailDelegate
{
    /** @var Custom $campaign */
    protected $campaign;

    /** @var EntitiesBuilder $entitiesBuilder */
    protected $entitiesBuilder;

    /** @var Urn $urn */

    public function __construct($campaign = null, $entitiesBuilder = null, $urn = null)
    {
        $this->campaign = $campaign ?: new Custom;
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->urn = $urn ?: new Urn;
    }

    /**
     * On Strike
     * @param Strike $strike 
     * @return void
     */
    public function onStrike(Strike $strike)
    {
        $entityUrn = $strike->getReport()->getEntityUrn();
        $entityGuid = $this->urn->setUrn($entityUrn)->getNss();

        $entity = $this->entitiesBuilder->single($entityGuid);
        $owner = $entity->type === 'user' ? $entity : $this->entitiesBuilder->single($entity->getOwnerGuid());

        $type = $entity->type;
        if ($type === 'object') {
            $type = $entity->subtype;
        }

        $action = 'removed';
        switch ($strike->getReasonCode()) {
            case 2:
                $action = 'marked as nsfw';
                break;
        }

        $this->campaign->setUser($owner);
        $this->campaign->setTemplate('moderation-strike.md');
        $this->campaign->setSubject('You have received a strike');
        $this->campaign->setVars([
            'type' => $type,
            'action' => $action,
            //'reason' => $reason,
        ]);

        $this->campaign->send();
    }

}
