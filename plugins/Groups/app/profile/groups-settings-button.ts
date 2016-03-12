import { Component, EventEmitter } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { Router, RouteParams, RouterLink, Location, ROUTER_DIRECTIVES } from 'angular2/router';

import { GroupsService } from '../groups-service';

@Component({
  selector: 'minds-groups-settings-button',
  inputs: ['group'],
  template: `
    <button class="material-icons" (click)="toggleMenu($event)">settings <i *ngIf="group['is:muted']" class="minds-groups-button-badge material-icons">notifications_off</i></button>

    <ul class="minds-dropdown-menu" [hidden]="!showMenu" >
      <li class="mdl-menu__item" [hidden]="group['is:muted']" (click)="mute()">Disable Notifications</li>
      <li class="mdl-menu__item" [hidden]="!group['is:muted']" (click)="unmute()">Enable Notifications</li>
      <li class="mdl-menu__item" *ngIf="group['is:owner']" [hidden]="group.deleted" (click)="deletePrompt()">Delete Group</li>
    </ul>
    <minds-bg-overlay (click)="toggleMenu($event)" [hidden]="!showMenu"></minds-bg-overlay>

    <minds-groups-modal-dialog *ngIf="group['is:owner'] && isGoingToBeDeleted">
      <div class="minds-groups-modal-dialog-wrapper">
        <div class="mdl-card mdl-shadow--2dp">
          <div class="mdl-card__supporting-text">
            <p>Are you sure you want to delete {{ group.name }}? This action cannot be undone.</p>
            <p><input type="checkbox" #sure (click)="isReallyGoingToBeDeleted = sure.checked"> I'm 100% sure.</p>
          </div>
          <div class="minds-modal-dialog-actions">
            <button (click)="delete()" [disabled]="!isReallyGoingToBeDeleted" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
              Confirm
            </button>
            <button (click)="cancelDelete()" class="mdl-button mdl-js-button mdl-button--colored">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </minds-groups-modal-dialog>
  `,
  directives: [ CORE_DIRECTIVES ],
  bindings: [ GroupsService ]
})

export class GroupsSettingsButton {

  group: any = {
    'is:muted': false,
    deleted: false
  };
  showMenu: boolean = false;

  isGoingToBeDeleted: boolean = false;
  isReallyGoingToBeDeleted: boolean = false;

  constructor(public service: GroupsService, public router: Router) {
  }

  mute(){
    this.group['is:muted'] = true;

    this.service.muteNotifications(this.group)
    .then((isMuted: boolean) => {
      this.group['is:muted'] = isMuted;
    });

    this.showMenu = false;
  }

  unmute(){
    this.group['is:muted'] = true;

    this.service.unmuteNotifications(this.group)
    .then((isMuted: boolean) => {
      this.group['is:muted'] = isMuted;
    });

    this.showMenu = false;
  }

  deletePrompt() {
    this.isGoingToBeDeleted = true;
    this.isReallyGoingToBeDeleted = false;
  }

  cancelDelete() {
    this.isGoingToBeDeleted = false;
    this.isReallyGoingToBeDeleted = false;
  }

  delete(){

    if (!this.isGoingToBeDeleted || !this.isReallyGoingToBeDeleted) {
      return;
    }

    this.group.deleted = true;

    this.service.deleteGroup(this.group)
    .then((deleted) => {
      this.group.deleted = deleted;

      if (deleted) {
        this.router.navigate([ '/Groups', { 'filter': 'member' } ]);
      }
    });

    this.showMenu = false;
    this.isGoingToBeDeleted = false;
    this.isReallyGoingToBeDeleted = false;
  }

  toggleMenu(e){
    e.stopPropagation();
    if(this.showMenu){
      this.showMenu = false;

      return;
    }
    this.showMenu = true;
    // TODO: [emi] Maybe refresh state?
  }

  ngOnDestroy(){
  }

}
