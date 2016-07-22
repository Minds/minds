<?php
/**
 * Minds Thumbnail Proxy
 */
namespace Minds\Controllers;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;

class thumbProxy extends core\page implements Interfaces\page
{
    /**
     * Get requests
     */
    public function get($pages)
    {
        set_time_limit(1); //don't spend longer than 1 seconds
        ini_set('max_execution_time', 1);

        $src = urldecode($_GET['src']);
        //assume https if url not set
        if (strpos($src, 'http') === false) {
            $src = "https:$src";
        }

        if (strpos($src, 'blog/header/') !== false) {
            $src = str_replace('blog/header/', 'fs/v1/banners/', $src);
        }


        //get the original file
        $ch = curl_init($src);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Minds/2.0 (+http://www.minds.com/)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
        $image = curl_exec($ch);
        $errorno = curl_errno($ch);
        curl_close($ch);

        if ($errorno) {
            header('Content-type: image/jpeg');
            header('Access-Control-Allow-Origin: *');
            header('X-ERROR: $errorno');
            $img = imagecreatetruecolor(120, 1);
            $bg = imagecolorallocate($img, 255, 255, 255);
            imagefilledrectangle($img, 0, 0, 120, 1, $bg);
            imagejpeg($img, null, 100);
            die();
        }

        if (!$image) {
            return false;
        } else {
            $filename = '/tmp/'.time().rand();
            file_put_contents($filename, $image);
            $image = @imagecreatefromstring($image);
        }

        if (!$image) {
            @unlink($filename);
            $this->forward($src);
            return false;
        }

        header('Expires: ' . date('r', strtotime("today+6 months")), true);
        header("Pragma: public");
        header("Cache-Control: public");

        // Get new dimensions
        $width = imagesx($image);
        $height = imagesy($image);
        $new_width = get_input('width', 400);
        if ($width == 0 || $height == 0) {
            @unlink($filename);
            $this->forward($src);
            return;
        }

        $ratio = $width / $height;
        $new_height = $new_width / $ratio;

        if ($width <= 1 || $height <= 1) {
            $new_width = 1;
            $new_height = 1;
        }

        if (isset($_GET['height'])) {
            $new_height = get_input('height');
        }

        if (isset($_GET['width']) && $_GET['width'] == 'auto') {
            $new_width = $width;
            $new_height = $height;
        }

        // Resample
        $image_p = imagecreatetruecolor($new_width, $new_height);

        $mime = @getimagesize($filename);
        $mime = $mime['mime'];
        switch ($mime) {
            case 'image/gif':
                //$image = imagecreatefromgif($src);
                //WE WANT TO HAVE COOL GIFS!
                //	header('Content-type: image/gif');
                //	readfile($src);
                @unlink($filename);
                $this->forward($src);
                return;
                break;
            case 'image/png':
                $image = @imagecreatefrompng($filename);
                break;
            case 'image/bmp':
            case 'image/jpeg':
            default:
                $image = @imagecreatefromjpeg($filename);
        }
        if (!$image) {
            //we couldn't get the images, just output directly
            //header('Content-type: image/jpeg');
            @unlink($filename);
            $this->forward($src);
            return;
        }
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        header('Content-type: image/jpeg');
        header('Access-Control-Allow-Origin: *');

        imagejpeg($image_p, null, 75);
        @unlink($filename);

        exit;
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
