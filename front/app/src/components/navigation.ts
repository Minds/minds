import {Component, View, NgIf, NgFor} from 'angular2/angular2';
import {RouterLink} from 'angular2/router';

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
	}
	
	getUser(){
		if(window.Minds.user){
			this.user = window.Minds.user;
		}
	}
}