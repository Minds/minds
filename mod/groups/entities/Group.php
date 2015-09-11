<?php
/**
 * Group entity
 */
 namespace Minds\plugin\groups\entities;

 class Group extends \ElggGroup{

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
