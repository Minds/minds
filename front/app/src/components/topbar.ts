import {Component, View, NgIf} from 'angular2/angular2';
import {Storage} from 'src/services/storage';
import {LoggedIn} from 'src/directives/loggedin';

@Component({
  selector: 'minds-topbar',
  viewInjector: [Storage]
})
@View({
  templateUrl: 'templates/components/topbar.html',
  directives: [NgIf]
})

export class Topbar { 
	constructor(public storage: Storage){ }
}