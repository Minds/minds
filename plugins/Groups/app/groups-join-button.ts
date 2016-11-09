import { Component, Inject, EventEmitter } from '@angular/core';

import { GroupsService } from './groups-service';
import { SessionFactory } from '../../services/session';
import { MindsGroupListResponse } from '../../interfaces/responses';

@Component({
  selector: 'minds-groups-join-button',

  inputs: ['_group: group'],
  providers: [ GroupsService ],
  outputs:[ 'membership' ],
  template: `
    <button class="minds-group-join-button" *ngIf="!group['is:banned'] && !group['is:awaiting'] && !group['is:invited'] && !group['is:member']" (click)="join()" i18n>Join</button>
    <span *ngIf="group['is:invited'] &amp;&amp; !group['is:member']">
      <button class="minds-group-join-button" (click)="accept()" i18n>Accept</button>
      <button class="minds-group-join-button" (click)="decline()" i18n>Decline</button>
    </span>
    <button class="minds-group-join-button subscribed " *ngIf="group['is:member']" (click)="leave()" i18n>Leave</button>
    <button class="minds-group-join-button" *ngIf="group['is:awaiting']" (click)="cancelRequest()" i18n>Cancel request</button>
    <m-modal-signup-on-action [open]="showModal" (closed)="showModal = false" action="join a group" *ngIf="!session.isLoggedIn()"></m-modal-signup-on-action>
  `
})

export class GroupsJoinButton {

  minds;
  showModal : boolean = false;
  group : any;
  session = SessionFactory.build();
  membership: EventEmitter<any> = new EventEmitter();


  constructor(public service: GroupsService){
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

    if (this.isPublic()) {
      this.group['is:member'] = true;
    }

    this.service.join(this.group)
      .then(() => {
        if (this.isPublic()) {
          this.group['is:member'] = true;
          this.membership.next({
            member: true
          });
          return;
        }
        this.membership.next({});
        this.group['is:awaiting'] = true;
      })
      .catch(e => {
        this.group['is:member'] = false;
        this.group['is:awaiting'] = false;
      });
  }

  /**
   * Leave a group
   */
  leave(){
    this.service.leave(this.group)
    .then(() => {
      this.group['is:member'] = false;
      this.membership.next({
        member: false
      });
    })
    .catch(e => {
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
        this.membership.next({
          member: true
        });
      }
    });
  }

  /**
   * Cancel a group joining request
   */
  cancelRequest(){
    this.group['is:awaiting'] = false;

    this.service.cancelRequest(this.group)
    .then((done: boolean) => {
      this.group['is:awaiting'] = !done;
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
