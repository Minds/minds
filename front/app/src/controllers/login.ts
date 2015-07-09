import {Component, View} from 'angular2/angular2';
import {Router} from 'angular2/router';
import {OAuth} from 'src/services/api';
import {Inject} from 'angular2/di';

@Component({
  viewInjector: [OAuth]
})
@View({
  templateUrl: 'templates/login.html'
})

export class Login {

	constructor(public oauth: OAuth, @Inject(Router) public router: Router){
		
	}

	login(username, password){
		var that = this; //this <=> that for promises
		this.oauth.login(username, password)
			.then(function(){
				console.log(this.router);
				that.router.parent.navigate('/newsfeed');
			})
			.catch(function(e){
				alert('there was a problem');
				console.log(e);
			});
	}
}