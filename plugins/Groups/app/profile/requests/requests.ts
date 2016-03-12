import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { RouterLink, RouteParams } from "angular2/router";

import { GroupsService } from '../../groups-service';

import { Client } from '../../../../services/api';
import { SessionFactory } from '../../../../services/session';
import { Material } from '../../../../directives/material';
import { InfiniteScroll } from '../../../../directives/infinite-scroll';
import { UserCard } from '../../../../controllers/cards/cards';


@Component({
  selector: 'minds-groups-profile-requests',
  bindings: [ GroupsService ],
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

	constructor(public service: GroupsService, public client: Client){

	}

  set _group(value : any){
    this.group = value;
    this.load();
    this.minds = window.Minds;
  }

  load(refresh : boolean = false){
    this.service.infiniteList(this, {
      endpoint: `membership/${this.group.guid}/requests`,
      refresh,
      collection: 'users',
      query: {
        limit: 12
      }
    });
  }

  accept(user : any, index: number){
    var self = this;
    this.client.put('api/v1/groups/membership/' + this.group.guid + '/' + user.guid)
      .then((response : any) => {
        self.users.splice(index, 1);
      })
      .catch((e) => {

      });
  }

  reject(user : any, index: number){
    var self = this;
    this.client.delete('api/v1/groups/membership/' + this.group.guid + '/' + user.guid)
      .then((response : any) => {
        self.users.splice(index, 1);
      })
      .catch((e) => {

      });
  }

}
