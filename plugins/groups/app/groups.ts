import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { MindsTitle } from '../../services/ux/title';
import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsGroupListResponse } from '../../interfaces/responses';
import { GroupsCreator } from './create/create';
import { GroupsJoinButton } from './groups-join-button';

@Component({
  selector: 'minds-groups',
  viewBindings: [ Client ],
  bindings: [ MindsTitle ]
})
@View({
  templateUrl: 'src/plugins/groups/groups.html',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, InfiniteScroll, GroupsJoinButton ]
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
    @Inject(RouteParams) public params: RouteParams,
    public title: MindsTitle){
      this._filter = params.params['filter'];
      this.minds = window.Minds;
      this.load();

      this.title.setTitle("Groups");
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

  /**
   * Join a group
   */
  join(group : any){
  //  this.client.post('')

  }

}

export { GroupsProfile } from './profile/profile';
export { GroupsCreator } from './create/create';
