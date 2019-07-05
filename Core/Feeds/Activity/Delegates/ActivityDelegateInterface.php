<?php
/**
 * ActivityDelegateInterface
 * @author edgebal
 */

namespace Minds\Core\Feeds\Activity\Delegates;

use Minds\Entities\Activity;

interface ActivityDelegateInterface
{
    public function onAdd();

    public function onUpdate(Activity $activity);
}
