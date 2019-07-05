<?php
/**
 * AttachmentPaywallDelegate
 * @author edgebal
 */

namespace Minds\Core\Feeds\Activity\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Entities\Actions\Save;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\Activity;

class AttachmentPaywallDelegate implements ActivityDelegateInterface
{
    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /** @var Save */
    protected $save;

    /**
     * AttachmentPaywallDelegate constructor.
     * @param EntitiesBuilder $entitiesBuilder
     * @param Save $save
     */
    public function __construct(
        $entitiesBuilder = null,
        $save = null
    )
    {
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->save = $save ?: new Save();
    }

    /**
     * @throws \NotImplementedException
     */
    public function onAdd()
    {
        throw new \NotImplementedException();
    }

    /**
     * @param Activity $activity
     * @return bool
     */
    public function onUpdate(Activity $activity)
    {
        if ($activity->entity_guid) {
            $attachment = $this->entitiesBuilder->single($activity->entity_guid);

            if ($attachment->owner_guid == $activity->owner_guid) {
                $attachment->access_id = $activity->isPaywall() ? 0 : 2;
                $attachment->hidden = $activity->isPaywall();

                if (method_exists($attachment, 'setFlag')) {
                    $attachment->setFlag('paywall', (bool) $activity->isPaywall());
                }

                if (method_exists($attachment, 'setWireThreshold')) {
                    $attachment->setWireThreshold($activity->getWireThreshold() ?: false);
                }

                $this->save
                    ->setEntity($attachment)
                    ->save();
            }
        }

        return true;
    }
}
