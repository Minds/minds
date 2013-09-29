/**
 * Upload controller.
 *
 * Initializes the jQuery uploader and the Kaltura service.
 *
 *
 * User: ron
 * Date: 7/4/13
 * Time: 3:05 PM
 * To change this template use File | Settings | File Templates.
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
    }

    $scope.thumbConfig = {
        serviceUrl: serviceUrl,
        pid: partnerId
    };

    /**
     * Gets the uploaded file thumbnail
     * @param entryId
     * @returns {string}
     */
    $scope.getFileThumbnail = function(entry, thumbSecond, guid) {
            if(entry){ //Only applies to video
                var thumbnailUrl = 'url('+ serviceUrl + '/p/' + partnerId + '/thumbnail/entry_id/' + entry.id + '/width/400/vid_sec/' + thumbSecond +')';
                // return empty string if entryID not set, otherwise return thumbnail URL
                return thumbnailUrl;
            }else if(guid){
                var thumbnailUrl = 'url(' + cdnUrl + '/photos/thumbnail/' + guid +'/large)';
                return thumbnailUrl;
            }

            return "";
    }

    /**
     * Callback for the uploader add method. Creates a token, uploads a file, creates an entry and adds the uploaded
     * content.
     * @param data jQuery uploader data object, contains the file to upload.
     * @param elm the uploader element id (with #).
     */
    $scope.uploadFiles = function(data, elm) {
        var file = data.files[0];
        $scope.queue.push(file);

        $scope.saveEnabled = true;

        var fileInfoRow = {};
        fileInfoRow['fileObj'] = file;
        fileInfoRow['fileType'] = $scope.detectMediaType(file.type);
        fileInfoRow['name'] = file.name;
        fileInfoRow['updateResult'] = false;
        fileInfoRow['license'] = 'not-selected';
        fileInfoRow['accessId'] = "2";
	fileInfoRow['tags'] = "";
	
	if(fileInfoRow['fileType'] == 'image') {
            for(albumIndex in $scope.albums){
   		if($scope.isSelected($scope.albums[albumIndex])){
               		     fileInfoRow['albumId'] = albumIndex; //if video we set the album to "" else we set to the album
               	}
            }
        } else if(fileInfoRow['fileType'] == 'video' || fileInfoRow['fileType'] == 'audio'){
		fileInfoRow['thumbSecond'] = 0;
	}

        $scope.fileInfo.push(fileInfoRow);

        data.fileIndex = $scope.fileInfo.length - 1;

        // If file is Video/Audio then add entry to Kaltura and create entity in Elgg
        if ($scope.fileInfo[data.fileIndex]['fileType'] == 'video' || $scope.fileInfo[data.fileIndex]['fileType'] == 'audio' ) {

            // Add upload token
            var token = Kaltura.uploadTokenAdd(file);

            // Upload file to token
            var uploadComplete = Kaltura.uploadTokenUpload(token, jQuery(elm), data, $scope);

            // Add entry
            $scope.fileInfo[data.fileIndex]['entryId'] = Kaltura.baseEntryAdd($scope.fileInfo[data.fileIndex], uploadComplete);

            // Add content to entry in Kaltura
            $scope.fileInfo[data.fileIndex]['entry'] = Kaltura.baseEntryAddContent($scope.fileInfo[data.fileIndex]['entryId'], token);

            $scope.fileInfo[data.fileIndex]['entryRefresh'] = $scope.getEntry($scope.fileInfo[data.fileIndex]['entryId']);

            //Create an Elgg entity
            $scope.fileInfo[data.fileIndex]['guid'] = Elgg.addElggEntity($scope.fileInfo[data.fileIndex]);
    
	} else if($scope.fileInfo[data.fileIndex]['fileType'] == 'image') {
		$scope.fileInfo[data.fileIndex]['guid'] = Elgg.uploadElggFile($scope.fileInfo[data.fileIndex], jQuery(elm), data, $scope);
        } else {
		$scope.fileInfo[data.fileIndex]['guid'] = Elgg.uploadElggFile($scope.fileInfo[data.fileIndex], jQuery(elm), data, $scope);
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
    }

    /**
     * Update an entry using its file info.
     * @param index the index of the entry in the file info object.
     */
    $scope.updateEntry = function(index) {
        Elgg.updateElggEntity($scope.fileInfo[index]);
    }

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

    }

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

            if($scope.fileInfo[data.fileIndex]) //Only if element is found
            {
                $scope.fileInfo[data.fileIndex]['progress'] = progress;

                $scope.$apply();
            }
            else{
            }
        });

        jQuery(elm).bind('fileuploaddone', function(e, data) {


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
    }

    $scope.rotateImage = function(entryRefresh, rotateLeft, index){
        if(entryRefresh && entryRefresh.duration){
            if(rotateLeft){ //Rotate left (reduce video time)

                $scope.fileInfo[index].thumbSecond = Math.abs(($scope.fileInfo[index].thumbSecond - entryRefresh.duration * 0.2) % entryRefresh.duration);

            }else //Rotate right (increase on video seconds)
            {
                $scope.fileInfo[index].thumbSecond = Math.abs(($scope.fileInfo[index].thumbSecond + entryRefresh.duration * 0.2) % entryRefresh.duration);
            }
        }
    }

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
    }

    $scope.getEntry = function(entryId) {
        return Kaltura.baseEntryGet(entryId);
    };
}
