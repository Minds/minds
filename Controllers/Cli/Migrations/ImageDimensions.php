<?php


namespace Minds\Controllers\Cli\Migrations;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Entities\Activity;
use Minds\Entities\Image;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Core\Data\Cassandra\Prepared;

use Cassandra;


class ImageDimensions extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
        $minds = new Core\Minds();
        $minds->start();
    }

    public function help($command = null)
    {
        $this->out('Syntax usage: cli migrations boosts [network|peer]');
    }

    public function exec()
    {
        $this->out('Syntax usage: cli migrations boosts [network|peer]');
    }

    public function single()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $guid = $this->getOpt('guid');
        $this->update($guid); 
    }

    private function update($guid)
    {
         Core\Security\ACL::$ignore = true;
        $thumbs = Di::_()->get('Media\Thumbnails');

        $entity = new Activity($guid);
        if (!$entity->entity_guid) {
            return false;
        }

        $thumbnail = Di::_()->get('Media\Thumbnails')->get($entity->entity_guid, 'master');
        if (!$thumbnail || is_string($thumbnail)) {
            return false;
        }

        $thumbnail->open('read');

        $data = $thumbnail->read();
        $image = imagecreatefromstring($data);

        $width = imagesx($image);
        $height = imagesy($image);

        if ($entity->custom_data) {
            $custom = $entity->custom_data;
            $custom[0]['width'] = $width;
            $custom[0]['height'] = $height;

            $entity->custom_data = $custom;
            $entity->save();
            $this->out("$entity->guid: Saved with w:$width h:$height");
        }    
    }

    public function activities()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $db = new Core\Data\Cassandra\Client();
        $query = new Core\Data\Cassandra\Prepared\Custom();

        $token = '';

        while (true) {
            $query->query("SELECT * FROM entities_by_time WHERE key='activity' ORDER BY column1 DESC", [
                
            ]);
            $query->setOpts([
                'page_size' => 50,
                'paging_state_token' => $token
            ]);
            $result = $db->request($query);
            if (!$result) {
                break;
            }

            $token = $result->pagingStateToken();

            foreach ($result as $row) {
                $this->update($row['column1']);
            }
        }

    }
}
