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
  errorMessage : string = "";
  twofactorToken : string = "";
  hideLogin : boolean = false;

	constructor(public client : Client, @Inject(Router) public router: Router){
		window.componentHandler.upgradeDom();
	}

	login(username, password){
    this.errorMessage = "";
		var self = this; //this <=> that for promises
		this.client.post('api/v1/authenticate', {username: username.value, password: password.value})
			.then((data : any) => {
				username.value = '';
				password.value = '';

				self.session.login(data.user);
				self.router.parent.navigate('/newsfeed');
			})
			.catch((e) => {
        if(e.status == 'failed'){
          //incorrect login details
          self.errorMessage = "Incorrect username/password. Please try again.";
          self.session.logout();
        }

        if(e.status == 'error'){
          //two factor?
          self.twofactorToken = e.message;
          self.hideLogin = true;
        }

			});
	}

  twofactorAuth(code){
    var self = this;
    this.client.post('api/v1/authenticate/two-factor', {token: this.twofactorToken, code: code.value})
        .then((data : any) => {
          self.session.login(data.user);
          self.router.parent.navigate('/newsfeed');
        })
        .catch((e) => {
          self.errorMessage = "Sorry, we couldn't verify your two factor code. Please try logging again.";
          self.twofactorToken = "";
          self.hideLogin = false;
        });
  }
}
