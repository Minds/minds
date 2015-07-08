import {Inject, Http} from 'angular2/angular2';

/**
 * API Class
 */
export class Api {
	constructor(){
		
	}
	get(){
		//console.log(Http)
		console.log('you ask, you get');
	}
}

export class OAuth {
	constructor(@Inject(Http) public http: Http){ }
	result;
	login(username, password){
		var http = this.http; //that <=> this
		return new Promise(function(resolve, reject){

			/**
			 * Fragile.. api always changing
			 */
			var request = http.post('https://www.minds.com/oauth2/token', 
							{ 
								grant_type:'password',
								username: username, 
								password: password
							},
							{
								headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }
							})
							.toRx()
							.map(res => res.json())
							.subscribe(function(res){
								resolve(true);
								console.log(res);
							});

		});
	}

}