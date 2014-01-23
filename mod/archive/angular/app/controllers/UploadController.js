/**
 * Upload controller.
 *
 * Initializes the jQuery uploader and the Kaltura service.
 */

function UploadCtrl($scope, Kaltura, Elgg, $q, $timeout) {

    $scope.fileInfo = [];
    $scope.queue = [];
    $scope.uploaderElement = '#fileupload';
    $scope.saveEnabled = false;
    $scope.albums = albums;
    
    var config = {
        ks: ks,
        serviceUrl: serviceUrl
    };

    $scope.isSelected = function(album){
        if(album['title'].toLowerCase() == "uploads")
        {
            return true;
        }

        return false;
    };

    $scope.thumbConfig = {
        serviceUrl: serviceUrl,
        pid: partnerId
    };
    
    /**
     * License options (for dropdown)
     */
   	$scope.licenses = [
   		{value:'not-selected', text:'-- select a license--'},
		{value:'attribution-cc', text:'Attribution CC BY'},
		{value:'attribution-sharealike-cc', text:'Attribution-ShareAlike BY-SA'},
		{value:'attribution-noderivs-cc', text:'Attribution-NoDerivs CC BY-ND'},
		{value:'attribution-noncommercial-cc', text:'Attribution-NonCommerical CC BY-NC'},
		{value:'attribution-noncommercial-sharealike-cc', text:'Attribution-NonCommerical-ShareAlike CC BY-NC-SA'},
		{value:'attribution-noncommercial-noderivs-cc', text:'Attribution-NonCommerical-NoDerivs CC BY-NC-ND'},
		{value:'publicdomaincco', text:'Public Domain CCO "No Rights Reserved'},
		{value:'gnuv3', text:'GNU v3 General Public License'},
		{value:'gnuv1.3', text:'GNU v1.3 Free Documentation License'},
		{value:'gnu-lgpl', text:'GNU Lesser General Public License'},
		{value:'gnu-affero', text: 'GNU Affero General Public License'},
		{value:'apache-v1', text:'Apache License, Version 1.0'},
		{value:'apache-v1.1', text:'Apache License, Version 1.1'},
		{value:'apache-v2', text:'Apache License, Version 2.0'},
		{value:'mozillapublic', text:'Mozilla Public License'},
		{value:'bsd', text:'BSD License'}
	];
	$scope.default_license = 'not-selected';

    /**
     * Access options (for dropdown)
     */
	$scope.access = [
		{value:0, text:'Private'},
		{value:2, text:'Public'}
	];
	$scope.default_access = 2;

    /**
     * Gets the uploaded file thumbnail
     * @param entryId
     * @returns {string}
     */
    $scope.getFileThumbnail = function(entry, thumbSecond, guid) {
            if(entry){ //Only applies to video
                var thumbnailUrl = serviceUrl + '/p/' + partnerId + '/thumbnail/entry_id/' + entry.id + '/width/400/vid_sec/' + thumbSecond;
                // return empty string if entryID not set, otherwise return thumbnail URL
                return thumbnailUrl;
            }else if(guid){
                var thumbnailUrl = cdnUrl + '/photos/thumbnail/' + guid +'/large';
                return thumbnailUrl;
            }

            return "";
    };
    
    /**
     * Perform reAlignment of upload items
     */
    $scope.reAlign = function(e){
    	$('.elgg-list.mason').masonry('reloadItems').masonry();
		return true;
    };

    /**
     * Callback for the uploader add method. Creates a token, uploads a file, creates an entry and adds the uploaded
     * content.
     * @param data jQuery uploader data object, contains the file to upload.
     * @param elm the uploader element id (with #).
     */
    $scope.uploadFiles = function(data, elm) {
    	
        var file = data.files[0];
        var index = $scope.fileInfo.length;
        data.index = index;
        
        if(index == 0){
        	if(!confirm('I confirm that I have the rights and permission to use this content')){
        		$scope.fileInfo.pop(index);
        		return false;
        	}
        }
        
        $scope.queue.push(file);

        $scope.saveEnabled = true;
		      
        $scope.fileInfo[index] = {};
        $scope.fileInfo[index]['fileObj'] = file;
        $scope.fileInfo[index]['fileType'] = $scope.detectMediaType(file.type);
        $scope.fileInfo[index]['name'] = file.name;
        $scope.fileInfo[index]['updateResult'] = false;
        $scope.fileInfo[index]['license'] = $scope.default_license;
        $scope.fileInfo[index]['access_id'] = $scope.default_access;
	$scope.fileInfo[index]['tags'] = "";
		
        /**
         * File type specifics
         * 
         * VIDEO/AUDIO: - Appends thumbSecond param
         * 				- Begin kaltura upload
         * 
         * IMAGES: 		- Set albumId (change to album_guid)
         * 				- Begin upload
         * 				- Set GUID
         * 
         * OTHER: 		- Begin upload
         * 				- Set GUID
         */
        if ($scope.fileInfo[index]['fileType'] == 'video' || $scope.fileInfo[index]['fileType'] == 'audio' ) {

			$scope.fileInfo[index]['thumbSecond'] = 0;
			
            // Add upload token
            var token = Kaltura.uploadTokenAdd(file);

            // Upload file to token
            var uploadComplete = Kaltura.uploadTokenUpload(token, jQuery(elm), data, $scope);

            // Add entry
            $scope.fileInfo[index]['entryId'] = Kaltura.baseEntryAdd($scope.fileInfo[index], uploadComplete);

            // Add content to entry in Kaltura
            $scope.fileInfo[index]['entry'] = Kaltura.baseEntryAddContent($scope.fileInfo[index]['entryId'], token);

            $scope.fileInfo[index]['entryRefresh'] = $scope.getEntry($scope.fileInfo[index]['entryId']);

            //Create an Elgg entity
            $scope.fileInfo[index]['guid'] = Elgg.addElggEntity($scope.fileInfo[index]);
    
		} else if($scope.fileInfo[index]['fileType'] == 'image') {
			
			for(albumIndex in $scope.albums){
				
		   		if($scope.isSelected($scope.albums[albumIndex])){
		   			
		     		$scope.fileInfo[index]['albumId'] = albumIndex; //if video we set the album to "" else we set to the album
				
				}
				
			}
			
			$scope.fileInfo[index]['guid'] = Elgg.uploadElggFile($scope.fileInfo[index], jQuery(elm), data, $scope);
	   
	    } else {
	    	
			$scope.fileInfo[index]['guid'] = Elgg.uploadElggFile($scope.fileInfo[index], jQuery(elm), data, $scope);
		
		}
			
    };

    /**
     * Detect the type of a file (audio, video, image).
     * @param type the MIME type.
     * @returns string the file type.
     */
    $scope.detectMediaType = function(type) {
        return type.substring(0, type.indexOf('/'));
    };

	
    $scope.saveAll = function(){
        for(var index in $scope.fileInfo) //Saves each file
        {
            $scope.updateEntry(index);
        }
		elgg.system_message('Success!');
    };

    /**
     * Update an entry using its file info.
     * @param index the index of the entry in the file info object.
     */
    $scope.updateEntry = function(index) {
        Elgg.updateElggEntity($scope.fileInfo[index]);
    };

    /**
     * Update an entry using its file info.
     * @param index the index of the entry in the file info object.
     */
    $scope.deleteEntry = function(index) {
        //Abort XHR requests from upload only and not other requests (like add / update)
        if($scope.fileInfo[index] && !$scope.fileInfo[index]['xhr'].isResolved())
        {
            $scope.fileInfo[index]['xhr'].abort();
        }

        //Entry was created in elgg / Kaltura, remove it
        if($scope.fileInfo[index] && $scope.fileInfo[index]['guid'])
        {
            //TODO: maybe implement a cancelable queue

//            Elgg.deleteElggEntity($scope.fileInfo[index]['guid']);
        }

        //Removes item from the list
        if ( ~index ) $scope.fileInfo.splice(index, 1);

    };

    /**
     * Initialize the jQuery uploader.
     * @param elm the uploader element (with #).
     */
    $scope.initializeUploader = function(elm) {
        jQuery(elm).fileupload({
            add: function (e, data) {
                $scope.uploadFiles(data, elm);
            },
            dropZone: $('#dropzone')
        });

        // bind the events
        $scope.bindUploaderEvents(elm);
    };

    /**
     * Returns true if metadata can be currently saved.
     * @param index the index of the entry in the file info object.
     * @returns true if update currently possible.
     */
    $scope.canDeleteMetadata = function(index) {
        return $scope.fileInfo[index]['updateResult'];
    };

    /**
     * Bind uploader events.
     * @param elm
     */
    $scope.bindUploaderEvents = function(elm) {
        jQuery(elm).bind('fileuploadprogress', function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
	
            if($scope.fileInfo[data.index]) //Only if element is found
            {
                $scope.fileInfo[data.index]['progress'] = progress;

                $scope.$apply();
            }
            else{
            }
        });

        jQuery(elm).bind('fileuploaddone', function(e, data) {
	
			$('.elgg-list.mason').imagesLoaded(function(){
					$('.elgg-list.mason').masonry('reloadItems').masonry();
			});

        });
    };

    // Initialize the jQuery uploader.
    $scope.initializeUploader($scope.uploaderElement);

    // Set the Kaltura service ks.
    Kaltura.setConfig(config);

    $scope.isImage = function(fileItem){
        if(fileItem['fileType'] == 'image')
        {
            return true;
        }

        return false;
    };

    $scope.rotateImage = function(entryRefresh, rotateLeft, index){
        if(entryRefresh && entryRefresh.duration){
            if(rotateLeft){ //Rotate left (reduce video time)

                $scope.fileInfo[index].thumbSecond = Math.abs(($scope.fileInfo[index].thumbSecond - entryRefresh.duration * 0.2) % entryRefresh.duration);

            }else //Rotate right (increase on video seconds)
            {
                $scope.fileInfo[index].thumbSecond = Math.abs(($scope.fileInfo[index].thumbSecond + entryRefresh.duration * 0.2) % entryRefresh.duration);
            }
        }
    };

    $scope.isShowThumbArrows = function(entry, index){
        if($scope.fileInfo[index].fileType == 'video' || $scope.fileInfo[index].fileType == 'audio')
        {
            if(entry && entry.id){
                if(entry.duration && entry.duration > 0){
                    return true;
               }
                else{
                    $scope.fileInfo[index].entryRefresh = $timeout(function() {
                                return $scope.getEntry(entry.id);
                            }, 20 * 1000);
                    }
                }
        }

        return false;
    };

    $scope.getEntry = function(entryId) {
        return Kaltura.baseEntryGet(entryId);
    };
}
