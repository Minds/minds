import {Component, View} from 'angular2/angular2';
import {Router} from 'angular2/router';
import {Client} from 'src/services/api';
import {Inject} from 'angular2/di';

@Component({
  viewInjector: [Client]
})
@View({
  templateUrl: 'templates/login.html'
})

export class Login {

	constructor(public client : Client, @Inject(Router) public router: Router){ }

	login(username, password){
		var that = this; //this <=> that for promises
		this.client.post('api/v1/authenticate', {username: username, password: password})
			.then(function(data){
				if(data.status == 'success'){
					window.LoggedIn = true;
					that.router.parent.navigate('/newsfeed');
				} else {
					window.LoggedIn = false;
				}
			})
			.catch(function(e){
				alert('there was a problem');
				console.log(e);
			});
	}
}