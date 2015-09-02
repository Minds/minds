import { Component, View, NgFor, NgIf, NgClass, Observable} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-messenger-conversation',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/gatherings.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink]
})

export class MessengerConversation {
  activity : any;
  session = SessionFactory.build();

	constructor(public client: Client){
	}

}
