<?php

namespace Minds\Core\Media\Imagick;

class Autorotate
{
    /** @var \Imagick */
    protected $image;

    /**
     * @param \Imagick $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return \Imagick
     */
    public function autorotate()
    {
        switch ($this->image->getImageOrientation()) {
            case \Imagick::ORIENTATION_TOPLEFT:
                break;
            case \Imagick::ORIENTATION_TOPRIGHT:
                $this->image->flopImage();
                break;
            case \Imagick::ORIENTATION_BOTTOMRIGHT:
                $this->image->rotateImage("#000", 180);
                break;
            case \Imagick::ORIENTATION_BOTTOMLEFT:
                $this->image->flopImage();
                $this->image->rotateImage("#000", 180);
                break;
            case \Imagick::ORIENTATION_LEFTTOP:
                $this->image->flopImage();
                $this->image->rotateImage("#000", -90);
                break;
            case \Imagick::ORIENTATION_RIGHTTOP:
                $this->image->rotateImage("#000", 90);
                break;
            case \Imagick::ORIENTATION_RIGHTBOTTOM:
                $this->image->flopImage();
                $this->image->rotateImage("#000", 90);
                break;
            case \Imagick::ORIENTATION_LEFTBOTTOM:
                $this->image->rotateImage("#000", -90);
                break;
            default: // Invalid orientation
                break;
        }
        $this->image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);

        return $this->image;
    }
}
