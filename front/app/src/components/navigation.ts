import {Component, View, NgIf, NgFor, EventEmitter} from 'angular2/angular2';
import {RouterLink} from 'angular2/router';
import {Factory, LoggedIn} from 'src/services/events';

@Component({
  selector: 'minds-navigation'
})
@View({
  templateUrl: 'templates/components/navigation.html',
  directives: [RouterLink, NgIf, NgFor]
})

export class Navigation { 
	user;

	constructor(){
		self = this;
		//Factory.build(LoggedIn).listen(()=>{
		//	console.log('receieved session event');
		//	this.getUser();
		//})
		this.getUser();
	}
	
	getUser(){

		//Factory.build(LoggedIn).emit("ok");
		
		if(window.Minds.user){
			this.user = window.Minds.user;
		}
	}
}