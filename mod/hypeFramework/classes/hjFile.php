<?php

class hjFile extends ElggFile {

    protected function initializeAttributes() {
        parent::initializeAttributes();
        $this->attributes['guid'] = null;
        $this->attributes['owner_guid'] = elgg_get_logged_in_user_guid();
        $this->attributes['container_guid'] = elgg_get_logged_in_user_guid();
        $this->attributes['type'] = 'object';
        $this->attributes['subtype'] = "hjfile";
    }

    public function __construct($guid = null) {
        parent::__construct($guid);
    }

    public function delete() {

        $thumbnails = array($this->tinythumb, $this->smallthumb, $this->mediumthumb, $this->largethumb);
        foreach ($thumbnails as $thumbnail) {
            if ($thumbnail) {
                $delfile = new ElggFile();
                $delfile->owner_guid = $this->owner_guid;
                $delfile->setFilename($thumbnail);
                $delfile->delete();
            }
        }

        return parent::delete();
    }

}