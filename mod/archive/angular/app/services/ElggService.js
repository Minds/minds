/**
 * Services. Communicates with the backend. 
 */

angular.module('services.Elgg', []);
angular.module('services.Elgg').factory('Elgg', ['$http', '$q', function($http, $q) {

    var elggService = {};
    var actionUrl= serverUrl + 'action/archive/';
//    var webServicesUrl = serverUrl + 'services/api/rest/json'; // Not used for now

    /**
     * Create new Elgg entity without uploading the files to Elgg.
     * This function being used when uploading Video or Audio so we just need to create entity without uploading the files.
     * @param fileInfo an object with fields name, description, tags, fileType and entryId. The entryId field may be a
     * promise to wait on (until the entry is created within Kaltura).
     * @returns a promise which resolves the guid.
     */
    elggService.addElggEntity = function(fileInfo){
        var deferred = $q.defer();

        $q.when(fileInfo.entryId).then(function(resolvedEntryId) {
            var data = {
	            'entryId': resolvedEntryId,
	            'title': fileInfo['name'],
	            'description': fileInfo['description'],
	            'access_id': fileInfo['access_id'],
	            'license': fileInfo['license'],
	            'fileType': fileInfo['fileType'],
	            'tags': fileInfo['tags'],
	            'thumbSecond': fileInfo['thumbSecond'],
	            '__elgg_token': elgg.security.token.__elgg_token,
	            '__elgg_ts': elgg.security.token.__elgg_ts
            };
            
			$http({
                method: 'POST',
                url: actionUrl + 'upload',
                data: data,
                cache: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transform: "transformRequest"
            }).
                success(function(guid, status, headers, config) {
                    deferred.resolve(guid);
                }).
                error(function(guid, status, headers, config) {
                    deferred.reject(guid);
                });
        });

        return deferred.promise;

   };
   
	elggService.createAlbum = function(fileInfo){
   		console.log('request submited');
   		var deferred = $q.defer();
		
		var data = {
            'title': fileInfo['title'],
            'license': 'default',
            '__elgg_token': elgg.security.token.__elgg_token,
            '__elgg_ts': elgg.security.token.__elgg_ts
        };
            
		$http({
            method: 'POST',
            url: actionUrl + 'add_album',
            data: data,
            cache: false,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transform: "transformRequest"
        }).
            success(function(output, status, headers, config) {
                deferred.resolve(output.output);
            }).
            error(function(output, status, headers, config) {
                deferred.reject(output.output);
            });

        return deferred.promise;

	};

    /**
     * Create new Elgg entity and uploading the files to Elgg.
     * This function being used when uploading Images or regular files excluding Video/Audio.
     * @param fileInfo an object with fields name, description, tags, fileType and guid.
     * @param fileUploader the uploader element id.
     * @param data the uploader data.
     * @param $scope the calling scope.
     * @returns a promise which resolves to the guid entity.
     */
     elggService.uploadElggFile = function(fileInfo, fileUploader, data, $scope){
        var deferred = $q.defer();

        var url = actionUrl + 'upload';
        data.formData = {
            'title': fileInfo['name'],
            'description': fileInfo['description'],
            'access_id': fileInfo['access_id'],
            'license': fileInfo['license'],
            'fileType': fileInfo['fileType'],
            'tags': fileInfo['tags'],
            'albumId': fileInfo['albumId'],
            '__elgg_token': elgg.security.token.__elgg_token,
            '__elgg_ts': elgg.security.token.__elgg_ts
        };
        fileUploader.fileupload('option', {
            url: url
        });

        data.submit().done(function(guid) {
            deferred.resolve(guid);
            $scope.$apply();
        });

        return deferred.promise;

     };

    /**
     * Updates an entry.
     * @param fileInfo an object with fields name, description, tags, fileType and guid. The guid field may be a
     * promise to wait on (until the entry is created).
     * @returns the updated entry object.
     */
    elggService.updateElggEntity = function(fileInfo){
        var deferred  = $q.defer();

        $q.all([fileInfo['guid'], fileInfo['entryId']]).then(function(resolvedValues) {
            var data = {
                'entryId': resolvedValues[1],
                'guid': resolvedValues[0],
                'title': fileInfo['name'],
                'description': fileInfo['description'],
                'access_id': fileInfo['access_id'],
                'license': fileInfo['license'],
                'fileType': fileInfo['fileType'],
                'tags': fileInfo['tags'],
                'thumbSecond': fileInfo['thumbSecond'],
                'albumId': fileInfo['albumId'],
                '__elgg_token': elgg.security.token.__elgg_token,
                '__elgg_ts': elgg.security.token.__elgg_ts
            };
       
		$http({
                method: 'POST',
                url: actionUrl + 'upload',
                data: data,
                cache: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transform: "transformRequest"
            }).
                success(function(guid, status, headers, config) {
                    deferred.resolve(guid);
                }).
                error(function(guid, status, headers, config) {
                    deferred.reject(guid);
                });
        });
        return deferred.promise;

   };

    /**
     * Deletes an entry.
     * @param fileInfo an object with fields name, description, tags, fileType and guid. The guid field may be a
     * promise to wait on (until the entry is created).
     * @returns null
     */
    elggService.deleteElggEntity = function(fileInfo){
        var deferred  = $q.defer();

        $q.when(fileInfo['guid']).then(function(guid) {
            var data = {
                'guid': guid,
                '__elgg_token': elgg.security.token.__elgg_token,
                '__elgg_ts': elgg.security.token.__elgg_ts
            };

            $http({
                method: 'POST',
                url: actionUrl + 'delete',
                data: data,
                cache: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transform: "transformRequest"
            }).
                success(function(guid, status, headers, config) {
                    deferred.resolve(guid);
                }).
                error(function(guid, status, headers, config) {
                    deferred.reject(guid);
                });
        });
       
        return deferred.promise;

    };

    return elggService;

}]);
