<?php
/**
 * Reverse Action delegate for Verdicts
 */
namespace Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Security\ACL;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Di\Di;
use Minds\Common\Urn;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Strikes\Strike;
use Minds\Core\Entities\Actions\Save as SaveAction;

class ReverseActionDelegate
{
    /** @var EntitiesBuilder $entitiesBuilder */
    private $entitiesBuilder;

    /** @var Actions $actions */
    private $actions;

    /** @var SaveAction $saveAction */
    private $saveAction;

    /** @var Urn $urn */
    private $urn;

    /** @var StrikesManager $strikesManager */
    private $strikesManager;

    /** @var Core\Channels\Ban $channelsBanManager */
    private $channelsBanManager;

    public function __construct(
        $entitiesBuilder = null,
        $actions = null,
        $urn = null,
        $strikesManager = null,
        $saveAction = null,
        $channelsBanManager = null
    )
    {
        $this->entitiesBuilder = $entitiesBuilder  ?: Di::_()->get('EntitiesBuilder');
        $this->actions = $actions ?: Di::_()->get('Reports\Actions');
        $this->urn = $urn ?: new Urn;
        $this->strikesManager = $strikesManager ?: Di::_()->get('Moderation\Strikes\Manager');
        $this->saveAction = $saveAction ?: new SaveAction();
        $this->channelsBanManager = $channelsBanManager ?: Di::_()->get('Channels\Ban');
    }

    public function onReverse(Verdict $verdict)
    {
        if (!$verdict->isAppeal() || $verdict->isUpheld()) {
            return; // Can not be reversed
        }

        $report = $verdict->getReport();

        // Disable ACL 
        ACL::$ignore = true;
        $entityUrn = $verdict->getReport()->getEntityUrn();
        $entityGuid = $this->urn->setUrn($entityUrn)->getNss();

        $entity = $this->entitiesBuilder->single($entityGuid);

        switch ($report->getReasonCode()) {
            case 1: // Illegal (not appealable)
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, false);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Ban the owner of the post too
                $this->unBan($report);
                break;
            case 2: // NSFW
                $nsfw = $report->getSubReasonCode();
                $entity->setNsfw(array_diff([$nsfw], $entity->getNsfw()));
                $entity->setNsfwLock(array_diff([$nsfw], $entity->getNsfwLock()));
                $this->saveAction->setEntity($entity)->save();
                // Apply a strike to the owner
                $this->removeStrike($report);
                break;
            case 3: // Incites violence
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, false);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Ban the owner of the post
                $this->unBan($report);
                break;
            case 4:  // Harrasment
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, false);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Apply a strike to the owner
                $this->removeStrike($report);
                break;
            case 5: // Personal and confidential information (not appelable)
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, false);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Ban the owner of the post too
                $this->unBan($report);
                break;
            case 7: // Impersonates (channel level)
                // Ban
                $this->unBan($report);
                break;
            case 8: // Spam
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, false);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Apply a strike to the owner
                $this->removeStrike($report);
                break;
            //case 12: // Incorrect use of hashtags
                // De-index post
                // Apply a strike to the owner
            //    break;
            case 13: // Malware
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, false);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Ban the owner
                $this->unBan($report);
                break;
            case 14: // Strikes
                break;
            case 16: // Token manipulation
                // Strike
                $this->removeBan($report);
                break;
        }

        // Enable ACL again
        ACL::$ignore = false;
    }

    /**
     * Remove strike from user
     * @param Report $report
     * @return void
     */
    private function removeStrike(Report $report)
    {
        $strike = new Strike;
        $strike->setReport($report)
            ->setReportUrn($report->getUrn())
            ->setUserGuid($report->getEntityOwnerGuid())
            ->setReasonCode($report->getReasonCode())
            ->setSubReasonCode($report->getSubReasonCode())
            ->setTimestamp($report->getTimestamp()); // Strike is recored for date of first report

        $this->strikesManager->delete($strike);

        // Remove any bans or nsfw locks
        if ($report->getReasonCode() === 2) {
            $this->removeNsfwLock($report);
        } else {
            $this->unBan($report);
        }
    }


    /**
     * Remove an NSFW lock to the user
     * @param Report $report
     */
    private function removeNsfwLock($report)
    {
        $user = $this->entitiesBuilder->single($report->getEntityOwnerGuid());

        //list($reason, $subReason) = explode('.', $report->getSubReason());
        $subReason = $report->getSubReasonCode();

        $user->setNsfw(array_diff($user->getNsfw(), [ $subReason ]));
        $user->setNsfwLock(array_diff($user->getNsfwLock(), [ $subReason ]));
        $user->save();
    }

    /**
     * un ban to the channel
     * @param Report $report
     */
    private function unBan($report)
    {
        $user = $this->entitiesBuilder->single($report->getEntityOwnerGuid());
       
        $this->channelsBanManager
            ->setUser($user)
            ->unBan();
    }

}
