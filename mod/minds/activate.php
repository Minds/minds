<?php
/**
 * Run some schema changes on activate
 */

//for features
db_alter_column('object', array('featured'=>BooleanType));
