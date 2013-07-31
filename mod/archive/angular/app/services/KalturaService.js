/**
 * A Kaltura service.
 * User: ron
 * Date: 7/4/13
 * Time: 3:03 PM
 * To change this template use File | Settings | File Templates.
 */

angular.module('services.Kaltura', []);
angular.module('services.Kaltura').factory('Kaltura', ['$http', '$q', function($http, $q) {

    var kalturaService = {};
    var apiUrl = '/api_v3/index.php';
    var actionUrl= 'http://roni.innovid.com/minds-elgg-git/action/archive/';

    /**
     * Maps media type to Kaltura media type enum.
     * @type {{video: number, image: number, audio: number}}
     */
    kalturaService.mediaTypesEnum = {
        video: 1,
        image: 2,
        audio: 5
    };

    kalturaService.setSession = function(){
        var deferred = $q.defer();

        var data = {
            '__elgg_token': elgg.security.token.__elgg_token,
            '__elgg_ts': elgg.security.token.__elgg_ts
        }
        $http({
            method: 'POST',
            url: actionUrl + 'getKSession',
            data: data,
            cache: false,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transform: "transformRequest"
        }).
            success(function(elggId, status, headers, config) {
                console.log(elggId);
                deferred.resolve(elggId);
            }).
            error(function(elggId, status, headers, config) {
                console.log('error: ' + elggId)
                deferred.reject(elggId);
            })

        return deferred.promise;
    }
    /**
     * Set configuration for the Kaltura service.
     * @param config an object with fields ks and serviceUrl.
     */
    kalturaService.setConfig = function(config) {
        kalturaService.config = config,
        kalturaService.config.apiUrl = kalturaService.config.serviceUrl + apiUrl
    };

    /**
     * Add an upload token.
     * @param file the file object.
     * @returns a promise which resolves to the token id.
     */
    kalturaService.uploadTokenAdd = function(file) {
        var deferred = $q.defer();
        var url = kalturaService.config.apiUrl + '?service=uploadtoken&action=add&ks=' + kalturaService.config.ks;

        var formData = {
            'uploadToken:objectType': 'KalturaUploadToken',
            'uploadToken:fileSize': file.size,
            'uploadToken:fileName': file.name
        };

        $http({
            method: 'POST',
            url: url,
            data: formData,
            cache: false
        }).
            success(function(data, status, headers, config) {
                var token = jQuery(data).find('id').text();
                deferred.resolve(token);
            }).
            error(function(data, status, headers, config) {
                console.log('error: ' + data);
                deferred.reject();
            });

        return deferred.promise;
    };

    /**
     * Upload a file to a token.
     * @param token a promise which resolves the token id.
     * @param fileUploader the uploader element id.
     * @param data the uploader data.
     * @param $scope the calling scope.
     * @returns a promise which resolves to the token id.
     */
    kalturaService.uploadTokenUpload = function(token, fileUploader, data, $scope) {
        var deferred = $q.defer();

        $q.when(token).then(
            function(resolvedToken) {
                var url = kalturaService.config.apiUrl + '?service=uploadtoken&action=upload';
                data.formData = {};
                data.formData.uploadTokenId = resolvedToken;
                data.formData.ks = kalturaService.config.ks;

                fileUploader.fileupload('option', {
                    url: url
                });

                data.submit().done(function() {
                    deferred.resolve(resolvedToken);
                    $scope.$apply();
                });
            }
        );

        return deferred.promise;
    };

    /**
     * Add an entry.
     * @param fileInfo an object with fields name, description, tags and fileType.
     * @param uploadComplete if a promise, waits for it to resolve. Else adds immediately. Usually used to delay entry
     * creation until upload finished.
     * @returns promise which resolves to entryId.
     */
    kalturaService.baseEntryAdd = function(fileInfo, uploadComplete) {
        var deferred = $q.defer();

        $q.when(uploadComplete).then(
            function() {

                var data = {
                    'entry:objectType': 'KalturaMediaEntry',
                    'entry:type': "-1",
                    'entry:name': fileInfo['name'],
                    'entry:description': fileInfo['description'],
                    'entry:tags': fileInfo['tags'],
                    'entry:mediaType': kalturaService.mediaTypesEnum[fileInfo['fileType']]
                };

                var params = {
                    'service': 'baseEntry',
                    'action': 'add',
                    'ks': kalturaService.config.ks
                };

                $http({
                    method: 'POST',
                    url: kalturaService.config.apiUrl,
                    params: params,
                    data: data,
                    cache: false,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    transform: "transformRequest"

                }).
                    success(function(data, status, headers, config) {
                        var entryId = jQuery(data).find('id').text();
                        deferred.resolve(entryId);
                    }).
                    error(function(data, status, headers, config) {
                        console.log('error: ' + data)
                        deferred.reject(data);
                    })
            }
        );

        return deferred.promise;
    };

    /**
     * Updates an entry.
     * @param fileInfo an object with fields name, description, tags, fileType and entryId. The entryId field may be a
     * promise to wait on (until the entry is created).
     * @returns the updated entry object.
     */
    kalturaService.baseEntryUpdate = function(fileInfo) {
        var deferred = $q.defer();

        $q.when(fileInfo['entryId']).then(function(entryId) {

            var data = {
                'entryId': entryId,
                'baseEntry:name': fileInfo['name'],
                'baseEntry:description': fileInfo['description'],
                'baseEntry:tags': fileInfo['tags']
            };

            var params = {
                'service': 'baseEntry',
                'action': 'update',
                'ks': kalturaService.config.ks
            };

            $http({
                method: 'POST',
                url: kalturaService.config.apiUrl,
                params: params,
                data: data,
                cache: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transform: "transformRequest"
            }).
                success(function(data, status, headers, config) {
                    deferred.resolve(data);
                }).
                error(function(data, status, headers, config) {
                    console.log('error: ' + data)
                    deferred.reject(data);
                }
            );

        });

        return deferred.promise;
    };

    /**
     * Add content to entry id. Waits for entryId and token if promises.
     * @param entryId the entryId (or a promise which resolves to it).
     * @param token the uploaded content token (or a promise which resolves to it).
     * @returns the updated entry object.
     */
    kalturaService.baseEntryAddContent = function(entryId, token, $scope) {
        var deferred = $q.defer();
        console.log('service scope',$scope);
        $q.all([entryId, token])
            .then(
                function(values) {

                    var params = {
                        'service': 'baseEntry',
                        'action': 'addContent',
                        'ks': kalturaService.config.ks
                    };

                    var data = {
                        'resource:token': values[1],
                        'entryId': values[0],
                        'resource:objectType': 'KalturaUploadedFileTokenResource'
                    };

                    $http({
                        method: 'POST',
                        url: kalturaService.config.apiUrl,
                        data: data,
                        params: params,
                        cache: false,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        transform: "transformRequest"
                    }).
                        success(function(data, status, headers, config) {
                            var entryId = jQuery(data).find('id').text();
                            deferred.resolve(entryId);
                            console.log('content added to: '+entryId);
                        }).
                        error(function(data, status, headers, config) {
                            console.log('error: ' + data);
                            deferred.reject();
                        });
                }
            );

        return deferred.promise;
    };

    /**
     * Delete an entry.
     * @param fileInfo an object with fields name, description, tags, fileType and entryId. The entryId field may be a
     * promise to wait on (until the entry is created).
     * Delete with the entryId.
     * @returns the deleted entry object.
     */
    kalturaService.baseEntryDelete = function(fileInfo) {
        var deferred = $q.defer();

        $q.when(fileInfo['entryId']).then(function(entryId) {

            var data = {
                'entryId': entryId
            };

            var params = {
                'service': 'baseEntry',
                'action': 'delete',
                'ks': kalturaService.config.ks
            };

            $http({
                method: 'POST',
                url: kalturaService.config.apiUrl,
                params: params,
                data: data,
                cache: false,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                transform: "transformRequest"
            }).
                success(function(data, status, headers, config) {
                    deferred.resolve(data);
                }).
                error(function(data, status, headers, config) {
                    console.log('error: ' + data)
                    deferred.reject(data);
                }
            );
        });

        return deferred.promise;
    };


    return kalturaService;

}]);