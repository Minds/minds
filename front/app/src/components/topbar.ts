import {Component, View, NgIf} from 'angular2/angular2';
import {Storage} from 'src/services/storage';

@Component({
  selector: 'minds-topbar',
  viewInjector: [Storage]
})
@View({
  templateUrl: 'templates/components/topbar.html',
  directives: [NgIf]
})

export class Topbar { 
	constructor(public storage: Storage){
		
	}
	isLoggedIn(){
		console.log('checking ng-if');
		if(this.storage.get('loggedin'))
			return true;
		return false;
	}
}