import {Inject, Injector, bind, Http} from 'angular2/angular2';
import {Storage} from 'src/services/storage';

var injector = Injector.resolveAndCreate([
	bind(Storage).toClass(Storage)
]);

export class OAuth {
	
	client_id : String = '421672819009523712';

	constructor(@Inject(Http) http){
		this.storage = injector.get(Storage);
	}
	
	buildParams(params){
		return Object.assign(params, {
				'client_id': this.client_id,
				'access_token': this.storage.get('access_token')
				});
	}

	login(username, password){
		var that = this;
		var http = this.http; //that <=> this
		return new Promise((resolve, reject) => {

			/**
			 * Fragile.. api always changing
			 */
			var request = http.post('https://www.minds.com/oauth2/token', 
							JSON.stringify({ 
								grant_type:'password',
								client_id:  that.client_id,
								client_secret: '68a8f432807541549ed3e95ffd22752c',
								username: username, 
								password: password
							}))
							.toRx()
							//.map(res => res.json())
							.subscribe(res => {
									if(res.status != 200){
										return reject("Header: " + status);
									}
									var data = res.json();
									if(!data.access_token){
										return reject("No access token");
									}
									this.storage.set("loggedin", true);
									this.storage.set("access_token", data.access_token);
									this.storage.set("user_guid", data.user_id);
									resolve(true);
							});

		});
	}

}