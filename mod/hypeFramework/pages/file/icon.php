<?php

require_once (dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/engine/start.php');

$file_guid = (int) get_input('guid', 0);
$size = get_input('size', 'small');
$file = get_entity($file_guid);

if (!$file || $file->getSubtype() != "hjfile") {
    exit;
}

// Get file thumbnail
switch ($size) {
    case "tiny" :
        $thumbfile = $file->tinythumb;
        break;
    case "small" :
        $thumbfile = $file->smallthumb;
        break;
    case "medium":
    default :
        $thumbfile = $file->mediumthumb;
        break;
    case "large":
        $thumbfile = $file->largethumb;
        break;
    case "master":
        $thumbfile = $file->masterthumb;
        break;        
    case "preview":
        $thumbfile = $file->previewthumb;
        break;
    case "full":
        $thumbfile = $file->fullthumb;
        break;
}
$filehandler = new ElggFile();
$filehandler->owner_guid = $file->owner_guid;
$filehandler->setFilename($thumbfile);
$contents = $filehandler->grabFile();

header("Content-type: image/jpeg");
header('Expires: ' . date('r', time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));

echo $contents;