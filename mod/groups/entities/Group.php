<?php
/**
 * Group entity
 */
 namespace Minds\plugin\groups\entities;

 use Minds\plugin\groups\helpers;

 class Group extends \ElggGroup{

   public function join($user = NULL){
     return helpers\Membership::join($this, $user);
   }

   public function leave($user = NULL){
     return helpers\Membership::leave($this, $user);
   }

   public function getExportableValues() {
     return array_merge(parent::getExportableValues(), array(
       'name',
       'description',
       'icontime',
       'banner',
       'membership'
     ));
   }

   public function export(){
     $export = parent::export();
     $export['member'] = $this->isMember();
     $export['members:count'] = 0;
     return $export;
   }

 }
