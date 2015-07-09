import {Component, View, Inject} from 'angular2/angular2';
import {Router} from 'angular2/router';
import {Client} from 'src/services/api';

@Component({
  viewInjector: [Client]
})
@View({
  template: "Logging out.."
})

export class Logout {

	constructor(public client : Client, @Inject(Router) public router: Router){
		this.logout();
	}

	logout(){
		//@todo send DELETE to authentication endpoint
		this.router.navigate('/login');
		window.LoggedIn = false;
		this.router.parent.navigate('/login');
		this.client.delete('api/v1/authenticate');
	}
}