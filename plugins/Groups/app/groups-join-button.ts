import { Component, View, Inject } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { RouterLink, Router, RouteParams } from "angular2/router";

import { GroupsService } from './groups-service';

import { Client } from '../../services/api';
import { SessionFactory } from '../../services/session';
import { Material } from '../../directives/material';
import { MindsGroupListResponse } from '../../interfaces/responses';
import { SignupOnActionModal } from '../../components/modal/modal';


@Component({
  selector: 'minds-groups-join-button',

  properties: ['_group: group'],
  bindings: [ GroupsService ]
})
@View({
  template: `
    <button class="minds-group-join-button" *ngIf="!group['is:awaiting'] && !group['is:invited'] && !group['is:member']" (click)="join()">Join</button>
    <span *ngIf="group['is:invited'] &amp;&amp; !group['is:member']">
      <button class="minds-group-join-button" (click)="accept()">Accept</button>
      <button class="minds-group-join-button" (click)="decline()">Decline</button>
    </span>
    <button class="minds-group-join-button subscribed " *ngIf="group['is:member']" (click)="leave()">Leave</button>
    <button class="minds-group-join-button" *ngIf="group['is:awaiting']" (click)="cancelRequest()">Cancel request</button>
    <m-modal-signup-on-action [open]="showModal" (closed)="showModal = false" action="join a group" *ngIf="!session.isLoggedIn()"></m-modal-signup-on-action>
  `,
  directives: [ CORE_DIRECTIVES, Material, RouterLink, SignupOnActionModal ]
})

export class GroupsJoinButton {

  minds;
  showModal : boolean = false;
  group : any;
  session = SessionFactory.build();

  constructor(public service: GroupsService, public client: Client,  public router: Router){
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
   * Check if the group is closed
   */
  isPublic() {
    if (this.group.membership != 2)
      return false;
    return true;
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

    if (this.isPublic()) {
      this.group['is:member'] = true;
    }

    this.client.put('api/v1/groups/membership/' + this.group.guid)
      .then((response : any) => {
        if (self.isPublic()) {
          self.group['is:member'] = true;
          // TODO: [emi] Find an Angular way. But Router doesn't reload the page.
          window.location.reload();
          return;
        }

        self.group['is:awaiting'] = true;
      })
      .catch((e) => {
        self.group['is:member'] = false;
        self.group['is:awaiting'] = false;
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
       // TODO: [emi] Find an Angular way. But Router doesn't reload the page.
       window.location.reload();
     })
     .catch((e) => {
      this.group['is:member'] = true;
     });

  }

  /**
   * Accept joining a group
   */
  accept(){
    this.group['is:member'] = true;
    this.group['is:invited'] = false;

    this.service.acceptInvitation(this.group)
    .then((done: boolean) => {
      this.group['is:member'] = done;
      this.group['is:invited'] = !done;

      if (done) {
        // TODO: [emi] Find an Angular way. But Router doesn't reload the page.
        window.location.reload();
      }
    });
  }

  /**
   * Cancel a group joining request
   */
  cancelRequest(){
    var self = this;
    this.group['is:awaiting'] = false;
    this.client.post(`api/v1/groups/membership/${this.group.guid}/cancel`, {})
      .then((response : any) => {
        if (response.done) {
          self.group['is:awaiting'] = false;
        } else {
          self.group['is:awaiting'] = true;
        }
      })
      .catch((e) => {
        self.group['is:awaiting'] = true;
      });

  }

  /**
   * Decline joining a group
   */
  decline(){
    this.group['is:member'] = false;
    this.group['is:invited'] = false;

    this.service.declineInvitation(this.group)
    .then((done: boolean) => {
      this.group['is:member'] = false;
      this.group['is:invited'] = !done;
    });
  }

}
