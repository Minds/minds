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

var ks = 'ODMwMGFlOTdlNmVhMTIyMWFiZGE0NTU5NWYwNDIwYjFmNDZjMzUxOXwxMDI7MTAyOzEzNzQyMzIyNTQ7MjsxMzc0MTQ1ODU0LjY5NTthY2Nlc3NAbWluZHMuY29tOyo7Ow==';
var serviceUrl = 'http://www.minds.tv';

function UploadCtrl($scope, Kaltura, Elgg) {

    $scope.fileInfo = [];
    $scope.queue = [];
    $scope.uploaderElement = '#fileupload';


/*TODO use setSession from kaltura services in order to generate dynamic session with Kaltura*/

    var config = {
        ks: ks,
        serviceUrl: serviceUrl
    };


    /**
     * Callback for the uploader add method. Creates a token, uploads a file, creates an entry and adds the uploaded
     * content.
     * @param data jQuery uploader data object, contains the file to upload.
     * @param elm the uploader element id (with #).
     */
    $scope.uploadFiles = function(data, elm) {
        var file = data.files[0];
        $scope.queue.push(file);
        data.fileIndex = $scope.queue.length - 1;

        $scope.fileInfo[data.fileIndex] = {};
        $scope.fileInfo[data.fileIndex]['fileType'] = $scope.detectMediaType(file.type);
        $scope.fileInfo[data.fileIndex]['name'] = file.name;
        $scope.fileInfo[data.fileIndex]['updateResult'] = false;
        $scope.fileInfo[data.fileIndex]['license'] = "not-selected";
        $scope.fileInfo[data.fileIndex]['privacy'] = "0";
        $scope.fileInfo[data.fileIndex]['album'] = "";
        console.log($scope.fileInfo[data.fileIndex]['fileType'] );

        // If file is Video/Audio then add entry to Kaltura and create entity in Elgg
        if ($scope.fileInfo[data.fileIndex]['fileType'] == 'video' || $scope.fileInfo[data.fileIndex]['fileType'] == 'audio' ) {
            console.log('Uploading to Kaltura');
            // Add upload token
            var token = Kaltura.uploadTokenAdd(file);

            // Upload file to token
            var uploadComplete = Kaltura.uploadTokenUpload(token, jQuery(elm), data, $scope);

            // Add entry
            $scope.fileInfo[data.fileIndex]['entryId'] = Kaltura.baseEntryAdd($scope.fileInfo[data.fileIndex], uploadComplete);

            // Once the entry is added to Kaltura and Elgg, we enable the save and delete button to allow updates.
            $scope.fileInfo[data.fileIndex]['entryId'].then(
                function() {
                    $scope.fileInfo[data.fileIndex]['elggId'].then(
                        function() {
                            $scope.fileInfo[data.fileIndex]['updateResult'] = true;
                        })
                }
            );

            // Add content to entry in Kaltura
            Kaltura.baseEntryAddContent($scope.fileInfo[data.fileIndex]['entryId'], token);
            //Create an Elgg entity
            $scope.fileInfo[data.fileIndex]['elggId'] = Elgg.addElggEntity($scope.fileInfo[data.fileIndex]);
        }
        else {
            $scope.fileInfo[data.fileIndex]['elggId'] = Elgg.uploadElggFile($scope.fileInfo[data.fileIndex], jQuery(elm), data, $scope);
            $scope.fileInfo[data.fileIndex]['elggId'].then(
                function() {
                    //TODO finish album Selector - Currently we are returning Album array - I can't translate it.
                    var albums = Elgg.albumSelector($scope.fileInfo[data.fileIndex]);
                    albums.then(function(){
                        console.log(albums);
                    })
                    $scope.fileInfo[data.fileIndex]['updateResult'] = true;
                })
        };

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
     * Update an entry using its file info.
     * @param index the index of the entry in the file info object.
     */
    $scope.updateEntry = function(index) {
        var updateResult = Kaltura.baseEntryUpdate($scope.fileInfo[index]);
        var elggUpdateResult = Elgg.updateElggEntity($scope.fileInfo[index]);
        // Disable the button.
        $scope.fileInfo[index]['updateResult'] = false;

        // Re-enable the button after a successful update.
        elggUpdateResult.then (function(){
            updateResult.then(function(){
                    $scope.fileInfo[index]['updateResult'] = true;
                }
            )}
        );
    }

    /**
     * Update an entry using its file info.
     * @param index the index of the entry in the file info object.
     */
    $scope.deleteEntry = function(index) {
        var updateResult = Kaltura.baseEntryDelete($scope.fileInfo[index]);
        var elggUpdateResult = Elgg.deleteElggEntity($scope.fileInfo[index]);
        // Disable the button.
        $scope.fileInfo[index]['updateResult'] = false;

        // Re-enable the button after a successful update.
        elggUpdateResult.then (function(){
                updateResult.then(function(){
                        $scope.fileInfo[index]['updateResult'] = true;
                    }
                )}
        );
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
     * Returns true iff metadata can be currently saved.
     * @param index the index of the entry in the file info object.
     * @returns true iff update currently possible.
     */
    $scope.canSaveMetadata = function(index) {
        return $scope.fileInfo[index]['updateResult'];
    };

    /**
     * Returns true iff metadata can be currently saved.
     * @param index the index of the entry in the file info object.
     * @returns true iff update currently possible.
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
            $scope.fileInfo[data.fileIndex]['progress'] = progress;
            $scope.$apply();
        });

        jQuery(elm).bind('fileuploaddone', function(e, data) {


        });
    };

    // Initialize the jQuery uploader.
    $scope.initializeUploader($scope.uploaderElement);

    // Set the Kaltura service ks.
    Kaltura.setConfig(config);
}