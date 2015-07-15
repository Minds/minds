import {Inject, Injector, bind, Http, Headers} from 'angular2/angular2';
import {Cookie} from 'src/services/cookie';

/**
 * API Class
 */
export class Client {
	base : string = "/";
	cookie : Cookie = new Cookie();
	constructor(@Inject(Http) public http : Http){ }

	private buildParams(object : Object){
		return Object.keys(object).map((k) => {
			return encodeURIComponent(k) + "=" + encodeURIComponent(object[k]);
		}).join('&');
	}

	/**
	 * Build the options
	 */
	private buildOptions(options : Object){
		var XSRF_TOKEN = this.cookie.get('XSRF-TOKEN');
		var headers = new Headers();
		headers.append('X-XSRF-TOKEN', XSRF_TOKEN);
		return Object.assign(options, {
					headers: headers,
					cache: true
				});
	}

	/**
	 * Return a GET request
	 */
	get(endpoint : string, data : Object = {}, options: Object = {}){
		var self = this;
		endpoint += "?" + this.buildParams(data);
		return new Promise((resolve, reject) => {
			self.http.get(
					self.base + endpoint,
					this.buildOptions(options)
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

	/**
	 * Return a POST request
	 */
	post(endpoint : string, data : Object = {}, options: Object = {}){
		var self = this;
		return new Promise((resolve, reject) => {
			self.http.post(
					self.base + endpoint,
					JSON.stringify(data),
					this.buildOptions(options)
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

	/**
	 * Return a DELETE request
	 */
	delete(endpoint : string, data : Object = {}, options: Object = {}){
		var self = this;
		return new Promise((resolve, reject) => {
			self.http.delete(
					self.base + endpoint,
					this.buildOptions(options)
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
