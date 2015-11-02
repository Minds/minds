<?php
/** 
 * This is a run once schema to populate a basic Minds-Elgg install
 * and setup column family names.
 */

require(dirname(__FILE__) . '/start.php');

create_cfs('token', array('owner_guid'=>'UTF8Type', 'expires' =>'IntegerType' ));
