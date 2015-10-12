import {Component, NgIf} from 'angular2/angular2';
import {Storage} from 'src/services/storage';

@Component({
  selector: 'minds-loggedin',
  viewBindings: [Storage]
})

export class LoggedIn { 
	constructor(public storage: Storage){
		
	}
	isLoggedIn(){
		console.log('checking ng-if');
		if(this.storage.get('loggedin'))
			return true;
		return false;
	}
}
