import { Component, View, NgFor, NgIf, CSSClass, Observable, formDirectives} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { Remind } from './remind';

@Component({
  selector: 'minds-activity',
  viewInjector: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/entities/activity.html',
  directives: [ NgFor, NgIf, CSSClass, Material, Remind, RouterLink]
})

export class Activity {
  activity : any;
  session = SessionFactory.build();

	constructor(public client: Client){
	}

  set object(value: any) {
    this.activity = value;
    if(!this.activity['thumbs:up:user_guids'])
      this.activity['thumbs:up:user_guids'] = [];
    if(!this.activity['thumbs:down:user_guids'])
      this.activity['thumbs:down:user_guids'] = [];
  }

	/**
	 * A temporary hack, because pipes don't seem to work
	 */
	toDate(timestamp){
		return new Date(timestamp*1000);
	}

  thumbsUp(){
    this.client.put('api/v1/thumbs/' + this.activity.guid + '/up', {});
    if(!this.hasThumbedUp()){
      this.activity['thumbs:up:user_guids'].push(this.session.getLoggedInUser().guid);
    } else {
      for(let key in this.activity['thumbs:up:user_guids']){
        if(this.activity['thumbs:up:user_guids'][key] == this.session.getLoggedInUser().guid)
          delete this.activity['thumbs:up:user_guids'][key];
      }
    }
  }

  thumbsDown(){
    this.client.put('api/v1/thumbs/' + this.activity.guid + '/down', {});
    if(!this.hasThumbedDown()){
      this.activity['thumbs:down:user_guids'].push(this.session.getLoggedInUser().guid);
    } else {
      for(let key in this.activity['thumbs:down:user_guids']){
        if(this.activity['thumbs:down:user_guids'][key] == this.session.getLoggedInUser().guid)
          delete this.activity['thumbs:down:user_guids'][key];
      }
    }
  }

  remind(){
    let self = this;
    this.client.post('api/v1/newsfeed/remind/' + this.activity.guid, {})
          .then((data)=> {
              alert('reminded');
          });
  }

  /**
   * Has thumbed up
   */
  hasThumbedUp(){
    if(this.activity['thumbs:up:user_guids'].indexOf(this.session.getLoggedInUser().guid) > -1)
      return true;
    return false;
  }

  hasThumbedDown(){
    if(this.activity['thumbs:down:user_guids'].indexOf(this.session.getLoggedInUser().guid) > -1)
      return true;
    return false;
  }

  hasReminded(){
    return false;
  }
}
