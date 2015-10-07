import { Component, View, NgFor, NgIf, NgClass, Observable} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { Activity } from './activity';
import { TagsLinks } from 'src/directives/tags';
import { TagsPipe } from 'src/pipes/tags';

@Component({
  selector: 'minds-remind',
  viewBindings: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/cards/activity.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, TagsLinks],
  pipes: [ TagsPipe ]
})

export class Remind {
  activity : any;
  hideTabs : boolean;
  session =  SessionFactory.build();

	constructor(public client: Client){
    this.hideTabs = true;
	}

  set object(value: any) {
    this.activity = value;
  }

  toDate(timestamp){
    return new Date(timestamp*1000);
  }
}
