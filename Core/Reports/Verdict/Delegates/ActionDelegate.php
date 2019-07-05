<?php
/**
 * Action delegate for Verdicts
 */
namespace Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Security\ACL;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Di\Di;
use Minds\Common\Urn;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Strikes\Strike;
use Minds\Core\Entities\Actions\Save as SaveAction;

class ActionDelegate
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

    /** @var EmailDelegate $emailDelegate */
    private $emailDelegate;

    /** @var Core\Channels\Ban $channelsBanManager */
    private $channelsBanManager;

    public function __construct(
        $entitiesBuilder = null,
        $actions = null,
        $urn = null,
        $strikesManager = null,
        $saveAction = null,
        $emailDelegate = null,
        $channelsBanManager = null
    )
    {
        $this->entitiesBuilder = $entitiesBuilder  ?: Di::_()->get('EntitiesBuilder');
        $this->actions = $actions ?: Di::_()->get('Reports\Actions');
        $this->urn = $urn ?: new Urn;
        $this->strikesManager = $strikesManager ?: Di::_()->get('Moderation\Strikes\Manager');
        $this->saveAction = $saveAction ?: new SaveAction;
        $this->emailDelegate = $emailDelegate ?: new EmailDelegate;
        $this->channelsBanManager = $channelsBanManager ?: Di::_()->get('Channels\Ban');
    }

    public function onAction(Verdict $verdict)
    {
        if ($verdict->isAppeal() || !$verdict->isUpheld()) {
            error_log('Not upheld so no action');
            return; // Can not 
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
                    $this->actions->setDeletedFlag($entity, true);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Ban the owner of the post too
                $this->applyBan($report);
                break;
            case 2: // NSFW
                $nsfw = $report->getSubReasonCode();
                $entity->setNsfw(array_merge([$nsfw], $entity->getNsfw()));
                $entity->setNsfwLock(array_merge([$nsfw], $entity->getNsfwLock()));
                $this->saveAction->setEntity($entity)->save();
                // Apply a strike to the owner
                $this->applyStrike($report);
                break;
            case 3: // Incites violence
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, true);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Ban the owner of the post
                $this->applyBan($report);
                break;
            case 4:  // Harrasment
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, true);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Apply a strike to the owner
                $this->applyStrike($report);
                break;
            case 5: // Personal and confidential information (not appelable)
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, true);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Ban the owner of the post too
                $this->applyBan($report);
                break;
            case 7: // Impersonates (channel level)
                // Ban
                $this->applyBan($report);
                break;
            case 8: // Spam
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, true);
                    $this->saveAction->setEntity($entity)->save();

                    // Apply a strike to the owner
                    $this->applyStrike($report);
                } else {
                    // Apply a strike to the owner
                    $this->applyBan($report);
                }
                break;
            //case 12: // Incorrect use of hashtags
                // De-index post
                // Apply a strike to the owner
            //    break;
            case 13: // Malware
                if ($entity->type !== 'user') {
                    $this->actions->setDeletedFlag($entity, true);
                    $this->saveAction->setEntity($entity)->save();
                }
                // Ban the owner
                $this->applyBan($report);
                break;
            case 14: // Strikes
                // Ban the user or make action

                switch ($report->getSubReason()) {
                    case 4: // Harrasment
                    case 8: // Spam
                    case 16: // Token manipulation
                        $this->applyBan($report);
                        break;
                    case 2.1: // NSFW
                    case 2.2:
                    case 2.3:
                    case 2.4:
                    case 2.5:
                    case 2.6:
                        $this->applyNsfwLock($report);
                        break;
                }

                break;
            case 16: // Token manipulation
                // Strike
                $this->applyBan($report);
                break;
        }

        // Enable ACL again
        ACL::$ignore = false;
    }

    /**
     * Apply a strike to the user
     * @param Report $report
     * @return void
     */
    private function applyStrike(Report $report)
    {
        $strike = new Strike;
        $strike->setReport($report)
            ->setReportUrn($report->getUrn())
            ->setUserGuid($report->getEntityOwnerGuid())
            ->setReasonCode($report->getReasonCode())
            ->setSubReasonCode($report->getSubReasonCode())
            ->setTimestamp($report->getTimestamp()); // Strike is recored for date of first report

        $count = $this->strikesManager->countStrikesInTimeWindow($strike, $this->strikesManager::STRIKE_TIME_WINDOW);

        if (!$count) {
            $this->strikesManager->add($strike);
        }

        // If 3 or more strikes, ban or apply NSFW lock
        if ($this->strikesManager->countStrikesInTimeWindow($strike, $this->strikesManager::STRIKE_RETENTION_WINDOW) >= 3) {
            if ($report->getReasonCode() === 2) {
                $this->applyNsfwLock($report);
            } else {
                $reasonCode = $report->getReasonCode();
                $subReasonCode = $report->getSubReasonCode();
                $report->setReasonCode(14) // Strike
                    ->setSubReasonCode(implode('.', [ $reasonCode, $subReasonCode ]));
                $this->applyBan($report);
            }
        }
    }


    /**
     * Apply an NSFW lock to the user
     * @param Report $report
     */
    private function applyNsfwLock($report)
    {
        $user = $this->entitiesBuilder->single($report->getEntityOwnerGuid());

        //list($reason, $subReason) = explode('.', $report->getSubReason());
        $subReason = $report->getSubReasonCode();

        $user->setNsfw(array_merge($user->getNsfw(), [ $subReason ]));
        $user->setNsfwLock(array_merge($user->getNsfwLock(), [ $subReason ]));
        $user->save();
    }

    /**
     * Apply a ban to the channel
     * @param Report $report
     */
    private function applyBan($report)
    {
        $user = $this->entitiesBuilder->single($report->getEntityOwnerGuid());

        $this->channelsBanManager
            ->setUser($user)
            ->ban(implode('.', [ $report->getReasonCode(), $report->getSubReasonCode() ]));

        $this->emailDelegate->onBan($report);
    }

}
