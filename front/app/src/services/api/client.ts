import {Inject, Injector, bind, Http} from 'angular2/angular2';
import {OAuth} from 'src/services/api/oauth';

/**
 * API Class
 */
export class Client {
	base : String = "https://www.minds.com/";

	constructor(@Inject(Http) public http : Http){ 
		this.oauth = new OAuth(http);
	}
	
	params(object : Object){
		return Object.keys(object).map((k) => {
			return encodeURIComponent(k) + "=" + encodeURIComponent(object[k]);
		}).join('&');
	}

	/**
	 * Return a GET request
	 */
	get(endpoint : String, data : Object, options: Object){
		var self = this;
		var data = this.oauth.buildParams(data);
		console.log(this.params(data));
		endpoint += "?" + this.params(data);
		return new Promise((resolve, reject) => {
			self.http.get(
					self.base + endpoint, 
					options
				)
				.toRx()
				.subscribe(res => {
						if(res.status != 200){
							return reject("Header: " + status);
						}
						var data = res.json();
						return resolve(data);
				});
		});
	}
}