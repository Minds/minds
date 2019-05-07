<?php
/**
 * Action delegate for Verdicts
 */
namespace Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Security\ACL;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Di\Di;
use Minds\Common\Urn;

class ActionDelegate
{
    /** @var EntitiesBuilder $entitiesBuilder */
    private $entitiesBuilder;

    /** @var Actions $actions */
    private $actions;

    /** @var Urn $urn */
    private $urn;

    public function __construct(
        $entitiesBuilder = null,
        $actions = null,
        $urn = null
    )
    {
        $this->entitiesBuilder = $entitiesBuilder  ?: Di::_()->get('EntitiesBuilder');
        $this->actions = $actions ?: Di::_()->get('Reports\Actions');
        $this->urn = $urn ?: new Urn;
    }

    public function onAction(Verdict $verdict)
    {
        $report = $verdict->getReport();

        // Disable ACL 
        ACL::$ignore = true;
        $entityUrn = $verdict->getReport()->getEntityUrn();
        $entityGuid = $this->urn->setUrn($entityUrn)->getNss();

        $entity = $this->entitiesBuilder->single($entityGuid);

        switch ($report->getReasonCode()) {
            case 1: // Illegal (not appealable)
                $this->actions->setDeletedFlag($entity, true);
                // TODO: ban the owner of the post too
                break;
            case 2: // NSFW
                $nsfw = $report->getSubReasonCode();
                $entity->setNsfw(array_merge([$nsfw], $entity->getNsfw()));
                $entity->save();
                // Apply a strike to the owner
                break;
            case 3: // Incites violence
                $this->actions->setDeletedFlag($entity, true);
                // Apply a strike to the owner
                break;
            case 4:  // Threatens, harasses or bullies
                $this->actions->setDeletedFlag($entity, true);
                // Apply a strike to the owner
                break;
            case 5: // Personal and confidential information (not appelable)
                $this->actions->setDeletedFlag($entity, true);
                // Ban the owner of the post too
                break;
            case 7: // Impersonates
                // Ban
                break;
            case 8: // Spam
                $this->actions->setDeletedFlag($entity, true);
                // Apply a strike to the owner
                break;
            case 12: // Incorrect use of hashtags
                // De-index post
                // Apply a strike to the owner
                break;
            case 13: // Malware
                $this->actions->setDeletedFlag($entity, true);
                // Ban the owner
                break;
            case 14: // Strikes
                // Ban the user
                break;
            case 16: // Token manipulation
                // Ban
                break;
        }

        // Enable ACL again
        ACL::$ignore = false;
    }

}