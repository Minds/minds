<?php
/**
 * Tidypics Album class
 *
 * @package TidypicsAlbum
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */


class TidypicsAlbum extends ElggObject {
	/**
	 * Sets the internal attributes
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "album";
		$this->attributes['title'] = $this->getTitle();
	}

	/**
	 * Constructor
	 * @param mixed $guid
	 */
	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	/**
	 * Save an album
	 *
	 * @return bool
	 */
	public function save() {

		if (!isset($this->new_album)) {
			$this->new_album = true;
		}

		if (!isset($this->last_notified)) {
			$this->last_notified = 0;
		}

		if (!parent::save()) {
			return false;
		}
		
		mkdir(tp_get_img_dir() . $this->guid, 0755, true);

		elgg_trigger_event('create', 'album', $this);

		return true;
	}

	/**
	 * Delete album
	 *
	 * @return bool
	 */
	public function delete() {

		$this->deleteImages();
		$this->deleteAlbumDir();
		
		return parent::delete();
	}

	/**
	 * Get the title of the photo album
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Get an array of image objects
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function getImages($limit, $offset = 0) {
		$imageList = $this->getImageList();
		if ($offset > count($imageList)) {
			return array();
		}

		$imageList = array_slice($imageList, $offset, $limit);
		
		$images = array();
		foreach ($imageList as $guid) {
			$images[] = get_entity($guid,'object');
		}
		return $images;
	}

	/**
	 * View a list of images
	 *
	 * @param array $options Options to pass to elgg_view_entity_list()
	 * @return string
	 */
	public function viewImages(array $options = array()) {
		$count = $this->getSize();
		
		if ($count == 0) {
			return '';
		}

		$defaults = array(
			'count' => $count,
			'limit' => 16,
			'offset' => max(get_input('offset'), 0),
			'full_view' => false,
			'list_type' => 'gallery',
			'list_type_toggle' => false,
			'pagination' => true,
			'gallery_class' => 'tidypics-gallery',
		);

		$options = array_merge($defaults, (array) $options);
		$images = $this->getImages($options['limit'], $options['offset']);

		if (count($images) == 0) {
			return '';
		}

		return elgg_view_entity_list($images, $options);
	}

	/**
	 * Returns the cover image entity
	 * @return TidypicsImage
	 */
	public function getCoverImage() {
		return get_entity($this->getCoverImageGuid(), 'object');
	}

	/**
	 * Get the GUID of the album cover
	 * 
	 * @return int
	 */
	public function getCoverImageGuid() {
		if ($this->getSize() == 0) {
			return 0;
		}

		$guid = $this->cover;
		$imageList = $this->getImageList();
		if (!in_array($guid, $imageList)) {
			// select random photo to be cover
			$index = array_rand($imageList, 1);
			$guid = $imageList[$index];
			$this->cover = $guid;
		}
		return $guid;
	}

	/**
	 * Set the GUID for the album cover
	 *
	 * @param int $guid
	 * @return bool
	 */
	public function setCoverImageGuid($guid) {
		$imageList = $this->getImageList();
		if (!in_array($guid, $imageList)) {
			return false;
		}
		$this->cover = $guid;
		return true;
	}

	/**
	 * Get the number of photos in the album
	 *
	 * @return int
	 */
	public function getSize() {
		return count($this->getImageList());
	}

	/**
	 * Returns an order list of image guids
	 * 
	 * @return array
	 */
	public function getImageList() {
		$listString = $this->orderedImages;
		if (!$listString) {
			return array();
		}
		
		$list = unserialize($listString);
		
		return $list;
	}

	/**
	 * Sets the album image order
	 *
	 * @param array $list An indexed array of image guids
	 * @return bool
	 */
	public function setImageList($list) {
		// validate data
		foreach ($list as $guid) {
			if (!filter_var($guid, FILTER_VALIDATE_INT)) {
				return false;
			}
		}

		$listString = serialize($list);
		$this->orderedImages = $listString;
		return true;
	}

	/**
	 * Add new images to the front of the image list
	 *
	 * @param array $list An indexed array of image guids
	 * @return bool
	 */
	public function prependImageList($list) {
		$currentList = $this->getImageList();
		$list = array_merge($list, $currentList);
		return $this->setImageList($list);
	}

	/**
	 * Get the previous image in the album. Wraps around to the last image if given the first.
	 *
	 * @param int $guid GUID of the current image
	 * @return TidypicsImage
	 */
	public function getPreviousImage($guid) {
		$imageList = $this->getImageList();
		$key = array_search($guid, $imageList);
		if ($key === FALSE) {
			return null;
		}
		$key--;
		if ($key < 0) {
			return get_entity(end($imageList), 'object');
		}
		return get_entity($imageList[$key], 'object');
	}

	/**
	 * Get the next image in the album. Wraps around to the first image if given the last.
	 *
	 * @param int $guid GUID of the current image
	 * @return TidypicsImage
	 */
	public function getNextImage($guid) {
		$imageList = $this->getImageList();
		$key = array_search($guid, $imageList);
		if ($key === FALSE) {
			return null;
		}
		$key++;
		if ($key >= count($imageList)) {
			return get_entity($imageList[0],'object');
		}
		return get_entity($imageList[$key],'object');
	}

	/**
	 * Get the index into the album for a particular image
	 *
	 * @param int $guid GUID of the image
	 * @return int
	 */
	public function getIndex($guid) {
		return array_search($guid, $this->getImageList()) + 1;
	}

	/**
	 * Remove an image from the album list
	 *
	 * @param int $imageGuid
	 * @return bool
	 */
	public function removeImage($imageGuid)  {
		$imageList = $this->getImageList();
		$key = array_search($imageGuid, $imageList);
		if ($key === false) {
			return false;
		}
		
		unset($imageList[$key]);
		$this->setImageList($imageList);

		return true;
	}

	/**
	 * Has enough time elapsed between the last_notified and notify_interval setting?
	 *
	 * @return bool
	 */
	public function shouldNotify() {
		return time() - $this->last_notified > elgg_get_plugin_setting('notify_interval', 'tidypics');
	}

	/**
	 * Delete all the images in this album
	 *
	 * @todo ElggBatch?
	 */
	protected function deleteImages() {
		$images = elgg_get_entities(array(
			"type=" => "object",
			"subtype" => "image",
			"container_guid" => $this->guid,
			"limit" => ELGG_ENTITIES_NO_VALUE,
		));
		if ($images) {
			foreach ($images as $image) {
				if ($image) {
					$image->delete();
				}
			}
		}
	}

	/**
	 * Delete the album directory on disk
	 */
	protected function deleteAlbumDir() {
		$tmpfile = new ElggFile();
		$tmpfile->setFilename('image/' . $this->guid . '/._tmp_del_tidypics_album_');
		$tmpfile->subtype = 'image';
		$tmpfile->owner_guid = $this->owner_guid;
		$tmpfile->container_guid = $this->guid;
		$tmpfile->open("write");
		$tmpfile->write('');
		$tmpfile->close();
		$tmpfile->save();
		$albumdir = eregi_replace('/._tmp_del_tidypics_album_', '', $tmpfile->getFilenameOnFilestore());
		$tmpfile->delete();
		if (is_dir($albumdir)) {
			rmdir($albumdir);
		}
	}
}
