import { Component, View, NgIf} from 'angular2/angular2';
import { RouterLink } from 'angular2/router';
import { Material } from 'src/directives/material';
import { Storage } from 'src/services/storage';
import { Sidebar } from 'src/services/ui/sidebar';
import { SessionFactory } from 'src/services/session';

@Component({
  selector: 'minds-topbar',
  viewInjector: [ Storage, Sidebar ]
})
@View({
  templateUrl: 'templates/components/topbar.html',
  directives: [ NgIf, RouterLink, Material ]
})

export class Topbar {
	loggedin = false;
	session = SessionFactory.build();

	constructor(public storage: Storage, public sidebar : Sidebar){
		this.showLogin();
	}

	/**
	 * Determine if login button should be shown
	 */
	showLogin(){
		var self = this;
		this.loggedin = this.session.isLoggedIn((loggedin) => {
			console.log(loggedin)
			self.loggedin = loggedin;
			});
	}

	/**
	 * Open the navigation
	 */
	openNav(){
		this.sidebar.open();
	}
}
