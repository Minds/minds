<?php
/**
 * Group entity
 */
 namespace Minds\plugin\groups\entities;

 use Minds\plugin\groups\helpers;

 class Group extends \ElggEntity{

   public function initializeAttributes(){
       parent::initializeAttributes();
       $this->attributes = array_merge($this->attributes, array(
           'type' => 'group'
       ));
   }

   public function isMember($user = NULL){
     return helpers\Membership::isMember($this, $user);
   }

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
       'banner_position',
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
