import { Component, View, NgFor, NgIf, NgClass, Observable} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { Remind } from './remind';
import { Boost } from './boost';

@Component({
  selector: 'minds-activity',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/cards/activity.html',
  directives: [ Boost, NgFor, NgIf, NgClass, Material, Remind, RouterLink]
})

export class Activity {

  activity : any;
  menuToggle : boolean = false;
  session = SessionFactory.build();
  showBoostOptions : boolean = false;

	constructor(public client: Client){
	}

  set object(value: any) {
    this.activity = value;
    if(!this.activity['thumbs:up:user_guids'])
      this.activity['thumbs:up:user_guids'] = [];
    if(!this.activity['thumbs:down:user_guids'])
      this.activity['thumbs:down:user_guids'] = [];
  }

  delete(){
    this.client.delete('api/v1/newsfeed/'+this.activity.guid);
    delete this.activity;
  }

  openMenu(){
    this.menuToggle = !this.menuToggle;
    console.log(this.menuToggle);
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

          });
  }

  /**
   * Has thumbed up
   */
  hasThumbedUp(){
    for(var guid of this.activity['thumbs:up:user_guids']){
      if(guid == this.session.getLoggedInUser().guid)
        return true;
    }
    return false;
  }

  hasThumbedDown(){
    for(var guid of this.activity['thumbs:down:user_guids']){
      if(guid == this.session.getLoggedInUser().guid)
        return true;
    }
    return false;
  }

  hasReminded(){
    return false;
  }

  showBoost(){
      this.showBoostOptions = true;
  }
}
