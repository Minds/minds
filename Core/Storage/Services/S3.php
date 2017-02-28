<?php

namespace Minds\Core\Storage\Services;

use Aws\S3\S3Client;
use Minds\Core\Config;

class S3 implements ServiceInterface
{

    public $s3;
    public $filepath;
    public $mode;

    private $modes = [
      'read',
      'read-uri',
      'redirect',
      'write'
    ];

    public function open($path, $mode)
    {

        if ($mode && !in_array($mode, $this->modes)) {
            throw new \Exception("$mode is not a supported type");
        }

        $this->mode = $mode;

        $this->s3 = S3Client::factory([
          'key' => Config::_()->aws['key'],
          'secret' => Config::_()->aws['secret'],
          'region' => 'us-east-1',
          'curl.options' => [
            CURLOPT_CONNECTTIMEOUT => 1, //if we don't connect in 1 second
            CURLOPT_TIMEOUT => 120 //if the request takes longer than 2 minutes (120 seconds)
          ]
        ]);

        $this->filepath = $path;
        return $this;
    }

    public function close()
    {
    }

    public function write($data)
    {
        
        //TODO: check mime performance here
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($data);

        $write =  $this->s3->putObject([
          'ACL' => 'public-read',
          'Bucket' => Config::_()->aws['bucket'],
          'Key' => $this->filepath,
          'ContentType' => $mimeType,
          'ContentLength' => strlen($data),
          //'ContentLength' => filesize($file),
          'Body' => $data,
        ]);

        //also write to disk until full migration
        $disk = new Disk();
        $disk->open($this->filepath, 'write');
        $disk->write($data);
        $disk->close();

        return true;
    }

    public function read($length = 0)
    {
        switch ($this->mode) {
            case "read-uri":
                $url = $this->s3->getObjectUrl(Config::_()->aws['bucket'], $this->filepath, "+15 minutes");
                return $url;
                break;
            case "read":
                try{
                    $result = $this->s3->getObject([
                        'Bucket' => Config::_()->aws['bucket'],
                        'Key' => $this->filepath
                    ]);
                } catch (\Exception $e){
                }
                return $result['Body'];
                break;
            case "redirect":
            default:
                //for now, check if the file exists, and fallback to disk if not!
                /*if (!$this->s3->doesObjectExist(Config::_()->aws['bucket'], $this->filepath)) {
                    $disk = new Disk();
                    $disk->open($this->filepath, 'read');
                    $content = $disk->read();
                    $disk->close();
                    return $content;
                }*/

                $url = $this->s3->getObjectUrl(Config::_()->aws['bucket'], $this->filepath, "+15 minutes");
                //$this->filepath = str_replace('//', '/', $this->filepath);
                //$url = Config::_()->aws['cloudfront'] . $this->filepath;
                header("Location: $url");
                exit;
        }

    }

    public function seek($offset = 0)
    {
        //not supported
    }

    public function destroy()
    {

    }


}
