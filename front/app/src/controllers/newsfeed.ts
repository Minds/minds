import {Component, View} from 'angular2/angular2';
import {Inject} from 'angular2/di';
import {Api} from 'src/services/api';

@Component({
  selector: 'minds-newsfeed',
  viewInjector: [Api]
})
@View({
  templateUrl: 'templates/newsfeed/list.html'
})

export class Newsfeed {

	constructor(public api: Api){
		this.load();
	}

	load(){
		this.api.get();
	}
}