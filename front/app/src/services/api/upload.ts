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

		if(files.length > 1){
			for(var file of files)
				formData.append(data.filekey + "[]", file);
		} else {
			formData.append(data.filekey, files[0]);
		}

		delete data.filekey;

		for(var key in data){
			formData.append(key, data[key]);
		}

		return new Promise((resolve, reject) => {
			var xhr = new XMLHttpRequest();
			xhr.open('POST', self.base + endpoint, true);
			xhr.onprogress = function(e){
				progress(e.loaded);
			}
			xhr.onload = function(){
    		if (this.status == 200) {
					resolve(JSON.parse(this.response));
				} else {
					reject(JSON.parse(this.response));
				}
			}
			xhr.onreadystatechange = function(){
				console.log(this);
			}
			var XSRF_TOKEN = this.cookie.get('XSRF-TOKEN');
			xhr.setRequestHeader('X-XSRF-TOKEN', XSRF_TOKEN);
			xhr.send(formData);
		});
	}

}
