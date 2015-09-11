import { Component, View, CORE_DIRECTIVES, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from 'src/directives/infinite-scroll';
import { UserCard } from 'src/controllers/cards/cards';

@Component({
  selector: 'minds-groups-profile-requests',
  viewBindings: [ Client ],
  properties: ['_group : group']
})
@View({
  templateUrl: 'templates/plugins/groups/profile/requests.html',
  directives: [ CORE_DIRECTIVES, FORM_DIRECTIVES, Material, RouterLink, InfiniteScroll, UserCard ]
})

export class GroupsProfileRequests {

  group : any;
  session = SessionFactory.build();

  users : Array<any> = [];
  offset : string = "";
  inProgress : boolean = false;
  moreData : boolean = true;

	constructor(public client: Client){

	}

  set _group(value : any){
    this.group = value;
    this.load();
  }

  load(refresh : boolean = false){
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/groups/membership/' + this.group.guid + '/requests', { limit: 12, offset: this.offset })
      .then((response : any) => {

        if(!response.users){
          self.moreData = false;
          self.inProgress = false;
          return false;
        }

        if(self.users && !refresh){
          for(let user of response.users)
            self.users.push(user);
        } else {
             self.users = response.users;
        }
        self.offset = response['load-next'];
        self.inProgress = false;

      })
      .catch((e)=>{

      });
  }

}
