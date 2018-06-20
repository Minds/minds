<?php

/**
 * Minds Blog Headers
 *
 * @author emi
 */

namespace Minds\Core\Blogs;

use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Entities\Actions\Save;

class Header
{
    /** @var Config */
    protected $config;

    /** @var Save */
    protected $saveAction;

    /**
     * Header constructor.
     * @param null $config
     */
    public function __construct($config = null, $saveAction = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->saveAction = $saveAction ?: new Save();
    }

    /**
     * Resolves the blog image, using either the header o the first <img>
     * @param Blog $blog
     * @param null $size
     * @return string
     */
    public function resolve(Blog $blog, $size = null)
    {
        $baseUrl = $this->config->get('cdn_url') ?: $this->config->get('site_url');
        $size = $size ?: 512;

        if ($size > 2000) {
            $size = 2000;
        }

        if ($blog->hasHeaderBg()) {
            $image = "{$baseUrl}fs/v1/banners/{$blog->getGuid()}/{$blog->getLastUpdated()}";
        } else {
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->strictErrorChecking = false;
            $dom->loadHTML($blog->getBody());

            $nodes = $dom->getElementsByTagName('img');

            $imgSrc = null;
            foreach ($nodes as $imgNodes) {
                $imgSrc = $imgNodes->getAttribute('src');
            }

            if (!$imgSrc) {
                return '';
            }

            $encodedImgSrc = urlencode($imgSrc);

            $image = "{$baseUrl}api/v2/media/proxy?size={$size}&src={$encodedImgSrc}&_={$blog->getLastUpdated()}";
        }

        return $image;
    }

    /**
     * Writes the header file
     * @param Blog $blog
     * @param $image
     * @return bool
     * @throws \IOException
     * @throws \InvalidParameterException
     * @throws \Minds\Exceptions\StopEventException
     */
    public function write(Blog $blog, $image, $headerTop = 0)
    {
        $file = new \ElggFile();
        $file->owner_guid = $blog->getOwnerGuid();
        $file->setFilename("blog/{$blog->getGuid()}.jpg");
        $file->open('write');
        $file->write($image);
        $file->close();
        $blog->setHasHeaderBg(true);
        $blog->setHeaderTop($headerTop);
        $blog->setLastUpdated(time());

        return $this->saveAction
            ->setEntity($blog)
            ->save();
    }

    /**
     * Reads the header file
     * @param Blog $blog
     * @return \ElggFile
     * @throws \IOException
     * @throws \InvalidParameterException
     */
    public function read(Blog $blog)
    {
        $header = new \ElggFile();
        $header->owner_guid = $blog->getOwnerGuid();
        $header->setFilename("blog/{$blog->getGuid()}.jpg");
        $header->open('read');

        return $header;
    }
}
