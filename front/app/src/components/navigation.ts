import {Component, View, NgIf, NgFor, EventEmitter} from 'angular2/angular2';
import {RouterLink} from 'angular2/router';
import {LoggedIn} from 'src/services/events';

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
		this.getUser();
		LoggedIn.listen(()=>{
			console.log('got loggedin event');
		});
	}
	
	getUser(){

		LoggedIn.emit();
		
		if(window.Minds.user){
			this.user = window.Minds.user;
		}
	}
}