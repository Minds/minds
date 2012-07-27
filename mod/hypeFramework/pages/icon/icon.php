<?php
$entity_guid = get_input('guid');
$entity = get_entity($entity_guid);

$size = strtolower(get_input('size'));
if (!in_array($size, array('large', 'medium', 'small', 'tiny', 'master', 'topbar')))
    $size = "medium";

$success = false;

$filehandler = new ElggFile();
$filehandler->owner_guid = $entity->owner_guid;
$filehandler->setFilename("icons/" . $entity->guid . $size . ".jpg");

$success = false;
if ($filehandler->open("read")) {
    if ($contents = $filehandler->read($filehandler->size())) {
        $success = true;
    }
}

header("Content-type: image/jpeg");
header('Expires: ' . date('r', time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));
echo $contents;
