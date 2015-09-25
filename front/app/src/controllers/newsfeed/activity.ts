import { Component, View, CORE_DIRECTIVES, Observable} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { Remind } from './remind';
import { BUTTON_COMPONENTS } from 'src/components/buttons';
import { Boost } from './boost';

@Component({
  selector: 'minds-activity',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/cards/activity.html',
  directives: [ CORE_DIRECTIVES, BUTTON_COMPONENTS, Boost, Material, Remind, RouterLink]
})

export class Activity {

  activity : any;
  menuToggle : boolean = false;
  session = SessionFactory.build();
  showBoostOptions : boolean = false;
  type : string;

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

  showBoost(boostType : string){
      this.activity.boostType = boostType;
      this.showBoostOptions = true;
  }
  closeBoost(){
    this.showBoostOptions = false;
  }

}
