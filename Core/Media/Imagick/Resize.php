<?php
namespace Minds\Core\Media\Imagick;

class Resize
{
    /** @var \Imagick $image */
    protected $image;

    /** @var int $width */
    protected $width;

    /** @var int $height */
    protected $height;

    /** @var array $offsets */
    protected $offsets = [
        'x1' => 0,
        'y1' => 0,
        'x2' => 0,
        'y2' => 0,
    ];

    /** @var bool $upscale */
    protected $upscale = false;

    /** @var bool $keepRatio */
    protected $keepRatio = true;

    /** @var bool $square */
    protected $square = false;

    /** @var \Imagick $output */
    protected $output;

    /**
     * @param \Imagick $image
     * @return Resize
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param int $width
     * @return Resize
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param int $height
     * @return Resize
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param array $offsets
     * @return $this
     */
    public function setOffsets($offsets)
    {
        $this->offsets = $offsets;
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
     * @param bool $value
     * @return $this
     */
    public function setKeepRatio($value)
    {
        $this->keepRatio = $value;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setSquare($value)
    {
        $this->square = $value;
        return $this;
    }

    /**
     * @return \Imagick
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

        if (!$this->width || !$this->height || $this->width < 16 || $this->height < 16) {
            throw new \Exception('Invalid size');
        }

        $params = $this->getResizeParameters();

        // First crop the image
        $this->image->cropImage($params['selectionwidth'], $params['selectionheight'], $params['xoffset'],
            $params['yoffset']);

        // If selected with / height differ from selection width/height, then we need to resize
        if ($params['selectionwidth'] !== $params['newwidth'] || $params['selectionheight'] !== $params['newheight']) {
            $this->image->thumbnailImage($params['newwidth'], $params['newheight']);
        }

        $this->output = $this->image;

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

        $this->output->setImageBackgroundColor('white');

        $this->output = $this->output->mergeImageLayers($this->image::LAYERMETHOD_FLATTEN);

        $this->output->setImageCompression($quality);
        $this->output->setImageFormat('jpg');

        return $this->output->getImageBlob();
    }

    /**
     * @return string
     */
    public function getPng()
    {
        $this->image->setImageFormat('png');

        return $this->output->getImageBlob();
    }

    protected function getResizeParameters()
    {
        extract($this->offsets);

        // Get the size information from the image
        $d = $this->image->getImageGeometry();
        $width = $d['width'];
        $height = $d['height'];

        // crop image first?
        $crop = true;
        if ($x1 == 0 && $y1 == 0 && $x2 == 0 && $y2 == 0) {
            $crop = false;
        }

        // how large a section of the image has been selected
        if ($crop) {
            $selection_width = $x2 - $x1;
            $selection_height = $y2 - $y1;
        } else {
            // everything selected if no crop parameters
            $selection_width = $width;
            $selection_height = $height;
        }

        // determine cropping offsets
        if ($this->square) {
            // asking for a square image back

            // detect case where someone is passing crop parameters that are not for a square
            if ($crop == true && $selection_width != $selection_height) {
                return false;
            }

            // size of the new square image
            $new_width = $new_height = min($this->width, $this->height);

            // find largest square that fits within the selected region
            $selection_width = $selection_height = min($selection_width, $selection_height);

            // set offsets for crop
            if ($crop) {
                $widthoffset = $x1;
                $heightoffset = $y1;
                $width = $x2 - $x1;
                $height = $width;
            } else {
                // place square region in the center
                $widthoffset = floor(($width - $selection_width) / 2);
                $heightoffset = floor(($height - $selection_height) / 2);
            }
        } else {
            // non-square new image
            $new_width = $this->width;
            $new_height = $this->height;

            // maintain aspect ratio of original image/crop
            if (($selection_height / (float) $new_height) > ($selection_width / (float) $new_width)) {
                $new_width = floor($new_height * $selection_width / (float) $selection_height);
            } else {
                $new_height = floor($new_width * $selection_height / (float) $selection_width);
            }

            // by default, use entire image
            $widthoffset = 0;
            $heightoffset = 0;

            if ($crop) {
                $widthoffset = $x1;
                $heightoffset = $y1;
            }
        }

        if (!$this->upscale && ($selection_height < $new_height || $selection_width < $new_width)) {
            // we cannot upscale and selected area is too small so we decrease size of returned image
            if ($this->square) {
                $new_height = $selection_height;
                $new_width = $selection_width;
            } else {
                if ($selection_height < $new_height && $selection_width < $new_width) {
                    $new_height = $selection_height;
                    $new_width = $selection_width;
                }
            }
        }

        $params = [
            'newwidth' => $new_width,
            'newheight' => $new_height,
            'selectionwidth' => $selection_width,
            'selectionheight' => $selection_height,
            'xoffset' => $widthoffset,
            'yoffset' => $heightoffset,
        ];

        return $params;
    }
}
