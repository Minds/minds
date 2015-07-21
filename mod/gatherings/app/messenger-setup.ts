import { Component, View, NgFor, NgIf, CSSClass, Observable, formDirectives} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-messenger-setup',
  viewInjector: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/gatherings/messenger-setup.html',
  directives: [ NgFor, NgIf, CSSClass, Material, formDirectives]
})

export class MessengerSetup {
  session = SessionFactory.build();

	constructor(public client: Client){
	}

  setup(passwords){
    console.log(passwords);
    passwords.value = {};
    return true;
  }

}
