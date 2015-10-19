import { Component, View, CORE_DIRECTIVES } from 'angular2/angular2';
import { Router, RouteParams } from 'angular2/router';
import { Material } from 'src/directives/material';
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';

@Component({
  selector: 'minds-register',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/forgot-password.html',
  directives: [ CORE_DIRECTIVES, Material ]
})

export class ForgotPassword {

	session = SessionFactory.build();
  error : string = "";
  inProgress : boolean = false;
  step : number = 1;

  username : string = "";
  code : string = "";

	constructor(public client : Client, public router: Router, public params: RouteParams){
    if(params.params['code']){
      this.setCode(params.params['code']);
      this.username = params.params['username'];
    }
	}

	request(username){
    this.error = "";
    this.inProgress = true;
		var self = this;
		this.client.post('api/v1/forgotpassword/request', {
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

  setCode(code : string){
    this.step = 3;
    this.code = code;
  }

  reset(password){
    var self = this;
    this.client.post('api/v1/forgotpassword/reset', {
        password: password.value,
        code: this.code,
        username: this.username
      })
      .then((response : any) => {
        self.session.login(response.user);
      })
      .catch((e) => {
        self.error = e.message;
        setTimeout(() => {
          self.router.navigate(['/Login']);
        }, 2000);
      });
  }

}
