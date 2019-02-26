<?php

/**
 * Minds Proxy Image Resizer using Image Magick.
 *
 * @author brianhatchet
 */

namespace Minds\Core\Media\Proxy;

use Imagick;
use ImagickPixel;
use Minds\Traits\MagicAttributes;

class MagicResize
{
    use MagicAttributes;
    /** @var string $image */
    protected $image;

    /** @var int $size */
    protected $size;

    /** @var int $quality */
    protected $quality;

    /** @var bool $upscale */
    protected $upscale = false;

    /** @var resource $output */
    protected $output;

    /** @var string $imageFormat */
    protected $imageFormat;

    /** @var string $image */
    public function setImage($image)
    {
        if (!$image) {
            throw new \Exception('Missing image');
        }

        if (!$this->size || $this->size < 16) {
            throw new \Exception('Invalid size');
        }
        $this->image = $image;
        $this->output = new Imagick();
        $this->output->setBackgroundColor(new ImagickPixel('transparent'));
        $this->output->readImageBlob($this->image);

        return $this;
    }

    /**
     * Resizes an image to a custom size.
     *
     * @return Resize
     *
     * @throws \Exception
     */
    public function resize()
    {
        $width = $this->output->getImageWidth();
        $height = $this->output->getImageHeight();

        if (!$this->upscale && max($width, $height) < $this->size) {
            $this->output = $this->image;

            return $this;
        }

        $ratio = $width / $height;

        if ($ratio > 1) {
            $newWidth = $this->size;
            $newHeight = round($this->size / $ratio);
        } else {
            $newWidth = round($this->size * $ratio);
            $newHeight = $this->size;
        }

        $this->output->resizeImage($newWidth, $newHeight, Imagick::FILTER_CATROM, 1);

        return $this;
    }

    public function roundCorners($x, $y)
    {
        $this->output->roundCorners($x, $y);

        return $this;
    }

    public function getImage()
    {
        if (!$this->output) {
            throw new \Exception('Output was not generated');
        }
        error_log($this->imageType);
        $this->output->setImageFormat($this->imageFormat);

        return $this->output->getImage();
    }
}
