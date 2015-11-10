<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$entity = new minds\plugin\oauth2\Entities\client();
$entity->subtype    = 'oauth2_client';
$entity->owner_guid = 0;
$entity->access_id  = ACCESS_PRIVATE;

$entity->title       = "test";
$entity->description = "test oauth";

$entity->client_id     = $entity->guid;
$entity->client_secret = \minds\plugin\oauth2\start::generateSecret();

if (!$entity->save()) {
    echo "failed to set oauth keys... \n";
}

$db = new Minds\Core\Data\Call('plugin');
$db->insert('oauth2', array('type'=>'plugin', 'active'=>1, 'access_id'=>2));

echo "\n\n Your keys are: \n";
echo "client_id: $entity->guid \n";
echo "client_secret: $entity->client_secret \n";
