<?php
/**
 * WallPost Class
 * 
 */
class PostAttachment {
	
	protected $resized = array();
	protected $useArchive = false;
	protected $container;

	public function __construct(){
		
		if(elgg_is_active_plugin('archive')){
			//is there wallpost album setup? If so, set the container guid to it
			  elgg_load_library('tidypics:upload');		
			$this->useArchive = true;
			
			$this->subtype = 'image';
						
			$albums = elgg_get_entities(array('type'=>'object', 'subtype'=>'album', 'limit'=>0));
			foreach($albums as $album){
				if(isset($album->post_attachments) && $album->post_attachments){
					$this->container = $album;
				}
			}
 
 			if(!$this->container){
				$album = new TidypicsAlbum();
				$album->post_attachments = true;
				$album->title = 'Post attachments';
				$album->access_id = 2;
				$album->save();
				$this->container = $album;
			}
			
		}
	}
	
	/**
	 * Save to archive
	 */
	public function saveToArchive($file){
		$image = new TidypicsImage();
		$image->container_guid = $this->container->guid;
		$image->access_id = $album->access_id;
		$mime = $file['type'];
		$image->setMimeType($mime);
		$guid = $image->save($file);
		$this->container->prependImageList(array($guid));
		
		return $guid;
	}
	

	/**
	 * Save to file
	 */
	public function saveToFile(){
		
	}
	
	/**
	 */
	public function save($file = null) {
	
		if($this->useArchive){
			return $this->saveToArchive($file);
		} else {
			return $this->saveToFile($file);
		}
		
	}
	
	public function resize($file){
			$icon_sizes = elgg_get_config('icon_sizes');
			
			$name = $file['name'];
	
			foreach ($icon_sizes as $name => $size_info)
				$this->resized[] = get_resized_image_from_uploaded_file($file['tmp_name'], $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);
			
	}

}
