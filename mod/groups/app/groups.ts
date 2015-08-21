import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';

import { GroupsCreator } from './groups-creator';

@Component({
  selector: 'minds-groups',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/groups/groups.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink ]
})

export class Groups {

  offset : string = "";
  session = SessionFactory.build();
  _filter : string = "featured";

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
    ){
      this._filter = params.params['filter'];
  }

  load(){
    this.client.get('api/v1/groups/' + this.page, { limit: 12, offset: this.offset})
      .then((response) => {

      })
      .catch((e)=>{

      });
  }

}

export { GroupsProfile } from './groups-profile';
export { GroupsCreator } from './groups-creator';
