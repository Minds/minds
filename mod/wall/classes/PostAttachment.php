<?php
/**
 * WallPost Class
 * 
 */
class PostAttachment extends ElggFile{
	
	protected $resized = array();
	protected $useArchive = false;
	protected $container;

	public function __construct($guid = NULL){
		parent::__construct($guid);
		
		if(!$guid){
			
			if(elgg_is_active_plugin('archive')){
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
	}
	
	/**
	 * Save to archive
	 */
	public function saveToArchive($file){
		switch($file['type']){
			case 'image/jpeg':
			case 'image/png':

				$image = new minds\plugin\archive\entities\image();
				$image->container_guid = $this->container->guid;
				$image->access_id = $album->access_id;
				$image->upload($file);
				$image->setMimeType($file['type']);
				$image->createThumbnails();
				$guid = $image->save($file);
				return $guid;
				break;
			case 'video/mp4':
			case 'video/webm':
			case 'video/ogv':
					break;
			default:
				$fileobj = new ElggFile();
				$fileobj->access_id = 2;
				$prefix = "file/";
				$filestorename = elgg_strtolower(time().$file['name']);
				$fileobj->setFilename($prefix . $filestorename);
				$fileobj->setMimeType($file['type']);
				$fileobj->originalfilename = $file['name'];
				$fileobj->simpletype = file_get_simple_type($mime_type);

				//save the space so we can add it to our quota.
				$fileobj->size = $file['size'];

				// Open the file to guarantee the directory exists
				$fileobj->open("write");
				$fileobj->close();

				move_uploaded_file($file['tmp_name'], $fileobj->getFilenameOnFilestore());

				return $guid = $fileobj->save();
			}
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
