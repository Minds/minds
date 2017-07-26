<?php
namespace Minds\Core\Media\Assets;

interface AssetsInterface
{
    public function setEntity($entity);
    public function validate(array $media);
    public function upload(array $media, array $data);
    public function update(array $data);
}
