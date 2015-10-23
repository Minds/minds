import { Component, View, CORE_DIRECTIVES, FORM_DIRECTIVES } from 'angular2/angular2';
import { Router } from 'angular2/router';

import { LICENSES, ACCESS } from 'src/services/list-options';
import { MindsTitle } from 'src/services/ux/title';
import { SessionFactory } from 'src/services/session';
import { MDL_DIRECTIVES } from 'src/directives/material';
import { Upload } from 'src/services/api/upload';
import { Client } from 'src/services/api/client';

@Component({
  selector: 'minds-capture',
  viewBindings: [ Upload, Client ],
  bindings: [ MindsTitle ],
  host : {
    '(dragover)': 'dragover($event)',
    '(dragleave)': 'dragleave($event)',
    '(drop)': 'drop($event)'
  }
})
@View({
  templateUrl: 'templates/capture/capture.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, MDL_DIRECTIVES ]
})

export class Capture {

  session = SessionFactory.build();
  uploads : Array<any> = [];

  postMeta : any = {}; //TODO: make this object

  albums : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;

  dragging : boolean = false;

  control;

  default_license : string = "all-rights-reserved";
  licenses = LICENSES;
  access = ACCESS;
	constructor(public _upload: Upload, public client: Client, public router: Router, public title: MindsTitle){
    if(!this.session.isLoggedIn()){
      router.navigate(['/Login']);
    } else {
      this.domListeners();
      this.getAlbums();
    }

    this.title.setTitle("Capture");
	}

  domListeners(){

  }

  getAlbums(){
    var self = this;
    this.client.get('api/v1/entities/owner/albums', { limit: 5, offset: this.offset })
      .then((response : any) => {
        if(!response.entities)
          return;
        console.log(response);
        self.albums = response.entities;
      })
      .catch((e) => {

      });
  }

  createAlbum(album){
    var self = this;
    this.inProgress = true;
    this.client.post('api/v1/archive/albums', { title: album.value })
      .then((response : any) => {
        self.albums.unshift(response.album);
        self.postMeta.album_guid = response.album.guid;
        self.inProgress = false;
        album.value = '';
      })
      .catch((e) => {

      });
  }

  selectAlbum(album){
    this.postMeta.album_guid = album.guid;
  }

  /**
   * Add a file to the upload queue
   */
  add(file : any){
    var self = this;

    for(var i = 0; i < file.files.length; i++){

      var data : any = {
        guid: null,
        state: 'created',
        progress: 0,
        license: this.default_license || 'all-rights-reserved'
      }

      var fileInfo = file.files[i];

      if(fileInfo.type && fileInfo.type.indexOf('image') > -1){
        data.type = "image";
      } else if(fileInfo.type && fileInfo.type.indexOf('video') > -1){
        data.type = "video";
      } else if(fileInfo.type && fileInfo.type.indexOf('audio') > -1){
        data.type = "audio";
      } else {
        data.type = "unknown";
      }

      data.name = fileInfo.name;

      var upload_i = this.uploads.push(data) - 1;
      this.uploads[upload_i].index = upload_i;

      this.upload(this.uploads[upload_i], fileInfo);

    }

  }

  upload(data, fileInfo){
    var self = this;
    this._upload.post('api/v1/archive', [fileInfo], this.uploads[data.index], (progress) => {
        self.uploads[data.index].progress = progress;
        if(progress == 100){
          self.uploads[data.index].state = 'uploaded';
        }
      })
      .then((response : any) => {
        self.uploads[data.index].guid = response.guid;
        self.uploads[data.index].state = 'complete';
        self.uploads[data.index].progress = 100;
      })
      .catch(function(e){
        self.uploads[data.index].state = 'failed';
        console.error(e);
      });
  }

  modify(index){
    var self = this;
    //we don't always have a guid ready, so keep checking for one
    var promise = new Promise((resolve, reject) => {
      if(self.uploads[index].guid){
        resolve();
        return;
      }
      var interval = setInterval(() => {
        if(self.uploads[index].guid){
          resolve();
          clearInterval(interval);
        }
      }, 1000);
    });
    promise.then(() => {
      self.client.post('api/v1/archive/' + self.uploads[index].guid, self.upload[index])
        .then((response : any) => {
          console.log('response from modify', response);
        });
    });
  }

  /**
   * Publish our uploads to an album
   */
  publish(){
    if(!this.postMeta.album_guid)
      return alert('You must select an album first');
    var self = this;
    var guids = this.uploads.map((upload) => {
      if(upload.guid != null || upload.guid != 'null' || !upload.guid)
        return upload.guid;
    });
    this.client.post('api/v1/archive/albums/' + this.postMeta.album_guid, { guids: guids })
      .then((response : any) => {
        self.router.navigate(['/Archive-View', {guid: this.postMeta.album_guid}]);
      })
      .catch((e) => {
          alert("there was a problem.");
      });
  }

  /**
   * Make sure the browser doesn't freak
   */
  dragover(e){
    e.preventDefault();
    this.dragging = true;
  }

  /**
   * Tell the app we have stopped dragging
   */
  dragleave(e){
    e.preventDefault();
    console.log(e);
    if(e.layerX < 0)
      this.dragging = false;
  }

  drop(e){
    e.preventDefault();
    this.dragging = false;
    this.add(e.dataTransfer);
  }

}
