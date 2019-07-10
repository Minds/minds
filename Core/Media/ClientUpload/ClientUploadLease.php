<?php

namespace Minds\Core\Media\ClientUpload;

use Minds\Traits\MagicAttributes;

/**
 * Class ClientUploadLease
 * @package Minds\Core\Media\ClientUpload
 * @method string getGuid()
 * @method string getMediaType()
 * @method string getPresignedUrl()
 */
class ClientUploadLease
{
    use MagicAttributes;

    /** @var string $guid */
    private $guid;

    /** @var string $presignedUrl */
    private $presignedUrl;

    /** @var string $mediaType */
    private $mediaType;

    /**
     * Export to API
     * @param array $extra
     * @return array
     */
    public function export($extra = [])
    {
        return [
            'guid' => (string) $this->getGuid(),
            'presigned_url' => $this->getPresignedUrl(),
            'media_type' => $this->getMediaType(),
        ];
    }

}

