import { Component, EventEmitter } from '@angular/core';
import { Location } from '@angular/common';
import { Router } from '@angular/router';

import { GroupsService } from '../groups-service';

@Component({
  selector: 'minds-groups-settings-button',
  inputs: ['group'],
  template: `
    <button class="material-icons" (click)="toggleMenu($event)">settings <i *ngIf="group['is:muted']" class="minds-groups-button-badge material-icons">notifications_off</i></button>

    <ul class="minds-dropdown-menu" [hidden]="!showMenu" >
      <li class="mdl-menu__item" [hidden]="group['is:muted']" (click)="mute()" i18n>Disable Notifications</li>
      <li class="mdl-menu__item" [hidden]="!group['is:muted']" (click)="unmute()" i18n>Enable Notifications</li>
      <li class="mdl-menu__item" *ngIf="group['is:owner']" [hidden]="group.deleted" (click)="deletePrompt()" i18n>Delete Group</li>
    </ul>
    <minds-bg-overlay (click)="toggleMenu($event)" [hidden]="!showMenu"></minds-bg-overlay>

    <m-modal [open]="group['is:owner'] && isGoingToBeDeleted">
      <div class="mdl-card__supporting-text">
        <p i18n>Are you sure you want to delete {{ group.name }}? This action cannot be undone.</p>
      </div>
      <div class="mdl-card__actions">
        <button (click)="delete()" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
          <!-- i18n -->Confirm<!-- /i18n -->
        </button>
        <button (click)="cancelDelete()" class="mdl-button mdl-js-button mdl-button--colored">
          <!-- i18n -->Cancel<!-- /i18n -->
        </button>
      </div>
    </m-modal>
  `,
  providers: [ GroupsService ]
})

export class GroupsSettingsButton {

  group: any = {
    'is:muted': false,
    deleted: false
  };
  showMenu: boolean = false;

  isGoingToBeDeleted: boolean = false;

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
  }

  cancelDelete() {
    this.isGoingToBeDeleted = false;
  }

  delete(){

    if (!this.isGoingToBeDeleted) {
      return;
    }

    this.group.deleted = true;

    this.service.deleteGroup(this.group)
    .then((deleted) => {
      this.group.deleted = deleted;

      if (deleted) {
        this.router.navigate(['/groups/member']);
      }
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
