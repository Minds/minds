<?php
/**
 * Email Notification delegate for Verdicts 
 */
namespace Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Reports\Report;
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
     * On Action
     * @param Report $report
     * @return void
     */
    public function onBan(Report $report)
    {
        $entityUrn = $report->getEntityUrn();
        $entityGuid = $this->urn->setUrn($entityUrn)->getNss();

        $entity = $this->entitiesBuilder->single($entityGuid);
        $owner = $entity->type === 'user' ? $entity : $this->entitiesBuilder->single($entity->getOwnerGuid());

        $type = $entity->type;
        if ($type === 'object') {
            $type = $entity->subtype;
        }

        $template = 'moderation-banned.md';

        $action = 'removed';
        switch ($report->getReasonCode()) {
            case 2:
                return;
                break;
            case 4:
            case 8:
                $template = 'moderation-3-strikes.md';
                break;
        }

        $this->campaign->setUser($owner);
        $this->campaign->setTemplate($template);
        $this->campaign->setSubject('You have been banned');
        $this->campaign->setVars([
            'type' => $type,
            'action' => $action,
            //'reason' => $reason,
        ]);

        $this->campaign->send();
    }

}
