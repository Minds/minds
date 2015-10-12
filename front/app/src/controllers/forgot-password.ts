import { Component, View, Inject } from 'angular2/angular2';
import { Router } from 'angular2/router';
import { Material } from 'src/directives/material';
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';

@Component({
  selector: 'minds-register',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/forgot-password.html',
  directives: [ Material ]
})

export class ForgotPassword {

	session = SessionFactory.build();
  error : string = "";
  inProgress : boolean = false;
  step : number = 1;

	constructor(public client : Client, @Inject(Router) public router: Router){
		window.componentHandler.upgradeDom();
	}

	request(username){
    this.error = "";
    this.inProgress = true;
		var self = this;
		this.client.post('/api/v1/forgotpassword/', {
        username: username.value
      })
			.then((data : any) => {
				username.value = '';

        this.inProgress = false;
        self.step = 2;
				//self.router.navigate(['/Homepage', {}]);
			})
			.catch((e) => {

        this.inProgress = false;
        if(e.status == 'failed'){
          self.error = "There was a problem trying to reset your password. Please try again.";
        }

        if(e.status == 'error'){
          self.error = e.message;
        }

			});
	}

}
