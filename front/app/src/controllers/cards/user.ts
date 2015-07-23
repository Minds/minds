import { Component, View, NgFor, NgIf, CSSClass, Observable, formDirectives} from 'angular2/angular2';
import { RouterLink } from "angular2/router";
import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-card-user',
  viewInjector: [ Client ],
  properties: ['object']
})
@View({
  templateUrl: 'templates/cards/user.html',
  directives: [ NgFor, NgIf, CSSClass, Material, RouterLink]
})

export class UserCard {
  user : any;
  session = SessionFactory.build();

	constructor(public client: Client){
	}

  set object(value: any) {
    this.user = value;
  }

}
