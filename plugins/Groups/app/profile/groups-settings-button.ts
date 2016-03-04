import { Component, EventEmitter } from 'angular2/core';
import { CORE_DIRECTIVES } from 'angular2/common';
import { Router, RouteParams, RouterLink, Location, ROUTER_DIRECTIVES } from 'angular2/router';

import { Client } from '../../../services/api';


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
            <p><input type="checkbox" #sure> I'm 100% sure.</p>
          </div>
          <div class="minds-modal-dialog-actions">
            <button (click)="delete()" [disabled]="!sure.checked" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
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
  directives: [ CORE_DIRECTIVES ]
})

export class GroupsSettingsButton {

  group: any = {
    'is:muted': false,
    deleted: false
  };
  showMenu: boolean = false;

  isGoingToBeDeleted: boolean = false;

  constructor(public client : Client, public router: Router) {
  }

  mute(){
    this.group['is:muted'] = true;
    this.client.post(`api/v1/groups/notifications/${this.group.guid}/mute`, { })
      .then((response : any) => {
        this.group['is:muted'] = true;
      })
      .catch((e) => {
        this.group['is:muted'] = false;
      });
    this.showMenu = false;
  }

  unmute(){
    this.group['is:muted'] = false;
    this.client.post(`api/v1/groups/notifications/${this.group.guid}/unmute`, { })
    .then((response : any) => {
      this.group['is:muted'] = false;
    })
    .catch((e) => {
      this.group['is:muted'] = true;
    });
    this.showMenu = false;
  }

  deletePrompt() {
    this.isGoingToBeDeleted = true;
  }

  cancelDelete() {
    this.isGoingToBeDeleted = false;
  }

  delete(){

    if (!this.isGoingToBeDeleted) {
      return;
    }

    this.group.deleted = true;
    this.client.delete(`api/v1/groups/group/${this.group.guid}`, { })
    .then((response : any) => {
      this.group.deleted = true;
      this.router.navigate([ '/Groups', { 'filter': 'member' } ]);
    })
    .catch((e) => {
      this.group.deleted = false;
    });
    this.showMenu = false;
    this.isGoingToBeDeleted = false;

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
