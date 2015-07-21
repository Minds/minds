import { Component, View, Inject } from 'angular2/angular2';
import { Router } from 'angular2/router';
import { Material } from 'src/directives/material';
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';

@Component({
  viewInjector: [ Client ]
})
@View({
  templateUrl: 'templates/login.html',
  directives: [ Material ]
})

export class Login {

	session = SessionFactory.build();

	constructor(public client : Client, @Inject(Router) public router: Router){
		window.componentHandler.upgradeDom();
	}

	login(username, password){
		var self = this; //this <=> that for promises
		this.client.post('api/v1/authenticate', {username: username.value, password: password.value})
			.then(function(data : any){
				username.value = '';
				password.value = '';
				if(data.status == 'success'){
					self.session.login(data.user);
					self.router.parent.navigate('/newsfeed');
				} else {
					self.session.logout();
				}
			})
			.catch(function(e){
				alert('there was a problem');
				console.log(e);
				self.session.logout();
			});
	}
}
