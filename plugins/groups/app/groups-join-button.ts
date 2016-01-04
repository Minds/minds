import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
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
    <button class="minds-group-join-button" *ngIf="!group.member" (click)="join()">Join</button>
    <button class="minds-group-join-button subscribed " *ngIf="group.member" (click)="leave()">Leave</button>
    <m-modal-signup-on-action [open]="showModal" (closed)="showModal = false" action="join a group" *ngIf="!session.isLoggedIn()"></m-modal-signup-on-action>
  `,
  directives: [ CORE_DIRECTIVES, Material, RouterLink, InfiniteScroll, SignupOnActionModal ]
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
