<?php
namespace Minds\Core\Media\Assets;

use Minds\Core;
use Minds\Entities;

class Video implements AssetsInterface
{
    protected $entity;

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function validate(array $media)
    {
        $maxMins = 40;

        $length = exec("ffmpeg -i {$media['file']} 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");
        $timeSplit = explode(':', $length);

        $hours = $timeSplit[0];
        $mins = $timeSplit[1];

        $length = ((int) $hours * 60) + (int) $mins;

        if ($length >= $maxMins) {
            throw new \Exception("Sorry, the video is too long ({$length}m). It should be shorter than {$maxMins}m.");
        }

        return true;
    }

    public function upload(array $media, array $data)
    {
        return [
            'media' => $media
        ];
    }

    public function update(array $data = [])
    {
        $assets = [];

        if (isset($data['file'])) {
            $thumb = str_replace('data:image/jpeg;base64,', '', $data['file']);
            $thumb = str_replace(' ', '+', $thumb);
            $data = base64_decode($thumb);

            $filename = "archive/thumbnails/{$this->entity->guid}.jpg";

            $file = new Entities\File();
            $file->owner_guid = $this->entity->owner_guid;
            $file->setFilename($filename);
            $file->open('write');
            $file->write($data);
            $file->close();

            $assets['thumbnail'] = $filename;
        }

        return $assets;
    }
}
