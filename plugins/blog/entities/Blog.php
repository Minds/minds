<?php
namespace Minds\plugin\blog\entities;

use Minds\Core;
use Minds\Helpers;

class Blog extends \ElggObject
{
    /**
     * Set subtype to blog.
     */
    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['subtype'] = "blog";
        $this->attributes['mature'] = false;
    }

    /**
     * Return an array of fields which can be exported.
     *
     * @return array
     */
    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), array(
            'last_updated',
            'excerpt',
            'license',
            'ownerObj',
            'header_bg',
            'header_top',
            'monetized',
            'mature',
        ));
    }

    /**
     * Icon URL
     */
    public function getIconURL($size = '')
    {
        if ($this->header_bg) {
            global $CONFIG;
            $base_url = Core\Config::build()->cdn_url ? Core\Config::build()->cdn_url : elgg_get_site_url();
            $image = elgg_get_site_url() . 'fs/v1/banners/' .  $this->guid . '/'.$this->last_updated;

            return $image;
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->strictErrorChecking = false;
        $dom->loadHTML($this->description);
        $nodes = $dom->getElementsByTagName('img');
        foreach ($nodes as $img) {
            $image = $img->getAttribute('src');
        }
        $base_url = Core\Config::build()->cdn_url ? Core\Config::build()->cdn_url: elgg_get_site_url();
        $image = $base_url . 'thumbProxy?src='. urlencode($image) . '&c=2708';
        if ($width) {
            $image .= '&width=' . $width;
        }
        return $image;
    }

    /**
     * Sets the maturity flag for this activity
     * @param mixed $value
     */
    public function setMature($value)
    {
        $this->mature = (bool) $value;
        return $this;
    }

    /**
     * Gets the maturity flag
     * @return boolean
     */
    public function getMature()
    {
        return (bool) $this->mature;
    }

    /**
     * Return the url for this entity
     */
    public function getUrl()
    {
        return elgg_get_site_url() . 'blog/view/' . $this->guid;
    }

    public function export()
    {
        $export = parent::export();
        $export['thumbnail_src'] = $this->getIconUrl();
        $export['description'] = $this->description; //blogs need to be able to export html
        $export['thumbs:up:user_guids'] = (array) array_values($export['thumbs:up:user_guids'] ?: []);
        $export['thumbs:up:count'] = Helpers\Counters::get($this->guid, 'thumbs:up');
        $export['thumbs:down:count'] = Helpers\Counters::get($this->guid, 'thumbs:down');
        $export['mature'] = (bool) $export['mature'];
        $export['monetized'] = (bool) $export['monetized'];
        return $export;
    }
}
