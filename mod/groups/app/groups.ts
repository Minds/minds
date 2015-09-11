import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsGroupListResponse } from 'src/interfaces/responses';
import { GroupsCreator } from './groups-creator';

@Component({
  selector: 'minds-groups',
  viewBindings: [ Client ]
})
@View({
  templateUrl: 'templates/plugins/groups/groups.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, InfiniteScroll ]
})

export class Groups {

  minds;

  offset : string = "";
  moreData : boolean = true;
  inProgress : boolean = false;
  groups : Array<any> = [];
  session = SessionFactory.build();
  _filter : string = "featured";

  constructor(public client: Client,
    @Inject(Router) public router: Router,
    @Inject(RouteParams) public params: RouteParams
    ){
      this._filter = params.params['filter'];
      this.minds = window.Minds;
      this.load();
  }

  load(refresh : boolean = false){
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/groups/' + this._filter, { limit: 12, offset: this.offset})
      .then((response : MindsGroupListResponse) => {

        if(!response.groups || response.groups.length == 0){
          self.moreData = false;
          self.inProgress = false;
          return false;
        }

        if(refresh){
          self.groups = response.groups;
        } else {
          if(self.offset)
            response.groups.shift();
          for(let group of response.groups)
            self.groups.push(group);
        }

        self.offset = response['load-next'];
        self.inProgress = false;
      })
      .catch((e)=>{

      });
  }

}

export { GroupsProfile } from './groups-profile';
export { GroupsCreator } from './groups-creator';
