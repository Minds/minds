<?php
/**
 * Comments entity
 */

namespace minds\plugin\comments\entities;

use Minds\Entities;
use Minds\Core;
use Minds\Core\Security;

class comment extends Entities\Entity{

    private $parent;

    public function initializeAttributes(){
        parent::initializeAttributes();
        $this->attributes = array_merge($this->attributes, array(
            'type' => 'comment',
            'owner_guid'=>elgg_get_logged_in_user_guid(),
            'access_id' => 2
        ));
    }

    public function setParent($parent){
      $this->parent = $parent;
      $this->parent_guid = $parent->guid;
      return $this;
    }

    public function save(){

        //check to see if we can interact with the parent
        if(!Security\ACL::interact($this->parent))
          return false;

        parent::save(false);
        $indexes = new \Minds\Core\Data\indexes('comments');
        $indexes->set($this->parent_guid, array($this->guid=>$this->guid));

        $cacher = Core\Data\cache\factory::build();
        $cacher->destroy("comments:count:$this->parent_guid");

        return $this->guid;
    }

    public function delete(){
        $db = new \Minds\Core\Data\Call('entities');
        $db->removeRow($this->guid);

        $indexes = new \Minds\Core\Data\indexes('comments');
        $indexes->remove($this->parent_guid, array($this->guid));

    $cacher = Core\Data\cache\factory::build();
    $cacher->destroy("comments:count:$this->parent_guid");

        return true;
    }

    public function view(){
        echo \elgg_view('comment/default', array('entity'=>$this));
    }

    public function getURL(){

        $entity = \Minds\Core\Entities::build(new Entities\Entity($this->parent_guid));
        if($entity)
            return $entity->getURL();

    }

    public function getExportableValues() {
        return array_merge(parent::getExportableValues(), array(
            'description',
            'ownerObj',
            'parent_guid'
        ));
    }

}
