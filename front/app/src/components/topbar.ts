import { Component, View, NgIf } from 'angular2/angular2';
import { RouterLink } from 'angular2/router';
import { Storage } from 'src/services/storage';

@Component({
  selector: 'minds-topbar',
  viewInjector: [Storage]
})
@View({
  templateUrl: 'templates/components/topbar.html',
  directives: [NgIf, RouterLink]
})

export class Topbar { 
	constructor(public storage: Storage){ }
	
	/**
	 * Determine if login button should be shown
	 */
	showLogin(){
		return !window.LoggedIn;
	}
}