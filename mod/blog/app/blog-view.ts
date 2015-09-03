import { Component, View, NgFor, NgIf, NgClass, Observable, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { MessengerConversation } from "./messenger-conversation";
import { MessengerSetup } from "./messenger-setup";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-blog-view',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/blog/view.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, MessengerConversation, MessengerSetup]
})

export class Gatherings {
  activity : any;
  session = SessionFactory.build();
  setup : boolean = false;

	constructor(public client: Client){
	}

}
