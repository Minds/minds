<?php

namespace Minds\Core\Media\Imagick;


use Minds\Core\Di\Di;
use Minds\Core\Media\Imagick\Autorotate;
use Minds\Core\Media\Imagick\Resize;

class Manager
{
    /** @var Autorotate */
    private $autorotate;

    /** @var Resize */
    private $resize;

    /** @var \Imagick */
    private $image;

    public function __construct($autorotate = null, $resize = null)
    {
        $this->autorotate = $autorotate ?: Di::_()->get('Media\Imagick\Autorotate');
        $this->resize = $resize ?: Di::_()->get('Media\Imagick\Resize');
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param int $quality
     * @return string
     * @throws \Exception
     */
    public function getJpeg($quality = 80)
    {
        if (!$this->image) {
            throw new \Exception('Output was not generated');
        }

        $this->image->setImageBackgroundColor('white');

        $this->image = $this->image->mergeImageLayers($this->image::LAYERMETHOD_FLATTEN);

        $this->image->setImageCompression($quality);
        $this->image->setImageFormat('jpg');

        return $this->image->getImageBlob();
    }

    /**
     * @param $value
     * @return $this
     * @throws \ImagickException
     */
    public function setImage($value)
    {
        $this->image = new \Imagick($value);

        return $this;
    }

    /**
     * @return $this
     */
    public function autorotate()
    {
        $this->autorotate
            ->setImage($this->image);

        $this->image = $this->autorotate->autorotate();

        return $this;
    }

    /**
     * @param bool $upscale
     * @param bool $square
     * @param int $width
     * @param int $height
     * @return $this
     * @throws \Exception
     */
    public function resize(int $width, int $height, bool $upscale = false, bool $square = false)
    {
        $this->resize->setImage($this->image)
            ->setUpscale($upscale)
            ->setSquare($square)
            ->setWidth($width)
            ->setHeight($height)
            ->resize();

        return $this;
    }
}
