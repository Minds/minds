<?php

/**
 * Minds Blogs Create Activity Delegate
 *
 * @author emi
 */

namespace Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;
use Minds\Core\Data\Call;
use Minds\Core\Entities\Actions\Save;
use Minds\Entities\Activity;

class CreateActivity
{
    /** @var Save */
    protected $saveAction;

    /** @var Call */
    protected $db;

    /**
     * CreateActivity constructor.
     * @param null $saveAction
     */
    public function __construct($saveAction = null, Call $db=null)
    {
        $this->saveAction = $saveAction ?: new Save();
        $this->db = $db ?? new Call('entities_by_time');
    }

    /**
     * Creates a new activity for a blog
     * @param Blog $blog
     * @throws \Minds\Exceptions\StopEventException
     * @return bool
     */
    public function save(Blog $blog) : bool
    {
        $activities = $this->db->getRow("activity:entitylink:{$blog->getGuid()}");
        if (!empty($activities)) {
            return false;
        }

        $owner = $blog->getOwnerEntity();

        $activity = (new Activity())
            ->setTitle($blog->getTitle())
            ->setBlurb(strip_tags($blog->getBody()))
            ->setURL($blog->getURL())
            ->setThumbnail($blog->getIconUrl())
            ->setFromEntity($blog)
            ->setMature($blog->isMature())
            ->setOwner($owner->export())
            ->setWireThreshold($blog->getWireThreshold())
            ->setPaywall($blog->isPaywall());

        $activity->container_guid = $owner->guid;
        $activity->owner_guid = $owner->guid;
        $activity->ownerObj = $owner->export();

        $this->saveAction
            ->setEntity($activity)
            ->save();

        return true;
    }
}
