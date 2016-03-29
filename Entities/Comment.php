<?php
/**
 * Comments entity
 */

namespace Minds\Entities;

use Minds\Entities;
use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Security;
use Minds\Helpers;

class Comment extends Entities\Entity
{
    private $parent;

    public function initializeAttributes()
    {
        parent::initializeAttributes();
        $this->attributes = array_merge($this->attributes, array(
            'type' => 'comment',
            'owner_guid'=>elgg_get_logged_in_user_guid(),
            'access_id' => 2,
            'mature' => false,
        ));
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        $this->parent_guid = $parent->guid;
        return $this;
    }

    public function setCustom($type, $data = array())
    {
        $this->custom_type = $type;
        $this->custom_data = $data;
        return $this;
    }

    public function setAttachmentGuid($guid)
    {
        $this->attachment_guid = $guid;
        return $this;
    }

    /**
     * Sets the maturity flag for this comment
     * @param mixed $value
     */
    public function setMature($value)
    {
        $this->mature = (bool) $value;
        return $this;
    }

    /**
     * Gets the maturity flag
     * @return boolean
     */
    public function getMature()
    {
        return (bool) $this->mature;
    }

    /**
     * Sets the title
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Sets the blurb
     * @param string $blurb
     * @return $this
     */
    public function setBlurb($blurb)
    {
        $this->blurb = $blurb;
        return $this;
    }

    /**
     * Sets the url
     * @param string $url
     * @return $this
     */
    public function setURL($url)
    {
        $this->perma_url = $url;
        return $this;
    }

    /**
     * Sets the thumbnail
     * @param string $src
     * @return $this
     */
    public function setThumbnail($src)
    {
        $this->thumbnail_src = $src;
        return $this;
    }

    public function save()
    {

        //check to see if we can interact with the parent
        if (!Security\ACL::_()->interact($this->parent)) {
            return false;
        }

        parent::save(false);
        $indexes = new Data\indexes('comments');
        $indexes->set($this->parent_guid, array($this->guid=>$this->guid));

        $cacher = Core\Data\cache\factory::build();
        $cacher->destroy("comments:count:$this->parent_guid");

        return $this->guid;
    }

    public function delete()
    {
        $db = new Data\Call('entities');
        $db->removeRow($this->guid);

        $indexes = new Data\indexes('comments');
        $indexes->remove($this->parent_guid, array($this->guid));

        $cacher = Core\Data\cache\factory::build();
        $cacher->destroy("comments:count:$this->parent_guid");

        return true;
    }

    public function canEdit()
    {
        $entity = \Minds\Entities\Factory::build($this->parent_guid);
        if ($entity->canEdit()) {
            return true;
        }
        return parent::canEdit();
    }

    public function view()
    {
        echo \elgg_view('comment/default', array('entity'=>$this));
    }

    public function getURL()
    {
        $entity = Entities::build(new Entities\Entity($this->parent_guid));
        if ($entity) {
            return $entity->getURL();
        }
    }

    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), array(
            'description',
            'title',
            'blurb',
            'perma_url',
            'thumbnail_src',
            'attachment_guid',
            'ownerObj',
            'parent_guid',
            'custom_type',
            'custom_data',
            'thumbs:up:count',
            'thumbs:up:user_guids',
            'thumbs:down:count',
            'thumbs:down:user_guids',
            'mature',
        ));
    }

    public function export()
    {
        $export = parent::export();

        $export['thumbs:up:count'] = Helpers\Counters::get($this, 'thumbs:up');
        $export['thumbs:down:count'] = Helpers\Counters::get($this, 'thumbs:down');

        $export['thumbs:up:user_guids'] = (array) array_values($export['thumbs:up:user_guids']);
        $export['thumbs:down:user_guids'] = (array) array_values($export['thumbs:down:user_guids']);

        $export['mature'] = (bool) $export['mature'];

        if ($this->custom_type == 'video' && $this->custom_data['guid']) {
          $export['play:count'] = Helpers\Counters::get($this->custom_data['guid'],'plays');
        }

        $export = array_merge($export, \Minds\Core\Events\Dispatcher::trigger('export:extender', 'activity', array('entity'=>$this), array()));

        if ($export['owner_guid'] && !$export['ownerObj']) {
          $export['ownerObj'] = Entities\Factory::build($export['owner_guid'])->export();
        }

        return $export;
    }
}
