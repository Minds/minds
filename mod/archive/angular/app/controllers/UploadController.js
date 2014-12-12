/**
 * Upload controller.
 */

function UploadCtrl($scope, Elgg, $q, $timeout, $http, $location) {

    $scope.fileInfo = [];
    $scope.status = 'select';
    $scope.queue = [];
    $scope.uploaderElement = '#fileupload';
    $scope.album = 0;
    $scope.albums = albums;
    $scope.completed = 0;
    
    $scope.total = function(){
    	return $scope.files.length;
    }
	
	var type_timeout;
	$scope.createAlbum = function(){
		if (type_timeout) $timeout.cancel(type_timeout);
		
		type_timeout = $timeout(function(){
			Elgg.createAlbum({title:$scope.albums.newInput}).then(function(guid){ 
				$scope.albums[guid] = {
					guid: guid,
					title: $scope.albums.newInput
				};
				$scope.albums.newInput = '';
				//make use of new album
				$scope.clickAlbum = false;
				$scope.album = guid;
			});
		},1000);
	};
	
	$scope.getAlbum = function(){
		if($scope.album == 0){
			return {
				guid: 0,
				title: 'Select album...'
			};
		}
		return $scope.albums[$scope.album];
	};
    
    $scope.selectAlbum = function(guid){
    	$scope.clickAlbum = false;
		$scope.album = guid;
		
		var guids= [];
		//we now need to update all items to use our new album..
		for($index in $scope.files){
			$scope.files[$index].container_guid = guid;
			guids.push($scope.files[$index].guid);
		}
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
	
	$scope.setDefaultLicense = function(){
			
		for($index in $scope.files){
			if($scope.files[$index].license == 'not-selected')
				$scope.files[$index]['license'] = $scope.default_license;
		}
		
	};

    /**
     * Access options (for dropdown)
     */
	$scope.access = [
		{value:0, text:'Private'},
		{value:2, text:'Public'}
	];
	
	$scope.default_access = 2;
	
	var saveTimeout;
	$scope.changed = function(guid) {
		if (saveTimeout) $timeout.cancel(saveTimeout);
		
		saveTimeout = $timeout(function(){
			$scope.updateEntity(guid).then(function($index){
					$scope.files[$index]['progress'] = 100;
			}, function($index){
				$scope.files[$index]['progress'] = 'fail';
			}, function(update){
			});
		},1000);
		
	};

    /**
     * Gets the uploaded file thumbnail
     * @param entryId
     * @returns {string}
     */
    $scope.getFileThumbnail = function(thumbSecond, guid) {
	if(guid){
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
        	$scope.uploading = true;
		//	$scope.$apply(); //sometimes this doesn't update
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

	/**
	 * @deprecated
	 */
    $scope.saveAll = function(){
    	return;
        for(var index in $scope.fileInfo) //Saves each file
        {
            $scope.updateEntity(index);
        }
		elgg.system_message('Success!');
    };

    /**
     * Initialize the jQuery uploader.
     * @param elm the uploader element (with #).
     */
    $scope.initializeUploader = function(elm) {
		
		$scope.files = [];
		$count = 0;
    	$(elm).fileupload({
    		url: elgg.security.addToken(elgg.get_site_url() + 'action/archive/upload'),
    		previewMaxWidth: 100,
	        previewMaxHeight: 100,
	        previewCrop: true,
	        limitConcurrentUploads: 3,
	        maxNumberOfFiles: 50,
	        add: function(e, data){
	        	
	        	$scope.status= 'uploading';
	        	
				data.index = $scope.files.length;

				$scope.$apply(function(scope){
		       		var file = data.files[0],
		       				entity = {
		       					index : data.index,
		       					container_guid: $scope.album,
		       					batch_guid: batch_guid,
		           				fileType: $scope.detectMediaType(file.type),
						       	title: file.name,
						        license: $scope.default_license,
						        access_id: $scope.default_access
							};
	                  $scope.files[data.index] = entity;
				});
				$scope.files[data.index]['progress'] = 1;
				data.submit();
		
				
	        },
	        submit : function(e, data){
	        	data.formData = $scope.files[data.index];
	        },
	        dropZone: $('#dropzone')
	    }).on('fileuploadfail', function (e, data) {
	    	
			$scope.files[data.index].progress = 'fail';
			$scope.completed++; //still update so we can continue..
			$scope.$apply();
			
	    }).on('fileuploaddone', function (e, data) {
	    	
			$scope.files[data.index].guid = data.result;
			$scope.files[data.index].thumb = cdnUrl + 'archive/thumbnail/' + $scope.files[data.index].guid +'/medium';
			$scope.files[data.index]['progress'] = 100;
			$scope.completed++;
			$scope.$apply();


	    }).on('fileuploadprogress', function(e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
            if($scope.files[data.index]){
                $scope.files[data.index]['progress'] = progress;

				if(progress <= 99){ //setting to 99 because only show a green bar once we have a guid..
               		$scope.$apply();
              	} 
            }
       });

    };
    
     // Initialize the jQuery uploader.
    $scope.initializeUploader($scope.uploaderElement);

    $scope.isImage = function(fileItem){
        if(fileItem['fileType'] == 'image')
        {
            return true;
        }

        return false;
    };
    
    /**
     * Send a batch request to populate the albums. Send a warning if no album is selected
     */
    $scope.complete = function(){
    	if($scope.completed != $scope.total()){
    		alert('The upload is not complete');
    	} else if($scope.album == 0) {
    		alert('Please select an album');
    	} else {
    		var deferred  = $q.defer();
    		
   				data = {
   					batch_guid : batch_guid,
   					album_guid : $scope.album,
   					container_guid : container_guid //in case we are uploading from a group, server ignores otherwise
   				};
   				data.__elgg_token = elgg.security.token.__elgg_token;
                data.__elgg_ts = elgg.security.token.__elgg_ts;
                
    			$http({
    				
	                method: 'POST',
	                url:  elgg.get_site_url() + 'action/archive/batch',
	                data: data,
	                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
	                
	            }).success(function(guid, status, headers, config) {
	                window.onbeforeunload = function(){NULL};
	                 window.location.href = elgg.get_site_url() + 'archive/view/'+$scope.album;
	                   //$location.absUrl() == elgg.get_site_url() + 'archive/view/'+$scope.album;
	                 //  console.log('success');
				}). error(function(guid, status, headers, config) {
	                 
	                   alert('something failed');
	                     
				});
				
				//return deferred.promise;
    	}
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

	    /**
     * Update an entry using its file info.
     * @param guid, the guid of entity
     */
    $scope.updateEntity = function(guid) {
    	var deferred  = $q.defer();
    	for($index in $scope.files){
    		if($scope.files[$index]['guid']==guid && typeof guid != 'undefined'){
   				
   				$scope.files[$index]['progress'] = 95;
   				
   				data = $scope.files[$index];
   				data.__elgg_token = elgg.security.token.__elgg_token;
                data.__elgg_ts = elgg.security.token.__elgg_ts;
                
    			$http({
    				
	                method: 'POST',
	                url:  elgg.get_site_url() + 'action/archive/save',
	                data: data,
	                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
	                
	            }).success(function(guid, status, headers, config) {
	                  
	                  
	                   deferred.resolve($index);
	                   
				}). error(function(guid, status, headers, config) {
	                 
	                    deferred.reject($index);
	                     
				});
				
				return deferred.promise;
    		}

    	}
    	
    };

    /**
     * Update an entry using its file info.
     * @param index the index of the entry in the file info object.
     */
    $scope.deleteEntity = function(index) {
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

}
