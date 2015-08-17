import { Component, View, NgIf, NgFor, NgClass, EventEmitter } from 'angular2/angular2';
import { RouterLink } from 'angular2/router';
import { Factory, LoggedIn } from 'src/services/events';
import { Navigation as NavigationService } from 'src/services/navigation';
import { SessionFactory } from 'src/services/session';

@Component({
  selector: 'minds-navigation',
  viewBindings: [NavigationService]
})
@View({
  templateUrl: 'templates/components/navigation.html',
  directives: [RouterLink, NgIf, NgFor, NgClass]
})

export class Navigation {
	user;
	session = SessionFactory.build();
	items;
	constructor(public navigation : NavigationService){
		var self = this;
    this.items = navigation.getItems();
		//Factory.build(LoggedIn).listen(()=>{
		//	console.log('receieved session event');
		//	this.getUser();
		//})
		this.getUser();

		//listen to click events to close nav
	}

	getUser(){
		var self = this;
		this.user = this.session.getLoggedInUser((user) => {
			console.log(user);
				self.user = user;
			});
	}
}
