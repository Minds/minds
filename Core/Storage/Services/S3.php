<?php

namespace Minds\Core\Storage\Services;

use Aws\S3\S3Client;
use Minds\Core\Config;

class S3 implements ServiceInterface
{

    public $filepath;

    public function open($path, $mode)
    {

        $this->s3 = S3Client::factory([
          'key' => Config::_()->aws['key'],
          'secret' => Config::_()->aws['secret'],
          'region' => 'us-east-1'
        ]);

        $cloned = clone $this;
        $cloned->filepath = $path;
        return $cloned;
    }

    public function close()
    {
    }

    public function write($data)
    {
        return $this->s3->putObject([
          'ACL' => 'public-read',
          'Bucket' => 'cinemr',
          'Key' => $this->filepath,
          'ContentLength' => strlen($data),
          //'ContentLength' => filesize($file),
          'Body' => $data,
        ]);
    }

    public function read($length = 0)
    {
        //get temorary url and return..
        $url = $this->s3->getObjectUrl("cinemr", $this->filepath, "+15 minutes");
        var_dump($url); exit;
        header("Location: $url");
        //return fread($this->resource, $length);
    }

    public function seek($offset = 0)
    {
        //not supported
    }

    public function destroy()
    {

    }


}
