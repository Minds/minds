import { Component, View, NgFor, FORM_DIRECTIVES } from 'angular2/angular2';
import { Http, Headers } from 'http/http';

import { Upload } from 'src/services/api/upload';
import { Client } from 'src/services/api/client';

@Component({
  selector: 'minds-capture',
  viewBindings: [ Upload, Client, Http ]
})
@View({
  templateUrl: 'templates/capture/capture.html',
  directives: [ NgFor, FORM_DIRECTIVES ]
})

export class Capture {

  uploads : Array<any> = [];
  postMeta : any = {}; //TODO: make this object

	constructor(public upload: Upload, public client: Client, public http: Http){
    this.domListeners();
	}

  domListeners(){

  }

  uploadFile(){
    var self = this;
    var file : any = {
      guid: null,
      state: 'created',
      progress: 0
    }
    var fileInfo = document.getElementById("file").files[0];

    if(fileInfo.type.indexOf('image') > -1){
      file.type = "image";
    } else if(fileInfo.type.indexOf('video') > -1){
      file.type = "video";
    } else if(fileInfo.type.indexOf('audio') > -1){
      file.type = "audio";
    } else {
      file.type = "unknown";
    }

    file.name = fileInfo.name;
    file.file = fileInfo;

    let index = this.uploads.push(file) - 1;

    this.upload.post('api/v1/archive', this.uploads[index], (progress) => {
      console.log('progress update');
      self.uploads[index].progress = progress;
      })
				.then((response : any) => {
          console.log(response, response.guid);
          self.uploads[index].guid = response.guid;
          self.uploads[index].state = 'uploaded';
          self.uploads[index].progress = 100;
				})
				.catch(function(e){
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

}
