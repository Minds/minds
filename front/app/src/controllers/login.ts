import {Component, View} from 'angular2/angular2';
import {Router} from 'angular2/router';
import {Api, OAuth} from 'src/services/api';
import {Inject} from 'angular2/di';

@Component({
  viewInjector: [Api, OAuth]
})
@View({
  templateUrl: 'templates/login.html'
})

export class Login {

	constructor(public api: Api, public oauth: OAuth, @Inject(Router) public router: Router){
		
	}

	login(username, password){
		//try the oauth login
		this.oauth.login()
			.then(function(){
				this.router.parent.navigate('/newsfeed');
			})
			.catch(function(e){
				alert('there was a problem');
				console.log(e);
			});
	}
}