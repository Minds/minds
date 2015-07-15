import { Component, View, NgFor, NgIf } from 'angular2/angular2';
import { Client } from 'src/services/api';

@Component({
  selector: 'minds-newsfeed',
  viewInjector: [ Client ]
})
@View({
  templateUrl: 'templates/newsfeed/list.html',
  directives: [ NgFor, NgIf ]
})

export class Newsfeed {

	newsfeed : Array<Object> = [];
	offset : string = "";

	constructor(public client: Client){
		this.load();
	}

	/**
	 * Load newsfeed
	 */
	load(){
		var self = this;
		this.client.get('api/v1/newsfeed', {limit:12}, {cache: true})
				.then(function(data : MindsActivityObject){
					if(!data.activity){
						return false;
					}
					self.newsfeed = data.activity;
					self.offset = data['load-next'];
				})
				.catch(function(e){
					console.log(e);
				});
	}

	/**
	 * Post to the newsfeed
	 */
	post(message){
		var self = this;
		this.client.post('api/v1/newsfeed', {message: message})
				.then(function(data){
					self.load();
				})
				.catch(function(e){
					console.log(e);
				});
	}

  /**
   * Get rich embed data
   */
  getPostPreview(message){
    console.log("you said " + message.value);
  }

	/**
	 * A temporary hack, because pipes don't seem to work
	 */
	toDate(timestamp){
		return new Date(timestamp*1000);
	}
}
