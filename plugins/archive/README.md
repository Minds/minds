Archive | Minds
======

The Archive is a plugin which provides a repository for users to upload and share media files, such as images and videos. 

##UPLOADING

The uploader is powered by an angularjs app. The general flow of the upload process is as follows.

    1) Temporary unique batch ID created for upload

    2) All uploads in this session are linked to the batch

    3) After upload is complete (user clicks done), the batch process allocates the uploaded items to the correct album. _If no album is selected then a generic upload album is created and used_.



The purpose of the Batch entity is to allow for bulk changes to new entities, and to allow for pre-published editing. 

## Videos
We use the Cinemr transcoding service to handle our video resources. Cinemr is configured to use AWS's Elastic Transcoding Service.

## Images/Photos
