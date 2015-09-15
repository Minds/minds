import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { Client } from 'src/services/api';
import { SessionFactory } from 'src/services/session';
import { Material } from 'src/directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsGroupListResponse } from 'src/interfaces/responses';
import { GroupsCreator } from './groups-creator';

@Component({
  selector: 'minds-groups-join-button',
  viewBindings: [ Client ],
  properties: ['_group: group']
})
@View({
  template: '<button class="minds-group-join-button mdl-button mdl-js-button mdl-button--raised mdl-button--colored" *ng-if="!group.member" (click)="join()">Join</button> \
    <button class="minds-group-join-button subscribed mdl-button mdl-js-button mdl-button--raised mdl-button--colored" *ng-if="group.member" (click)="leave()">Leave</button>',
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, InfiniteScroll ]
})

export class GroupsJoinButton {

  minds;

  group : any;
  session = SessionFactory.build();

  constructor(public client: Client){
    this.minds = window.Minds;
  }

  set _group(value : any){
    this.group = value;
  }

  /**
   * Check if is a member
   */
  isMember(){
    if(this.group.member)
      return true;
    return false;
  }

  /**
   * Join a group
   */
  join(){

    var self = this;
    this.group.member = true;
    this.client.put('api/v1/groups/membership/' + this.group.guid)
      .then((response : any) => {
        self.group.member = true;
      })
      .catch((e) => {
        self.group.member = false;
      });

  }

  /**
   * Leave a group
   */
  leave(){

    var self = this;
    this.group.member = false;
    this.client.delete('api/v1/groups/membership/' + this.group.guid)
     .then((response : any) => {
       self.group.member = false;
     })
     .catch((e) => {
      this.group.member = true;
     });

  }

}

export { GroupsProfile } from './groups-profile';
export { GroupsCreator } from './groups-creator';
