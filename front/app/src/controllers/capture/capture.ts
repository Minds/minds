import { Component, View, FORM_DIRECTIVES } from 'angular2/angular2';
import {Http, Headers} from 'http/http';

import { Upload } from 'src/services/api/upload';


@Component({
  selector: 'minds-capture',
  viewBindings: [ Upload, Http ]
})
@View({
  templateUrl: 'templates/capture/capture.html',
  directives: [FORM_DIRECTIVES]
})

export class Capture {

  postMeta : Object = {};

	constructor(public upload: Upload, public http: Http){
		console.log("this is the capture");
	}

  uploadFile(){
    console.log('called');
    console.log(this.postMeta);

    this.upload.post('api/v1/archive', this.postMeta)
				.then((response) => {
					console.log(response);
				})
				.catch(function(e){
					console.error(e);
				});
  }

}
