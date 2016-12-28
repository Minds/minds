<?php
/**
 * Minds Banners FS endpoint
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\fs\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class paywall implements Interfaces\FS
{
    public function get($pages)
    {

        switch($pages[0]){
            case "preview":
              $channel = Entities\Factory::build($pages[1]);

              $f = new Entities\File();
              $f->owner_guid = $channel->guid;
              $f->setFilename("paywall-preview.jpg");
              $f->open('read');

              $contents = $f->read();

              if (empty($contents)) {
                  $contents = file_get_contents(Core\Di\Di::_()->get('Config')->get('path') . 'front/app/assets/default-paywall.png');
              }

              $finfo    = finfo_open(FILEINFO_MIME);
              $mimetype = finfo_file($finfo, $filepath);
              finfo_close($finfo);
              header('Content-Type: '.$mimetype);
              header('Expires: ' . date('r', time() + 864000));
              header("Pragma: public");
              header("Cache-Control: public");
              echo $contents;
              exit;

              break;
        }

    }
}
