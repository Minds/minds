import {Component, View} from 'angular2/angular2';
import {Inject} from 'angular2/di';
import {Api} from 'src/services/api';

@Component({
  viewInjector: [Api]
})
@View({
  templateUrl: 'templates/login.html'
})

export class Login {

	constructor(public api: Api){
		
	}

	login(username, password){
		alert("trying to login");
		console.log(username);
		console.log(password);
	}
}