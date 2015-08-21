import { Component, View, NgFor, NgIf, NgClass, Observable, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

@Component({
  selector: 'minds-groups',
  viewInjector: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/groups/profile.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink ]
})

export class GroupsProfile {

  offset : string = "";
  session = SessionFactory.build();

	constructor(public client: Client){
	}

  load(){
    this.client.get('api/v1/groups/' + this.page, { limit: 12, offset: this.offset})
      .then((response) => {

      })
      .catch((e)=>{

      });
  }

}
