import {Component, View, NgFor} from 'angular2/angular2';
import {Client} from 'src/services/api';

@Component({
  selector: 'minds-newsfeed',
  viewInjector: [ Client ]
})
@View({
  templateUrl: 'templates/newsfeed/list.html',
  directives: [ NgFor ]
})

export class Newsfeed {

	newsfeed : Array;
	offset : String = "";

	constructor(public client: Client){
		this.load();
	}

	/**
	 * Load newsfeed
	 */
	load(){
		var self = this;
		this.client.get('api/v1/newsfeed', {limit:12}, {cache: true})
				.then(function(data){
					self.newsfeed = data.activity
					self.offset = data['load-next'];
				})
				.catch(function(e){
					console.log(e);
				});
	}
}