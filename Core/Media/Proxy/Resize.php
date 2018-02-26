<?php

/**
 * Minds Proxy Image Resizer
 *
 * @author emi
 */

namespace Minds\Core\Media\Proxy;

class Resize
{
    /** @var resource $image */
    protected $image;

    /** @var int $size */
    protected $size;
    
    /** @var int $quality */
    protected $quality;

    /** @var bool $upscale */
    protected $upscale = false;

    /** @var resource $output */
    protected $output;

    /**
     * @param resource $image
     * @return Resize
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param int $size
     * @return Resize
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @param int $quality
     * @return Resize
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
        return $this;
    }

    /**
     * @param bool $upscale
     * @return Resize
     */
    public function setUpscale($upscale)
    {
        $this->upscale = $upscale;
        return $this;
    }

    /**
     * @return resource
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Resizes an image to a custom size.
     * @return Resize
     * @throws \Exception
     */
    public function resize()
    {
        if (!$this->image) {
            throw new \Exception('Missing image');
        }

        if (!$this->size || $this->size < 16) {
            throw new \Exception('Invalid size');
        }

        $width = imagesx($this->image);
        $height = imagesy($this->image);

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

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled(
            $resized,
            $this->image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        $this->output = $resized;

        return $this;
    }

    /**
     * @param int $quality
     * @return string
     * @throws \Exception
     */
    public function getJpeg($quality = 80)
    {
        if (!$this->output) {
            throw new \Exception('Output was not generated');
        }

        ob_start();
        imagejpeg($this->output, null, $quality);

        return ob_get_clean();
    }
}
