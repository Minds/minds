import { Component, View, Inject } from 'angular2/angular2';
import { Router, RouterLink } from 'angular2/router';
import { Material } from 'src/directives/material';
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Register } from './register';

@Component({
  selector: 'minds-login',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/login.html',
  directives: [ Material, Register, RouterLink]
})

export class Login {

	session = SessionFactory.build();
  errorMessage : string = "";
  twofactorToken : string = "";
  hideLogin : boolean = false;
  inProgress : boolean = false;

	constructor(public client : Client, public router: Router){
		if(this.session.isLoggedIn())
      router.navigate(['/Newsfeed']);
	}

	login(username, password){
    this.errorMessage = "";
    this.inProgress = true;
		var self = this; //this <=> that for promises
		this.client.post('api/v1/authenticate', {username: username.value, password: password.value})
			.then((data : any) => {
				username.value = '';
				password.value = '';
        this.inProgress = false;
				self.session.login(data.user);
				self.router.navigate(['/Newsfeed', {}]);
			})
			.catch((e) => {
        console.log(e);
        this.inProgress = false;
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
          self.router.navigate(['/Newsfeed', {}]);
        })
        .catch((e) => {
          self.errorMessage = "Sorry, we couldn't verify your two factor code. Please try logging again.";
          self.twofactorToken = "";
          self.hideLogin = false;
        });
  }

}
