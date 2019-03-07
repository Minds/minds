<?php

namespace Minds\Core\Http\Curl;

use Minds\Traits\MagicAttributes;

class CurlWrapper
{
    use MagicAttributes;

    private $handle;

    public function __construct()
    {
        $this->handle = curl_init();
    }

    public function __destruct()
    {
        if ($this->handle) {
            curl_close($this->handle);
        }
    }

    public function setLimit($limitKb)
    {
        curl_setopt($this->handle, CURLOPT_BUFFERSIZE, 128);
        curl_setopt($this->handle, CURLOPT_NOPROGRESS, false);
        curl_setopt($this->handle, CURLOPT_PROGRESSFUNCTION, function (
            $downloadSize, $downloaded, $uploadSize, $uploaded
        ) use ($limitKb) {
            error_log($downloaded);
            if ($downloaded) {
                return ($downloaded > ($limitKb * 1000)) ? 1 : 0;
            } elseif ($uploaded) {
                return ($uploaded > ($limitKb * 1000)) ? 1 : 0;
            }

            return 0;
        });
    }

    public function getErrorNumber()
    {
        return curl_errno($this->handle);
    }

    public function getError()
    {
        return curl_error($this->handle);
    }

    public function execute()
    {
        return curl_exec($this->handle);
    }

    public function setOpt($option, $value)
    {
        curl_setopt($this->handle, $option, $value);
    }

    public function setOptArray($optionArray)
    {
        curl_setopt_array($this->handle, $optionArray);
    }
}
