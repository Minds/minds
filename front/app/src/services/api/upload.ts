import {Inject, Injector, bind} from 'angular2/angular2';
import {Http, Headers} from 'http/http';
import {Cookie} from 'src/services/cookie';

/**
 * API Class
 */
export class Upload  {
	base : string = "/";
	cookie : Cookie = new Cookie();
	constructor(@Inject(Http) public http : Http){ }

	/**
	 * Return a POST request
	 */
	post(endpoint : string, files : Array<any> = [], data : any = {}, progress : Function = ()=>{}){
		var self = this;

		var formData = new FormData();
		if(!data.filekey){
			data.filekey = "file";
		}

		for(var file in files)
			formData.append(data.filekey + "[]", file);

		for(var key in data){
			formData.append(key, data[key]);
		}

		return new Promise((resolve, reject) => {
			var xhr = new XMLHttpRequest();
			xhr.open('POST', self.base + endpoint, true);
			xhr.upload.onprogress = (e) => {
				progress(e.loaded/e.total);
			}
			xhr.onload = function(){
    		if (this.status == 200) {
					resolve(this.response);
				} else {
					reject(this.response);
				}
			}
		});
	}

}
