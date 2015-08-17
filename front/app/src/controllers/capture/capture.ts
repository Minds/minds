import { Component, View } from 'angular2/angular2';
import { Upload } from 'src/services/api/upload';


@Component({
  selector: 'minds-capture',
  viewBindings: [ Upload ]
})
@View({
  template: 'this is capture'
})

export class Capture {

	constructor(public upload: Upload){
		console.log("this is the capture");
	}

  uploadFile(){
    this.upload.post('api/v1/archive', this.postMeta)
				.then((response) => {
					console.log(response);
				})
				.catch(function(e){
					console.error(e);
				});
  }

}
