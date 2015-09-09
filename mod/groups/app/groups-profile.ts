import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { MindsGroupResponse } from 'src/interfaces/responses';
import { MindsGroup } from 'src/interfaces/entities';

@Component({
  selector: 'minds-groups',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/groups/profile.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink ]
})

export class GroupsProfile {

  guid;
  group : MindsGroup;
  offset : string = "";
  session = SessionFactory.build();

	constructor(public client: Client,
    @Inject(RouteParams) public params: RouteParams
    ){
      this.guid = params.params['guid'];
      this.load();
	}

  load(){
    var self = this;
    this.client.get('api/v1/groups/group/' + this.guid, {})
      .then((response : MindsGroupResponse) => {
          self.group = response.group;
      })
      .catch((e)=>{

      });
  }

}
