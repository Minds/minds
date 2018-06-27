<?php
namespace Minds\Core\Media\Assets;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;

class Image implements AssetsInterface
{
    protected $entity;

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function validate(array $media)
    {
    }

    public function upload(array $media, array $data)
    {
        $filename = "/image/{$this->entity->batch_guid}/{$this->entity->guid}/master.jpg";

        // @note: legacy file handling
        $file = new \ElggFile();
        $file->setFilename($filename);
        $file->open('write');
        $file->write(file_get_contents($media['file']));
        $file->close();

        list($width, $height) = getimagesize($media['file']);

        if (exif_imagetype($media['file'])) {
            $exif = @exif_read_data($media['file']);

            if ($exif && isset($exif['Orientation'])) {
                // check and invert
                if (in_array($exif['Orientation'], [5, 6, 7, 8])) {
                    list($height, $width) = [$width, $height];
                }
            }
        }

        return [
            'filename' => $filename,
            'media' => $media,
            'width' => $width,
            'height' => $height,
        ];
    }

    public function update(array $data = [])
    {
        $assets = [];

        $album = null;
        $container_guid = isset($data['container_guid']) && $data['container_guid'] ? $data['container_guid'] : null;

        if (isset($data['album_guid'])) {
            $album = new Entities\Album($data['album_guid']);

            if (!$album->guid) {
                throw new \Exception('Sorry, the album was not found');
            }

            $mediaAlbums->addChildren($album, [ $this->entity->guid => time() ]);
            $assets['container_guid'] = $album->guid;
        } elseif (!$container_guid) {
            $mediaAlbums = Di::_()->get('Media\Albums');

            $albums = $mediaAlbums->getAll($owner_guid, [
                'createDefault' => true
            ]);

            $mediaAlbums->addChildren($albums[0], [ $this->entity->guid => time() ]);
            $assets['container_guid'] = $album->guid;
        } else {
            $assets['container_guid'] = $container_guid;
        }

        return $assets;
    }
}
