<?php
/**
 * Minds Archive AWS Service
 */

namespace Minds\Plugin\Archive\Core\Services;

use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Aws\S3\S3Client;
use Minds\Core\Config;

class AWS implements ServiceInterface
{

    private $s3;
    private $et;

    private $key;
    private $dir = 'cinemr_data';

    public function __construct()
    {
        $this->s3 = S3Client::factory([
          'key' => Config::_()->aws['key'],
          'secret' => Config::_()->aws['secret'],
          'region' => 'us-east-1'
        ]);
        $this->et = ElasticTranscoderClient::factory([
    			'key' =>  Config::_()->aws['key'],
    			'secret' => Config::_()->aws['secret'],
    			'region' => 'us-east-1'
    		]);
        $this->dir = Config::_()->aws['elastic_transcoder']['dir'];
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function saveToFilestore($file)
    {
        try {
            if (is_string($file)) {

                $result =  $this->s3->putObject([
                  'ACL' => 'public-read',
                  'Bucket' => 'cinemr',
                  'Key' => "$this->dir/$this->key/source",
                  'ContentLength' => $_SERVER['CONTENT_LENGTH'],
                  'Body' => fopen($file, 'r'),
                ]);
                return $this;

            } elseif (is_resource($file)) {

                $result =  $this->client->putObject([
                  'ACL' => 'public-read',
                  'Bucket' => 'cinemr',
                  'Key' => "$this->dir/$this->key/source",
                  'ContentLength' => $_SERVER['CONTENT_LENGTH'],
                  'Body' => $file
                ]);
                return $this;

            }
        } catch (\Exception $e) {
            var_dump($e->getMessage()); exit;
        }
        throw new \Exception('Sorry, only strings and stream resource are accepted');
    }

    public function transcode()
    {
        $outputs = [];
        $presets = Config::_()->aws['elastic_transcoder']['presets'];
        foreach ($presets as $prefix => $preset_id) {
            $outputs[] = [
              'Key' => "$this->dir/$this->key/$prefix",
              'PresetId' => $preset_id,
              'ThumbnailPattern' => "$this->dir/$this->key/thumbnail-{count}",
            ];
        }
        $params = [
		       'PipelineId' => Config::_()->aws['elastic_transcoder']['pipeline_id'],
		       'Input' => ['Key' => "$this->dir/$this->key/source"],
		       'Outputs' => $outputs,
		  	];
        $this->et->createJob($params);

        return $this;
    }

}
