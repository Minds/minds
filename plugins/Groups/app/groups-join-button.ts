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

  properties: ['_group: group']
})
@View({
  template: `
    <button class="minds-group-join-button" *ngIf="!group['is:invited'] && !group['is:member']" (click)="join()">Join</button>
    <span *ngIf="group['is:invited'] &amp;&amp; !group['is:member']">
      <button class="minds-group-join-button" (click)="accept()">Accept</button>
      <button class="minds-group-join-button" (click)="decline()">Decline</button>
    </span>
    <button class="minds-group-join-button subscribed " *ngIf="group['is:member']" (click)="leave()">Leave</button>
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
    if(this.group['is:member'])
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
    this.group['is:member'] = true;
    this.client.put('api/v1/groups/membership/' + this.group.guid)
      .then((response : any) => {
        self.group['is:member'] = true;
      })
      .catch((e) => {
        self.group['is:member'] = false;
      });

  }

  /**
   * Leave a group
   */
  leave(){

    var self = this;
    this.group['is:member'] = false;
    this.client.delete('api/v1/groups/membership/' + this.group.guid)
     .then((response : any) => {
       self.group['is:member'] = false;
     })
     .catch((e) => {
      this.group['is:member'] = true;
     });

  }

  /**
   * Accept joining a group
   */
  accept(){
    if(!this.session.isLoggedIn()){
      this.showModal = true;
      return;
    }

    var self = this;
    this.group['is:member'] = true;
    this.group['is:invited'] = false;
    this.client.post(`api/v1/groups/invitations/${this.group.guid}/accept`, {})
      .then((response : any) => {
        if (response.done) {
          self.group['is:member'] = true;
          self.group['is:invited'] = false;
        } else {
          self.group['is:member'] = false;
          self.group['is:invited'] = true;
        }
      })
      .catch((e) => {
        self.group['is:member'] = false;
        self.group['is:invited'] = true;
      });

  }

  /**
   * Decline joining a group
   */
  decline(){
    if(!this.session.isLoggedIn()){
      this.showModal = true;
      return;
    }

    var self = this;
    this.group['is:member'] = false;
    this.group['is:invited'] = false;
    this.client.post(`api/v1/groups/invitations/${this.group.guid}/decline`, {})
      .then((response : any) => {
        self.group['is:member'] = false;

        if (response.done) {
          self.group['is:invited'] = false;
        } else {
          self.group['is:invited'] = true;
        }
      })
      .catch((e) => {
        self.group['is:member'] = false;
        self.group['is:invited'] = true;
      });

  }

}
