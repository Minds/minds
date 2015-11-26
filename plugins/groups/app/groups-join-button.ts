import { Component, View, NgFor, NgIf, NgClass, Observable, Inject, FORM_DIRECTIVES} from 'angular2/angular2';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { Material } from '../../directives/material';
import { InfiniteScroll } from '../../directives/infinite-scroll';
import { MindsGroupListResponse } from '../../interfaces/responses';
import { SignupOnActionModal } from '../../components/modal/modal';


@Component({
  selector: 'minds-groups-join-button',
  viewBindings: [ Client ],
  properties: ['_group: group']
})
@View({
  template: `
    <button class="minds-group-join-button" *ng-if="!group.member" (click)="join()">Join</button>
    <button class="minds-group-join-button subscribed " *ng-if="group.member" (click)="leave()">Leave</button>
    <m-modal-signup-on-action [open]="showModal" (closed)="showModal = false" action="join a group" *ng-if="!session.isLoggedIn()"></m-modal-signup-on-action>
  `,
  directives: [ NgFor, NgIf, NgClass, Material, RouterLink, InfiniteScroll, SignupOnActionModal ]
})

export class GroupsJoinButton {

  minds;
  showModal : boolean = false;
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
    if(!this.session.isLoggedIn()){
      this.showModal = true;
      return;
    }

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
