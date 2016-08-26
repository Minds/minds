<?php
/**
 * A minds archive video entity
 *
 * Handles basic communication with cinemr
 */
namespace minds\plugin\archive\entities;

use Minds\Core;
use Minds\Entities\Object;
use cinemr;
use Minds\Helpers;

use Minds\plugin\archive\Core\Services\Factory;

class video extends object
{
    private $cinemr;

    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['super_subtype'] = 'archive';
        $this->attributes['subtype'] = "video";
    }


    public function __construct($guid = null)
    {
        parent::__construct($guid);
    }

    public function cinemr()
    {
        return new cinemr\sdk\client(array(
                'account_guid' => '335988155444367360',
                            'secret' => '+/rW1ArsueEjXK++0zkxlBrbLkb5suHqvqZJ64kX8rk=',
                'uri' => 'http://cinemr.minds.com'
            ));
    }

    /**
     * Get the status of the video
     */
    public function getStatus()
    {
        $cinemr = $this->cinemr();
        $data = $cinemr::factory('media')->get($this->cinemr_guid);
        return $data['status'];
    }

    /**
     * Return the source url of the remote video
     * @param string $transcode
     * @return string
     */
    public function getSourceUrl($transcode = '720.mp4')
    {
        $url = Core\Config::_()->cinemr_url . $this->cinemr_guid . '/' . $transcode;
        return $url;
    }

    /**
     * Uploads to remote
     *
     */

    public function upload($filepath)
    {
        if(!$this->guid){
            $this->guid = Core\Guid::build();
        }

        $aws = Factory::build('AWS');
        $aws->setKey($this->getGuid())
          ->saveToFilestore($filepath)
          ->transcode();

        $this->cinemr_guid = $this->getGuid();
    }

    public function getIconUrl($size = "medium")
    {
        $domain = elgg_get_site_url();
        global $CONFIG;
        if (isset($CONFIG->cdn_url)) {
            $domain = $CONFIG->cdn_url;
        }

        return $domain . 'api/v1/archive/thumbnails/'.$this->guid;
    }

    public function getURL()
    {
        return elgg_get_site_url() . 'archive/view/'.$this->guid;
    }

    /**
     * Extend the default entity save function to update the remote service
     *
     */
    public function save($force = false)
    {
        $this->super_subtype = 'archive';
        parent::save((!$this->guid || $force));


        try {
            $prepared = new \Minds\Core\Data\Neo4j\Prepared\Common();
            \Minds\Core\Data\Client::build('Neo4j')->request($prepared->createObject($this));
        } catch (\Exception $e) {
        }

        $cinemr = $this->cinemr();
        $cinemr::factory('media')->post($this->cinemr_guid, array(
                'title' => $this->title,
                'description' => $this->description,
                'minds_guid' => $this->guid,
                'minds_owner' => $this->owner_guid
            ));
        return $this->guid;
    }

    /**
     * Extend the default delete function to remove from the remote service
     */
    public function delete()
    {
        $result = parent::delete();

        $cinemr = $this->cinemr();
        $cinemr::factory('media')->delete($this->cinemr_guid);

        return $result;
    }

    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), array(
          'thumbnail',
                'cinemr_guid',
                'license',
                'monetized'
            ));
    }

    public function getAlbumChildrenGuids()
    {
        $db = new Core\Data\Call('entities_by_time');
        $row= $db->getRow("object:container:$this->container_guid", ['limit'=>100]);
        $guids = [];
        foreach ($row as $col => $val) {
            $guids[] = (string) $col;
        }
        return $guids;
    }

    /**
     * Extend exporting
     */
    public function export()
    {
        $export = parent::export();
        $export['thumbnail_src'] = $this->getIconUrl();
        $export['src'] = array(
            '360.mp4' => $this->getSourceUrl('360.mp4'),
            '720.mp4' => $this->getSourceUrl('720.mp4')
        );
        $export['play:count'] = Helpers\Counters::get($this->guid, 'plays');
        $export['thumbs:up:count'] = Helpers\Counters::get($this->guid, 'thumbs:up');
        $export['thumbs:down:count'] = Helpers\Counters::get($this->guid, 'thumbs:down');
        $export['description'] = (new Core\Security\XSS())->clean($this->description); //videos need to be able to export html.. sanitize soon!
        return $export;
    }
}
