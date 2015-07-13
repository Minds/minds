import {Component, View, NgIf, NgFor, CSSClass, EventEmitter} from 'angular2/angular2';
import {RouterLink} from 'angular2/router';
import {Factory, LoggedIn} from 'src/services/events';
import { Navigation as NavigationService } from 'src/services/navigation';

@Component({
  selector: 'minds-navigation',
  viewInjector: [NavigationService]
})
@View({
  templateUrl: 'templates/components/navigation.html',
  directives: [RouterLink, NgIf, NgFor, CSSClass]
})

export class Navigation { 
	user;
	items = navigation.getItems();
	constructor(public navigation : NavigationService){
		self = this;
		//Factory.build(LoggedIn).listen(()=>{
		//	console.log('receieved session event');
		//	this.getUser();
		//})
		this.getUser();
		
		//listen to click events to close nav
	}
	
	getUser(){

		//Factory.build(LoggedIn).emit("ok");
		
		if(window.Minds.user){
			this.user = window.Minds.user;
		}
	}
}