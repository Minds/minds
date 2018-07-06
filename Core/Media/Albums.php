<?php
namespace Minds\Core\Media;

use Minds\Core;
use Minds\Entities;

class Albums
{
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    public function getAll($ownerGuid, array $opts = [])
    {
        $opts = array_merge([
            'createDefault' => false
        ], $opts);

        $entities = Core\Entities::get([
            'subtype' => 'album',
            'owner_guid' => $ownerGuid
        ]);

        if (!$entities && $opts['createDefault']) {
            $album = new Entities\Album();
            $album->title = "My Album";
            $album->owner_guid = $ownerGuid;
            $album->save();

            $entities = [ $album ];
        }

        return $entities;
    }

    public function getChildren($guid, array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => ''
        ], $opts);

        $guids = $this->db->getRow("object:container:$guid", [
            'limit' => $opts['limit'],
            'offset' => $opts['offset']
        ]);

        if ($opts['offset']) {
            unset($guids[$opts['offset']]);
        }

        if (!$guids) {
            return [];
        }

        $entities = Core\Entities::get([
            'guids' => array_keys($guids)
        ]);

        return $entities;
    }

    public function create(array $data = [])
    {
        $album = new Entities\Album();
        $album->title = $data['title'];
        $album->save();

        return $album;
    }

    public function addChildren($guid, array $guids = [])
    {
        $album = $guid instanceof Entities\Album ? $guid : new Entities\Album($guid);

        if (!$album->guid || !$album->canEdit()) {
            return false;
        }

        $album->addChildren($guids);

        return true;
    }

    public function delete($guid)
    {
        $album = $guid instanceof Entities\Album ? $guid : new Entities\Album($guid);

        if (!$album->guid || !$album->canEdit()) {
            return false;
        }

        $album->delete();
        return true;
    }
}
