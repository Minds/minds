import { Component, View, NgFor, NgIf, Observable, formDirectives} from 'angular2/angular2';
import { Client } from 'src/services/api';
import { Material } from 'src/directives/material';
import { Remind } from './remind';

@Component({
  selector: 'minds-activity',
  viewInjector: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/entities/activity.html',
  directives: [ NgFor, NgIf, Material, Remind]
})

export class Activity {
  activity : any;

	constructor(public client: Client){
	}

  set object(value: any) {
    this.activity = value;
  }

	/**
	 * A temporary hack, because pipes don't seem to work
	 */
	toDate(timestamp){
		return new Date(timestamp*1000);
	}

  thumbsUp(){
    console.log('you hit the thumbsup for ' + this.activity.guid);
  }

  remind(){
    let self = this;
    this.client.post('api/v1/newsfeed/remind/' + this.activity.guid, {})
          .then((data)=> {
              alert('reminded');
          });
  }
}
