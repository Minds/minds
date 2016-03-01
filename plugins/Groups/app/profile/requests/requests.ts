import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { RouterLink, RouteParams } from "angular2/router";

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';
import { Material } from '../../../../directives/material';
import { InfiniteScroll } from '../../../../directives/infinite-scroll';
import { UserCard } from '../../../../controllers/cards/cards';


@Component({
  selector: 'minds-groups-profile-requests',
  
  properties: ['_group : group']
})
@View({
  templateUrl: 'src/plugins/Groups/profile/requests/requests.html',
  directives: [ CORE_DIRECTIVES, Material, RouterLink, InfiniteScroll, UserCard ]
})

export class GroupsProfileRequests {

  minds;
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
    this.minds = window.Minds;
  }

  load(refresh : boolean = false){
    var self = this;
    this.inProgress = true;
    this.client.get('api/v1/groups/membership/' + this.group.guid + '/requests', { limit: 12, offset: this.offset })
      .then((response : any) => {

        if(!response.users || response.users.length == 0){
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

  accept(user : any){
    var self = this;
    this.client.put('api/v1/groups/membership/' + this.group.guid + '/' + user.guid)
      .then((response : any) => {
        for(var i in self.users){
          delete self.users[i];
        }
      })
      .catch((e) => {

      });
  }

  reject(user : any){
    var self = this;
    this.client.delete('api/v1/groups/membership/' + this.group.guid + '/' + user.guid)
      .then((response : any) => {
        for(var i in self.users){
          delete self.users[i];
        }
      })
      .catch((e) => {

      });
  }

}
